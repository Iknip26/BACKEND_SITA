<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::with('lecturer.user')->paginate(10);
            return response()->json(['data' => $projects],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('lecturer.user')->findOrFail($id);
            return response()->json(['data' => $project],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'lecturer_id' => 'required|exists:lecturers,id',
                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'status' => 'required|in:bimbingan, revisi, progress',
                'instance' => 'required',
                'isApproved' => 'required|in:Approved,Not Approved,Not yet Approved',
            ]);

            $project = Project::create($request->all());

            return response()->json(['data' => $project], 201);
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
                'lecturer_id' => 'required|exists:lecturers,id',
                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'status' => 'required|in:bimbingan, revisi, progress',
                'instance' => 'required',
                'isApproved' => 'required|in:Approved,Not Approved,Not yet Approved',
            ]);

            $project = Project::findOrFail($id);
            $project->update($request->all());

            return response()->json(['data' => $project], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
