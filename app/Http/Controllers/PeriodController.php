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
            $request->validate([
                'semester' => 'required|string|max:255',
                'year' => 'required|integer',

                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            $data = $request->all();
            $data['status'] = "inProgresss";
            $period = Period::create($data);

            $activePeriod = Period::findOrFail("inProgress")->first();

            if ($activePeriod) {
                $activePeriod->status = 'ended';
                $activePeriod->save();
            }

            return response()->json(['message' => "data successfully created",
            'data' => $period],
             201);
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
