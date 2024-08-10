<?php

namespace App\Http\Controllers;

use App\Http\Resources\CounselingResource;
use App\Models\Counseling;
use App\Models\Lecturer;
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
                $counselings = Counseling::with('student.user')->with('student.user')->with('project')->where('student_id',$student->id)->orderByDesc('id')->paginate();

            }
        // Get the data and meta separately
        $response = CounselingResource::collection($counselings)->response();
        $headers = $response->headers->all();

        return response()->json([
            'headers' => $headers, // Adding headers for debugging
            'message' => "data retrieved successfully",
            'data' => $response->getData(true)['data'], // The actual data
            'meta' => $response->getData(true)['meta'], // Pagination meta information
            'links' => $response->getData(true)['links'], // Pagination links

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
                $Mahasiswa = Student::where("user_id",$user->id)->first();
                // dd($Mahasiswa);
                $counseling = Counseling::with('student.user')->with('project')->with('lecturer.user')->findOrFail($id);
                if($counseling->student_id != $Mahasiswa->id){
                    return response()->json([
                        'message' => "unauthorized",
                    ],401);
                }
            }
            elseif($user->role == "Dosen" ){
                $Dosen = Lecturer::where("user_id",$user->id)->first();
                $counseling = Counseling::with('student')->with('project')->with('lecturer')->findOrFail($id);
                if($counseling->lecturer_id != $Dosen->id){
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
            $request->validate([
                'student_id' => 'required',
                'lecturer_id' => 'required',
                'project_id' => 'required',
                'date' => 'required|date',
                'subject' => 'required',
                'lecturer_note' => 'nullable',
                'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:100000',
                'progress' => 'required',

            ]);

            $data = $request->all();

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('counseling_files', 'public');
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
                'student_id' => 'required',
                'lecturer_id' => 'required',
                'project_id' => 'required',
                'tanggal' => 'required|date',
                'subjek' => 'required',
                'catatan_dosen' => 'nullable',
                'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
                'progress' => 'required',
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
