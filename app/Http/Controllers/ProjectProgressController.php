<?php

namespace App\Http\Controllers;

use App\Models\ProjectProgress;
use Illuminate\Http\Request;

class ProjectProgressController extends Controller
{
    public function index()
    {
        $progress = ProjectProgress::all();
        return response()->json($progress);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'counseling_id' => 'required|exists:counseling,id',
            'lecturer_note' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $projectProgress = ProjectProgress::create($validatedData);
        return response()->json($projectProgress, 201);
    }

    public function show(ProjectProgress $projectProgress)
    {
        return response()->json($projectProgress);
    }

    public function update(Request $request, ProjectProgress $projectProgress)
    {
        $validatedData = $request->validate([
            'counseling_id' => 'required|exists:counseling,id',
            'lecturer_note' => 'nullable|string',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $projectProgress->update($validatedData);
        return response()->json($projectProgress);
    }

    public function destroy(ProjectProgress $projectProgress)
    {
        $projectProgress->delete();
        return response()->json(null, 204);
    }
}
