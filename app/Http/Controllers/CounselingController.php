<?php

namespace App\Http\Controllers;

use App\Http\Resources\CounselingResource;
use App\Models\Counseling;
use App\Models\Lecturer;
use App\Models\Project;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CounselingController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            // dd($user);
            if($user->role == 'Dosen'){
                // dd($user);
                $dosen = Lecturer::where('user_id', $user->id)->first();
                // dd($dosen);
                $counselings = Counseling::with('project.lecturer.user')->with('student.user')->where('lecturer_id',$dosen->id)->orderByDesc('id')->paginate(10);

            }
            else if($user->role == 'Mahasiswa'){
                $student = Student::where('user_id',$user->id)->first();
                $counselings = Counseling::with('project')->orderByDesc('id')->paginate(10);
            }
        // Get the data and meta separately

        return response()->json([
            'message' => 'Projects retrieved successfully. There are ' . $counselings->total() . " projects",
            'data' => CounselingResource::collection($counselings),
            'meta' => [
                'total' => $counselings->total(),
                'per_page' => $counselings->perPage(),
                'current_page' => $counselings->currentPage(),
                'last_page' => $counselings->lastPage(),
                'next_page' => $counselings->appends(request()->query())->nextPageUrl(),
                'previous_page' => $counselings->appends(request()->query())->previousPageUrl()
            ]
        ], 200);
            // else{
            //     return response()->json(["Not Authenticate"],400);
            // }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // dd($id);
            $user = Auth::user();
            if($user->role == "Mahasiswa" ){
                $Mahasiswa = $user->student;
                $counseling = Counseling::with('project')->findOrFail($id);
                // dd($counseling);
                // dd($Mahasiswa->id."=".$counseling->project->student_id);
                if($counseling->project->student_id != $Mahasiswa->id){
                    return response()->json([
                        'message' => "unauthorized",
                    ],401);
                }
            }
            elseif($user->role == "Dosen" ){
                $Dosen = $user->lecturer;
                $counseling = Counseling::with('project')->findOrFail($id);
                if($counseling->project->lecturer_id != $Dosen->id){
                    return response()->json([
                        'message' => "unauthorized",
                    ],401);
                }
            }
            return response()->json([
                'message' => "data retrieved successfully",
                'data' => new CounselingResource($counseling)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'project_id' => 'required',
                'date' => 'nullable|date',
                'subject' => 'required',
                'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:100000',
                'description' => 'nullable',
                'lecturer_note' => 'nullable'
            ]);
            $id = $data['project_id'];
            $counseling_amount = Counseling::where('project_id',$request->project_id)->count();
            $project = Project::findOrFail($id)->when('student')->first();
            // dd();
            $data['progress']=0;

            if ($request->hasFile('file')) {
                $fileExtension = $request->file('file')->getClientOriginalExtension();
                $filePath = "{$project->student->user->first_name}_{$project->student->user->last_name}_{$counseling_amount}.{$fileExtension}";
                $data['file'] = $filePath;

            }

            $counseling = Counseling::create($data);

            return response()->json(['data' => $counseling], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'project_id' => 'required',
                'date' => 'nullable|date',
                'subject' => 'required',
                'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:100000',
                'progress' => 'required|integer|max:100,min:0',
                'description' => 'nullable',
                'lecturer_note' => 'nullable'
            ]);

            $counseling = Counseling::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($counseling->file) {
                    Storage::disk('public')->delete($counseling->file);
                }
                $filePath = $request->file('file')->store('counseling_files', 'public');
                $data['file'] = $filePath;
            }

            $counseling->update($data);

            return response()->json(['data' => $counseling], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $counseling = Counseling::findOrFail($id);
            if ($counseling->file) {
                Storage::disk('public')->delete($counseling->file);
            }
            $counseling->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
