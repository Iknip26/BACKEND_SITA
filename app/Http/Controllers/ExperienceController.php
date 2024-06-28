<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            $experiences = Experience::where('student_id',$student->id)->with('student.user')->get();
            // dd($experiences);
            return response()->json(['data' => $experiences],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            $experience = Experience::with('student.user')->findOrFail($id);
            return response()->json(['data' => $experience]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            $request->validate([

                'position' => 'required',
                'company_name' => 'required',
                'field' => 'required',
                'duration' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);
            $data = $request->all();
            $data['student_id']=$student->id;
            $experience = Experience::create($data);

            return response()->json(['data' => $experience], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            $request->validate([

                'position' => 'required',
                'company_name' => 'required',
                'field' => 'required',
                'duration' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $experience = Experience::findOrFail($id);
            $data = $request->all();
            $data['student.id']=$student->id;
            $experience->update($data);

            return response()->json(['data' => $experience], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $rowsDeleted = Experience::where('student_id', $id)->delete();

            return response()->json([
                'message' => "Data dengan id_user $id berhasil dihapus.",
                'rows_deleted' => $rowsDeleted,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}