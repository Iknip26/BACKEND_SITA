<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AnnouncementController;
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

// Route::get('/student/udpateSkill/', [DosenPageController::class,  'dashboard'])->name('dosen.dashboard');
Route::apiResource('/period',PeriodController::class);

// harus ada token
Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/user', [AuthController::class, 'currentUser']);
    Route::apiResource('/counseling',CounselingController::class);
    Route::apiResource('/project',ProjectController::class);
    Route::get('/download/{filename}',[FileController::class,'download']);
    Route::get('/annoucementFile/{path}',[FileController::class,'showAttachment']);

    Route::get('/announcement', [AnnouncementController::class, 'index']);
    Route::get('/announcement/{announcement}', [AnnouncementController::class, 'show']);

    // api hanya untuk mahasiswa
    Route::middleware(['role:student'])->group(function(){
        // Route::get('p',function(){return "Welcome to the student dashboard!";});
        Route::post('/student', [StudentController::class, 'store']);
        Route::get('/student/{student}', [StudentController::class, 'show']);
        Route::put('/student/{student}', [StudentController::class, 'update']);
        Route::delete('/student/{student}', [StudentController::class, 'destroy']);
        Route::apiResource('/skill', SkillController::class);
        Route::apiResource('/achievement',AchievementController::class);
        Route::apiResource('/experience',ExperienceController::class);
    });

    // api hanya untuh Dosen
    Route::middleware(['role:lecturer'])->group(function () {
        Route::post('/lecturer', [LecturerController::class, 'store']);
        Route::get('/lecturer/{lecturer}', [LecturerController::class, 'show']);
        Route::put('/lecturer/{lecturer}', [LecturerController::class, 'update']);
        Route::delete('/lecturer/{lecturer}', [LecturerController::class, 'destroy']);
        Route::post('/lecturer/Approval/{id}',[ProjectController::class,'Approval']);

    });

    // api hanya kaprodi tapi kaprodi bisa melihat yang ada di dosen
    Route::middleware(['role:'])->group(function(){
        Route::get('/lecturer',[LecturerController::class,'index']);
        Route::get('/student',[StudentController::class,'index']);
        Route::post('/announcement', [AnnouncementController::class, 'store']);
        Route::put('/announcement/{announcement}', [AnnouncementController::class, 'update']);
        Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'destroy']);
    });

    Route::post('/logout',[AuthController::class,'logout']);
});

Route::post('/email/verification-link', [EmailVerificationController::class, 'getVerificationLink']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);

Route::post('/parse-pdf', [PdfParserController::class, 'parse']);
