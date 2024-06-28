<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $students = Student::with('user')->paginate(15);

            return response()->json(['data' => $students],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $student = Student::with('user')->findOrFail($id);
            return response()->json(['data' => $student],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'NIM' => 'required|unique:students',
                'semester' => 'required',
                'IPK' => 'required',
                'SKS' => 'required',
                'phone_number' => 'nullable',
                'link_github' => 'nullable',
                'link_porto' => 'nullable',
                'link_linkedin' => 'nullable'
            ]);

            $student = Student::create($request->all());

            return response()->json(['data' => $student], 201);
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
                'user_id' => 'required',
                // 'NIM' => 'required|unique:students,NIM,' . $id,
                'semester' => 'required',
                'IPK' => 'required',
                'SKS' => 'required',
                'phone_number' => 'required',
                'link_github' => 'nullable',
                'link_porto' => 'nullable',
                'link_linkedin' => 'nullable'
            ]);

            $student = Student::findOrFail($id);
            $student->update($request->all());

            return response()->json(['data' => $student], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function updateSkill(){
        echo("sbqhgsqy");
    }
}