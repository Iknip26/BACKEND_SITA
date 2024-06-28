<?php

namespace App\Http\Controllers;

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
                return response()->json(['data' => $counselings],200);
            }
            else if($user->role == 'Mahasiswa'){
                $student = Student::where('user_id',$user->id)->first();
                $counselings = Counseling::with('student.user')->with('student.user')->with('project')->where('student_id',$student->id)->orderByDesc('id')->paginate();
                return response()->json(['data' => $counselings],200);
            }
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
            $counseling = Counseling::with('student.user')->with('project.lecturer.user')->findOrFail($id);
            return response()->json(['data' => $counseling]);
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
                'tanggal' => 'required|date',
                'subjek' => 'required',
                'catatan_dosen' => 'nullable',
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
