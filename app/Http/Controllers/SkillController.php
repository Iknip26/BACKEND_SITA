<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            $skill = Skill::where('student_id', $student->id)->get();
            // dd($skill);
            return response()->json(['data' => $skill]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $skill = Skill::with('student.user')->findOrFail($id,'id');
            return response()->json(['data' => $skill]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id',$user->id)->first();
            // dd($student->id);
            $request->validate([
                'achievement_name' => 'required',
                'achievement_type' => 'required',
                'achievement_level' => 'required',
                'achievement_year' => 'required',
                'description' => '',
            ]);
            $data = $request->all();
            $data['student_id'] = $student->id;

            $skill = Skill::create($data);

            return response()->json(['data' => $skill], 201);
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
                'achievement_name' => 'required',
                'achievement_type' => 'required',
                'achievement_level' => 'required',
                'achievement_year' => 'required',
                'description' => '',
            ]);

            $skill = Skill::findOrFail($id);
            $data = $request->all();
            $data['student_id'] = $student->id;

            $skill->update($data);

            return response()->json(['data' => $skill], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try {
            // $id_user = $request->input('id_user');
            // dd($id_user);
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

    public function updateSkill(Request $request, $id){
        // dd("kena");
        try {

            $student = Student::findOrFail($id);
            $student->update([
                'skill' => $request->input('skill')
            ]);

            return response()->json(['data' => $request->input('skill'), 'message'=>"data berhasil di ubah"], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}