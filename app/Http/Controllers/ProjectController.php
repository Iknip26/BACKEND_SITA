<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Project::with('lecturer.user');
            if ($request->has('dosen')) {
                $searchTerm = $request->input('dosen');
                $query->whereHas('lecturer.user', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
                });
            }
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
            if ($request->has('Approval')) {
                $query->where('Approval', $request->input('Approval'));
            }
            if ($request->has('tools')) {
                $query->where('tools', 'like', '%' . $request->input('tools') . '%');
            }
            $projects = $query->paginate(10);
            return response()->json([
                'message'=>'project retrieved success there is '.$projects->total() ." projects",
                'data' => ProjectResource::collection($projects),
                'meta' => [
                    'total' => $projects->total(),
                    'per_page' => $projects->perPage(),
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('lecturer.user')->findOrFail($id);
            return response()->json(['data' => new ProjectResource($project)], 200);
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
                'status' => 'required|in:bimbingan,revisi,progress',
                'instance' => 'required',
                'Approval' => 'required|in:Approved,Not Approved,Not yet Approved',
            ]);

            $project = Project::create($request->all());

            return response()->json(['message' => "project successfully created",
                'data' => new ProjectResource($project
            )], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Update the validation rule to match the database column

            // dd($request);
            $request->validate([
                'lecturer_id' => 'required|exists:lecturers,id',
                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'status' => 'required|in:bimbingan,revisi,progress',
                'instance' => 'required',
                'Approval' => 'required|in:Approved,Not Approved,Not yet Approved', // Match the database column
            ]);

            $project = Project::findOrFail($id);
            $project->update($request->except(['id'])); // Make sure to use 'Approval' in the request payload

            return response()->json(['data' => new ProjectResource($project)], 200);
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
