<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{
    public function index()
    {
        try {
            // Retrieve paginated students with the associated user
            $students = Student::paginate(15);
            // dd($students);
            // Return response with paginated student data
            return response()->json([
                'message' => 'Students retrieved successfully',
                'data' => StudentResource::collection($students)->response()->getData(true),
                'meta' => [
                    'total' => $students->total(),
                    'per_page' => $students->perPage(),
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                ]
            ], 200);
        } catch (\Exception $e) {
            // Return error response if there's an exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {
            // dd($id);
            $student = Student::with('user')->findOrFail($id);
            return response()->json([
                'message' => 'Student retrieved successfully',
                'data' => new StudentResource($student)
            ], 200);
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

            return response()->json([
                'message' => 'Student created successfully',
                'data' => new StudentResource($student)
            ], 201);
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
                // 'user_id' => 'required',
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
            $data = $request->except(['NIM', 'user_id']); // Exclude NIM and user_id
            $student->update($data);

            return response()->json([
                'message' => 'Student updated successfully',
                'data' => new StudentResource($student)
            ], 200);
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
            return response()->json([
                'message' => 'Student deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
