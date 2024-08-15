<?php

namespace App\Http\Controllers;

use App\Models\ProjectProgress;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectProgressResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProjectProgressController extends Controller
{
    public function index()
    {
        try {
            $progress = ProjectProgress::all();
            return response()->json(['message'=> 'data retrieved successfully',
            'data' => ProjectProgressResource::collection($progress)],200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve project progress.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $lecturer = Auth::user()->load("lecturer");
            dd($lecturer);
            $validatedData = $request->validate([
                'counseling_id' => 'required|exists:counseling,id',
                'lecturer_note' => 'nullable|string',
                'progress' => 'required|integer|min:0|max:100',
            ]);

            $projectProgress = ProjectProgress::create($validatedData);

            return response()->json(["message" => "data created successfully",
            "data" => new ProjectProgressResource($projectProgress)],201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create project progress.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $projectProgress = ProjectProgress::findOrFail($id);
            return new ProjectProgressResource($projectProgress);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project progress not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve project progress.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $projectProgress = ProjectProgress::findOrFail($id);

            $validatedData = $request->validate([
                'counseling_id' => 'required|exists:counseling,id',
                'lecturer_note' => 'nullable|string',
                'progress' => 'required|integer|min:0|max:100',
            ]);

            $projectProgress->update($validatedData);

            return new ProjectProgressResource($projectProgress);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project progress not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update project progress.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $projectProgress = ProjectProgress::findOrFail($id);
            $projectProgress->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project progress not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete project progress.'], 500);
        }
    }
}
