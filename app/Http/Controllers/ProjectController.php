<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Resources\ProjectResource;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
{
    try {
        $user = Auth::user();

        $query = Project::with('lecturer1.user', 'lecturer2.user'); // Include both lecturers

        if ($user->role == "Dosen") {
            $dosen = Lecturer::where('user_id', $user->id)->first();
            $query->where(function ($q) use ($dosen) {
                $q->where('lecturer1_id', $dosen->id)
                  ->orWhere('lecturer2_id', $dosen->id);
            });
        }

        if ($request->has('dosen')) {
            $searchTerm = $request->input('dosen');
            $query->whereHas('lecturer1.user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%');
            })->orWhereHas('lecturer2.user', function ($q) use ($searchTerm) {
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

        $projects = $query->paginate(6);
        return response()->json([
            'message' => 'Projects retrieved successfully. There are ' . $projects->total() . " projects",
            'data' => ProjectResource::collection($projects),
            'meta' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'next_page' => $projects->nextPageUrl(),
                'previous_page' => $projects->previousPageUrl()
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
            $user = Auth::user();

            $activePeriod = Period::where('status', 'inProgress')->first();

            if (!$activePeriod) {
                return response()->json(['error' => 'No active period found'], 400);
            }

            $request->validate([

                'title' => 'required',
                'agency' => 'required',
                'description' => 'required',
                'tools' => 'required',
                'status' => 'required|in:bimbingan,revisi,progress',
                'instance' => 'required',
                'Approval' => 'required|in:Approved,Not Approved,Not yet Approved',
            ]);

            $data = $request->all();
            $data['year'] = $activePeriod->year;
            if($user->role == 'lecturer')

            $project = Project::create($data);

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
