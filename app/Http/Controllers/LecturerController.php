<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class LecturerController extends Controller
{
    public function index()
    {
        try {
            $lecturers = Lecturer::with('user')->paginate(15);
            return response()->json(['data' => $lecturers],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $lecturer = Lecturer::with('user')->findOrFail($id);
            return response()->json(['data' => $lecturer],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'front_title' => 'required',
                'back_title' => 'required',
                'NID' => 'required|unique:lecturers',
                'max_quota' => 'required',
                'phone_number' => 'required',
            ]);

            $lecturer = Lecturer::create($request->all());

            return response()->json(['data' => $lecturer], 201);
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
                'front_title' => 'required',
                'back_title' => 'required',
                // 'NID' => 'required|unique:lecturers,NID,' . $id,
                'max_quota' => 'required',
                'phone_number' => 'required',
            ]);

            $lecturer = Lecturer::findOrFail($id);
            $lecturer->update($request->all());

            return response()->json(['data' => $lecturer], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $lecturer = Lecturer::findOrFail($id);
            $lecturer->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
