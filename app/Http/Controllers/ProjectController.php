<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Resources\ProjectResource;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
{
    try {
        $user = Auth::user();
        // dd($user);

        $query = Project::with('lecturer1.user', 'lecturer2.user');

        if ($user->role == "Dosen") {
            $dosen = Lecturer::where('user_id', $user->id)->first();
            $query->where(function ($q) use ($dosen) {
                $q->where('lecturer1_id', $dosen->id)
                  ->orWhere('lecturer2_id', $dosen->id);
            });

            if($dosen->isKaprodi == false){
                $query->where('Approval_kaprodi','Approved');
            }
        }

        elseif($user->role == "Mahasiswa"){
            $query->where('Approval_kaprodi','Approved');
        }

        if ($request->has('dosen')) {
            $searchTerm = $request->input('dosen');
            $query->whereHas('lecturer1.user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            })->orWhereHas('lecturer2.user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('Approval')) {
            $query->where('Approval', $request->input('Approval'));
        }

        if ($request->has('tools')) {
            $query->where('tools', 'like', '%' . $request->input('tools') . '%');
        }

        if($request->has('kaprodi')){
            $query->where('Approval_kaprodi',$request->input('kaprodi'));
        }

        $projects = $query->paginate(6);
        return response()->json([
            'message' => 'Projects retrieved successfully. There are ' . $projects->total() . " projects",
            'data' => ProjectResource::collection($projects),
            'meta' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'next_page' => $projects->appends(request()->query())->nextPageUrl(),
                'previous_page' => $projects->appends(request()->query())->previousPageUrl()
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function show($id)
    {
        try {

            $project = Project::with('lecturer1.user')->with('lecturer2.user')->findOrFail($id);

            return response()->json(['message' => 'data retrieved successfully','data' => new ProjectResource($project)], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $activePeriod = Period::where('status', 'inProgress')->first();

            if (!$activePeriod) {
                return response()->json(['error' => 'No active period found'], 400);
            }

            $validated = $request->validate([
                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'instance' => 'required',
            ]);

            $data = $validated;
            $data['year'] = $activePeriod->year;
            $data['Approval_kaprodi'] = 'Not yet Approved';

            if($user->role == "Mahasiswa"){
                $request->validate([
                    'lecturer1_id' => 'required',
                    'lecturer2_id' => 'required',
                ]);
                $data['lecturer1_id'] = $request->lecturer1_id;
                $data['lecturer2_id'] = $request->lecturer2_id;
                $data['Approval_lecturer_1'] = 'Not yet Approved';
                $data['Approval_lecturer_2'] = 'Not yet Approved';
                $data['student_id'] = $user->student->id;
                $data['status'] = 'process';
                $data['uploadedBy'] = 'Mahasiswa';

            }

            elseif($user->role == 'Dosen'){
                $dosen = Lecturer::where('user_id', $user->id)->firstOrFail();
                // dd($dosen);
                $data['uploadedBy'] = 'Dosen';
                $data['lecturer1_id'] = $dosen->id;
                $data['status'] = 'not taken yet';
                // $data['Approval_lecturer_1'] = 'Approved';
            }


            $project = Project::create($data);


            return response()->json(['message' => "project successfully created",

                'data' => new ProjectResource($project
            )], 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'status' => 'required|in:counseling,not approved,process,not taken yet',
                'instance' => 'required',
            ]);

            $project = Project::findOrFail($id);

            $project->update($validated);

            return response()->json(['data' => new ProjectResource($project)], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            $project->delete();

            return response()->json(["message" => " udah di delete sayang"], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function Approval(Request $request, $id){
        try{

            $project = Project::findOrFail($id);
            $user = Auth::user();
            $dosen = Lecturer::where('user_id',$user->id)->first();
            if($dosen->id == $project->lecturer1_id){
                $validated = $request->validate([
                    'Approval' => 'required|in:Approved,Not Approved',
                ]);

                $project->update(['Approval_lecturer_1' => $validated['Approval']]);
            }
            elseif($dosen->id == $project->lecturer2_id){
                $validated = $request->validate([
                    'Approval' => 'required|in:Approved,Not Approved',
                ]);

                $project->update(['Approval_lecturer_2' => $validated['Approval']]);
                $dosen->remaining_quota -= 1;
                $dosen->save;
            }
            else{
                return response()->json(['error' => 'Not permitted'], 403);
            }

            if($project['Approval_lecturer_2'] == "Not yet Approved" || $project['Approval_lecturer_1']=="Not yet Approved"){
                $project->update(['status' => 'process']);
            }
            elseif($project['Approval_lecturer_1'] == "Not Approved" || $project['Approval_lecturer_2'] == "Not Approved"){
                $project->update(['status' => 'not approved']);
            }
            elseif ($project['Approval_lecturer_1'] == "Approved" && $project['Approval_lecturer_2'] == "Approved") {
                $project->update(['status' => 'counseling']);
            }

            return response()->json(['message' => 'Approval updated successfully'], 200);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],500);
        }
    }

    public function ApprovalKaprodi(Request $request, $id){
        try {
            $project = Project::findOrFail($id);
            $user = Auth::user();
            $dosen = Lecturer::where('user_id',$user->id)->first();
            if($dosen->isKaprodi != true){
                return response()->json(['message' => 'you are not kaprodi'],403);
            }

            $validated = $request->validate([
                'Approval' => 'required|in:Approved,Not Approved',
            ]);

            if($request->Approval == "Approved"){
                $project->update([
                    'Approval_kaprodi' => $validated['Approval'],
                    'Status' => "not taken yet"
            ]);
        }
            elseif ($request->Approval == "Not Approved") {
                $project->update([
                    'Approval_kaprodi' => $validated['Approval'],
                    'Status' => "not approved"
                ]);
            }

            return response()->json(['message' => 'Approval updated successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],500);
        }
    }

    public function Dospem2(Request $request, $id){
        try {
            $project = Project::findOrFail($id);
            $user = Auth::user();

            $validated = $request->validate([
                'lecturer2_id' => 'required',
            ]);
            $data = $validated;
            $data['status'] = 'process';
            $project->update($data);
            return response()->json(['message' => 'dospem updated successfully'], 200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],500);
        }
    }

    public function getMyProject(){
        try {
            $user= Auth::user()->load('student');


        if (!$user->student) {
            return response()->json(['message' => 'No student profile found for this user.'], 404);
        }
            $project = null;
            $project = Project::where('student_id',$user->student->id)->first();

            if (!$project) {
                return response()->json(['message' => 'kamu belum mengajukan judul'], 200);
            }
            else if($project->status == "not approved"){
                return response()->json(["message" => "proyekmu ditolak"],200);
            }
            else if($project->status == "process"){
                return response()->json(["message" => "proyekmu sedang diproses"],200);
            }
            else if($project->status == "counseling"){
                return response()->json(["message" => "kamu sudah bisa bimbingan"],200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()],500);
        }
    }

}
