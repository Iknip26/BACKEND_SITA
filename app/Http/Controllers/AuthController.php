<?php

namespace App\Http\Controllers;

use App\Http\Resources\LecturerResource;
use App\Http\Resources\StudentResource;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:1|max:255',
            'first_name' => 'required|min:1|max:255',
            'last_name' => 'required|min:1|max:255',
            'role' => 'required',
            'password' => 'required',
        ]);

        $hashedPassword  = Hash::make($request->password);

        // Register user
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            // 'profile_photo' => "https://t3.ftcdn.net/jpg/06/03/30/74/360_F_603307418_jya3zntHWjXWn3WHn7FOpjFevXwnVP52.jpg",
            'role' => $request->role,
            'password' => $hashedPassword,
        ]);


        // dd($request->user());

        $emailVerificationController = new EmailVerificationController();
        $emailVerificationController->getVerificationLink($request);
        $emailVerificationController->sendVerificationEmail($request);

        // Response, create token
        return response()->json([
            'message' => 'User berhasil mendaftar',
            'token' => $user->createToken('user_login')->plainTextToken
        ], 201);
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        // Auth check
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }
        // if(!$user->email_verified_at){
        //     return response()->json(['message'=> "Email belum terferifikasi"]);
        // }

        // Response, create token
        return response()->json([
            'message' => 'Berhasil login',
            'token' => $user->createToken('user_login')->plainTextToken
        ]);
    }


    public function logout(Request $request){
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'User berhasil logout'], 201);
    }

    public function currentUser(Request $request){
        $tmpdata = Auth::user();
        $profileData = null;
        if ($tmpdata->role == "Mahasiswa") {
            $student = Student::with('achievements')->where('user_id', $tmpdata->id)->first();
            if ($student) {
                $profileData = new StudentResource($student);
            }
        } else {
            $profileData = new LecturerResource(Lecturer::where('user_id', $tmpdata->id)->first());
        }
        // dd($profileData);
        return response()->json([
            'message' => "Data User Yang Login.",
            'user_data' => $tmpdata,
            'profile_data' => $profileData,
        ]);
    }

    public function UpdateCurrentUser(Request $request) {
        $user = Auth::user();
        if($user->role = "Mahasiswa"){
            $request -> validate([
                "profile_photo"
            ]);
        }
    }
}