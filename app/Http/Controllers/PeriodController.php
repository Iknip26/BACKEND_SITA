<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PeriodController extends Controller
{
    public function index()
    {
        try {
            $periods = Period::all();
            return response()->json(['data' => $periods]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $period = Period::findOrFail($id);
            return response()->json(['data' => $period]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'semester' => 'required|string|max:255',
                'year' => 'required|integer',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // Set the status to "inProgress"
            $validatedData['status'] = "inProgress";

            // Create the new Period record
            $period = Period::create($validatedData);

            // Return a success response
            return response()->json([
                'message' => "Data successfully created",
                'data' => $period
            ], 201);

        } catch (QueryException $e) {
            // Return a database error response
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);

        } catch (\Exception $e) {
            // Return a general error response
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'semester' => 'required|string|max:255',
                'year' => 'required|integer',
                'status' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $period = Period::findOrFail($id);
            $period->update($request->all());

            return response()->json(['message' => 'data successfully updated',
            'data' => $period], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $period = Period::findOrFail($id);
            $period->delete();

            return response()->json(["message" => "periode ".$period->year." deleted"], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
