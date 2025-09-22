<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Notification;

use App\Http\Controllers\AdmissionController;
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
use App\Http\Controllers\Admin\MonthlyReportController;
use App\Http\Controllers\Student\NotificationController;

use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\VacationRequestController as AdminVacationRequestController;
use App\Http\Controllers\Student\VacationRequestController as StudentVacationRequestController;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'main.home.home');

Route::get('/login',     [AuthController::class, 'loginform'])->name('loginform');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::get('/register',  [AuthController::class, 'registerform'])->name('registerform');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Error pages
Route::view('/error', 'auth.errors.error403')->name('auth.errors.error403');

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
    | Admin Resources
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users',        UserController::class)->middleware('can:view users');
        Route::resource('roles',        RoleController::class)->middleware('can:view roles');
        Route::resource('permissions',  PermissionController::class)->middleware('can:view permissions');
    });

    Route::resource('classes',   SchoolClassController::class);
    Route::resource('courses',   CourseController::class);

    Route::resource('students', StudentController::class);
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
| Redirects
|--------------------------------------------------------------------------
*/
Route::redirect('/home', '/admin/dashboard')->name('home');

/*
|--------------------------------------------------------------------------
| Student Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard',       [StudentDashboard::class, 'index'])->name('dashboard');
        Route::get('/homeworks',       [StudentDashboard::class, 'homeworks'])->name('homeworks');
        Route::get('/exams',           [StudentDashboard::class, 'exams'])->name('exams');
        Route::get('/monthly-reports', [StudentDashboard::class, 'monthlyReports'])->name('monthlyreports');

        // Vacation Requests
        Route::get('vacation-requests',     [StudentVacationRequestController::class, 'index'])->name('vacation-requests.index');
        Route::get('vacation-requests/new', [StudentVacationRequestController::class, 'create'])->name('vacation-requests.create');
        Route::post('vacation-requests',    [StudentVacationRequestController::class, 'store'])->name('vacation-requests.store');

        // Student Results
        Route::get('/results', [StudentResultController::class, 'index'])->name('results');

        // Student Notifications (see all)
        Route::get('notifications', function () {
            $notifications = \App\Models\Notification::where('is_active', true)
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->paginate(20);
            return view('students.notifications.index', compact('notifications'));
        })->name('notifications.index');

        // Default student redirect
        Route::redirect('/', '/student/dashboard');
    });

/*
|--------------------------------------------------------------------------
| Monthly Reports (Admin)
|--------------------------------------------------------------------------
*/
Route::resource('monthlyreports', MonthlyReportController::class);
Route::get('monthlyreports/{monthlyreport}/download', [MonthlyReportController::class, 'download'])
    ->name('monthlyreports.download');

/*
|--------------------------------------------------------------------------
| Results
|--------------------------------------------------------------------------
*/
// Admin Results
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('results',           [AdminResultController::class, 'index'])
        ->name('results.index')
        ->middleware('can:view results');

    Route::post('results/upload',   [AdminResultController::class, 'upload'])
        ->name('results.upload')
        ->middleware('can:upload results');
});

/*
|--------------------------------------------------------------------------
| Vacation Requests (Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        Route::get('vacations', [AdminVacationRequestController::class, 'index'])
            ->name('vacations.index');

        Route::post('vacations/{id}/{status}', [AdminVacationRequestController::class, 'updateStatus'])
            ->whereIn('status', ['approved','rejected'])
            ->name('vacations.updateStatus');

        Route::resource('vacationrequests', AdminVacationRequestController::class)
            ->only(['index','show','update','destroy']);
    });

/*
|--------------------------------------------------------------------------
| Notifications (Admin)
|--------------------------------------------------------------------------
*/
// routes/web.php
Route::prefix('admin')
    ->middleware(['auth'])        // removed role:Admin
    ->name('admin.')
    ->group(function () {
        Route::resource('notifications', AdminNotificationController::class)
            ->only(['index','create','store','edit','update','destroy']);

        Route::post('notifications/{notification}/toggle',  [AdminNotificationController::class, 'toggle'])
            ->name('notifications.toggle');

        Route::post('notifications/{notification}/publish', [AdminNotificationController::class, 'publish'])
            ->name('notifications.publish');
    });


    Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::post('notifications/mark-latest-read',
        [\App\Http\Controllers\Student\NotificationController::class, 'markLatestRead']
    )->name('notifications.markLatestRead');
});



Route::get('/fee', function () {
    return view('main.fee.index');
})->name('fee');


// Admission Page
Route::get('/admission', function () {
    return view('main.admission.admission');
})->name('admission');


// Admission Page
Route::get('/admission', function () {
    return view('main.admission.admission');
})->name('admission');



// Frontend: store admission
Route::post('/admission/store', [AdmissionController::class, 'store'])->name('admission.store');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
    Route::delete('/admissions/{id}', [AdmissionController::class, 'destroy'])->name('admissions.destroy');
});




// Computer Courses Page
Route::get('/computer-courses', function () {
    return view('main.courses.computer');
})->name('computer.courses');


Route::view('/vision', 'main.vision.vision')->name('vision');




Route::get('/faculty', function () {
    return view('main.faculty.index');
})->name('faculty');