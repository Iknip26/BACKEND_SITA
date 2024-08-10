<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Api\AnnouncementController as ApiAnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\PdfParserController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StudentController;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

*/
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::apiResource('/student',StudentController::class);
// Route::get('/student/udpateSkill/', [DosenPageController::class,  'dashboard'])->name('dosen.dashboard');

Route::apiResource('/lecturer',LecturerController::class);
Route::apiResource('/project',ProjectController::class);


Route::apiResource('/period',PeriodController::class);
Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/user', [AuthController::class, 'currentUser']);
    Route::apiResource('/skill', SkillController::class);
    Route::put('/skill/updateSkill/{id}', [SkillController::class, 'updateSkill'])->name('skill.updateSkill');

    Route::apiResource('/counseling',CounselingController::class);
    Route::apiResource('/experience',ExperienceController::class);

    Route::post('/logout',[AuthController::class,'logout']);
});

Route::post('/email/verification-link', [EmailVerificationController::class, 'getVerificationLink']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
Route::get('/download/{filename}',[FileController::class,'download']);
Route::apiResource('/announcement',AnnouncementController::class);
Route::get('/annoucementFile/{path}',[FileController::class,'showAttachment']);
Route::post('/parse-pdf', [PdfParserController::class, 'parse']);
