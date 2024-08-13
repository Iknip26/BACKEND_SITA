<?php

namespace App\Http\Controllers;

use App\Http\Resources\AchievementResource;
use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AchievementController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $achievements = Achievement::where('student_id', $student->id)->get();
            return AchievementResource::collection($achievements);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Student not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while retrieving achievements'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $data = $request->all();
            $data['student_id'] = $student->id;
            $achievement = Achievement::create($data);
            return response() -> json(['message' => 'data store successfully',
            'data'=> new AchievementResource($achievement),
        ],201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the achievement'], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $achievement = Achievement::findOrFail($id);
            return response()->json([
                'message' => 'Data retrieved successfully',
                'data' => new AchievementResource($achievement)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Achievement not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while retrieving the achievement'], 500);
        }
    }

    public function update(Request $request, Achievement $achievement)
    {
        try {
            $achievement->update($request->all());
            return new AchievementResource($achievement);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the achievement'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $achievement = Achievement::findOrFail($id);
            $achievement->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the achievement'], 500);
        }
    }
}
