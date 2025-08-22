<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\SocialController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\HomeworkController;
use App\Http\Controllers\Admin\ExamController;

use App\Http\Controllers\Student\DashboardController as StudentDashboard;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

Route::get('/login',     [AuthController::class, 'loginform'])->name('loginform');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::get('/register',  [AuthController::class, 'registerform'])->name('registerform');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

Route::view('/error', 'auth.errors.error403')->name('auth.error403');

/*
|--------------------------------------------------------------------------
| Social Login
|--------------------------------------------------------------------------
*/
Route::get('login/google',            [SocialController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback',   [SocialController::class, 'handleGoogleCallback']);

Route::get('login/facebook',          [SocialController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('login/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated Area
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', fn () => app(DashboardController::class)->index())
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin-prefixed resources
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users',        UserController::class)->middleware('can:view users');
        Route::resource('roles',        RoleController::class)->middleware('can:view roles');
        Route::resource('permissions',  PermissionController::class)->middleware('can:view permissions');
    });

    /*
    |--------------------------------------------------------------------------
    | App resources
    |--------------------------------------------------------------------------
    */
    Route::resource('classes',   SchoolClassController::class);
    Route::resource('courses',   CourseController::class);

    // Prevent "students/dashboard" conflict with admin resource
    Route::get('students/dashboard', function () {
        return redirect()->route('student.homeworks');
    });

    Route::resource('students', StudentController::class)
        ->whereNumber('student'); // only numeric IDs

    Route::get('students/{student}/bform/download', [StudentController::class, 'downloadBForm'])
        ->name('students.bform.download');

    Route::resource('homeworks', HomeworkController::class);
    Route::get('homeworks/{homework}/download', [HomeworkController::class, 'download'])
        ->name('homeworks.download');

    Route::resource('exams', ExamController::class);
    Route::get('exams/{exam}/download', [ExamController::class, 'download'])
        ->name('exams.download');
});

/*
|--------------------------------------------------------------------------
| Convenience redirect
|--------------------------------------------------------------------------
*/
Route::redirect('/home', '/admin/dashboard')->name('home');

/*
|--------------------------------------------------------------------------
| Student area (separate namespace)
|--------------------------------------------------------------------------
*/
// routes/web.php
Route::middleware(['auth'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/homeworks', [\App\Http\Controllers\Student\DashboardController::class, 'homeworks'])->name('homeworks');
        Route::get('/exams',     [\App\Http\Controllers\Student\DashboardController::class, 'exams'])->name('exams');

        // ✅ Monthly reports page (student-only view by Reg #)
        Route::get('/monthly-reports', [\App\Http\Controllers\Student\DashboardController::class, 'monthlyReports'])
            ->name('monthlyreports');
    });



    Route::resource('monthlyreports', App\Http\Controllers\Admin\MonthlyReportController::class);

