<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ExamController;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\HomeworkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SchoolClassController;

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

    // Dashboards
    Route::get('/admin/dashboard', function () {
        // you can use a controller if you prefer:
        // return app(DashboardController::class)->index();
        return app(DashboardController::class)->index();
    })->name('admin.dashboard');

    // Student dashboard (students go here after login)
    Route::view('/students/dashboard', 'students.dashboard')->name('students.dashboard');

    /*
    |----------------------------------------------------------------------
    | Admin-prefixed resources (with abilities)
    |----------------------------------------------------------------------
    | Keep these under /admin and gate by your permissions.
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users',        UserController::class)->middleware('can:view users');
        Route::resource('roles',        RoleController::class)->middleware('can:view roles');
        Route::resource('permissions',  PermissionController::class)->middleware('can:view permissions');
    });

    /*
    |----------------------------------------------------------------------
    | App resources (no admin prefix) – matches your sidebar route() names
    |----------------------------------------------------------------------
    */
    Route::resource('classes',   SchoolClassController::class);
    Route::resource('courses',   CourseController::class);

    Route::resource('students',  StudentController::class);
    Route::get('students/{student}/bform/download', [StudentController::class, 'downloadBForm'])
        ->name('students.bform.download');

    Route::resource('homeworks', HomeworkController::class);
    Route::get('homeworks/{homework}/download', [HomeworkController::class, 'download'])
        ->name('homeworks.download');
});

/*
|--------------------------------------------------------------------------
| Convenience redirect (optional)
|--------------------------------------------------------------------------
*/
Route::redirect('/home', '/admin/dashboard')->name('home');
Route::middleware('auth')->group(function () {
    Route::resource('exams', ExamController::class);                 // index/create/store/show(edit)/update/destroy
    Route::get('exams/{exam}/download', [ExamController::class, 'download'])
        ->name('exams.download');                                    // ✅ download route
});