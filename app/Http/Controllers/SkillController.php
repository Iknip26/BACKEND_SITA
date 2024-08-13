<?php

namespace App\Http\Controllers;

use App\Http\Resources\SkillResource;
use App\Models\Skill;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            // $skill = skill::where('student_id',$user->id)->get();
            // // dd($skill);
            // return response()->json([
            //     'message'=>'data retrieved successfully',
            //     'data' => SkillResource::collection($skill)
            // ],200);
            $student = Student::where('user_id',$user->id)->first();

            $skillsArray = explode(',', $student->skill);
            // dd($skillsArray);
                        return response()->json([
                'message'=>'data retrieved successfully',
                'data' => $skillsArray,
            ],200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $skill = Skill::where('id', $id)->firstOrFail();
            $student_id = Auth::user()->id;

            if ($student_id != $skill->student_id) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json(['data' => new SkillResource($skill)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Skill not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();

            $request->validate([
                'skill' => 'required|string',
            ]);

            // Convert the input skill string into an array
            $skillsArray = explode(',', $request->skill);

            // Save the skills as a comma-separated string
            $student->skill = implode(',', array_map('trim', $skillsArray));
            $student->save();

            return response()->json([
                'message' => "Data retrieved successfully",
                'user' => $user->first_name . " " . $user->last_name,
                'data' => $student
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
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();

            $request->validate([
                'skill' => 'required',
            ]);

            $data = $request->skill;
            dd($data);

            return response()->json(['data' => ""], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $rowsDeleted = Skill::where('student_id', $id)->delete();

            return response()->json([
                'message' => "Data dengan id_user $id berhasil dihapus.",
                'rows_deleted' => $rowsDeleted,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage by id_user.
     *
     * @param  int  $id_user
     * @return \Illuminate\Http\Response
     */

    public function destroyByIdUser(Request $request)
    {
        try {
            $id_user = $request->input('id_user');
            dd($id_user);
            $rowsDeleted = Skill::where('id_user', $id_user)->delete();

            return response()->json([
                'message' => "Data dengan id_user $id_user berhasil dihapus.",
                'rows_deleted' => $rowsDeleted,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
