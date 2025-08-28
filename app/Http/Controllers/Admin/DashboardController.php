<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

// Adjust model names here if yours differ:
use App\Models\Student;
use App\Models\Course;
use App\Models\SchoolClass;     // e.g. change to Classroom if that's your model
use App\Models\VacationRequest; // optional
use App\Models\Notification;    // optional

class DashboardController extends Controller
{
    /**
     * Show the admin/staff dashboard with key counts.
     */
    public function index()
    {
        $uid = Auth::id();

        // --- Totals ---
        $totalStudents = class_exists(Student::class)     ? Student::count()     : 0;
        $totalClasses  = class_exists(SchoolClass::class) ? SchoolClass::count() : 0;
        $totalCourses  = class_exists(Course::class)      ? Course::count()      : 0;

        // --- Students added by the logged-in user (auto-detect creator column) ---
        $addedByYou = null;
        if ($uid && class_exists(Student::class)) {
            if (Schema::hasColumn('students', 'created_by')) {
                $addedByYou = Student::where('created_by', $uid)->count();
            } elseif (Schema::hasColumn('students', 'user_id')) {
                $addedByYou = Student::where('user_id', $uid)->count();
            } elseif (Schema::hasColumn('students', 'created_by_id')) {
                $addedByYou = Student::where('created_by_id', $uid)->count();
            }
        }

        // --- Optional: small “attention” counters (safe if models don’t exist) ---
        $vacationsPending   = class_exists(VacationRequest::class)
                                ? VacationRequest::where('status', 'pending')->count()
                                : 0;

        $notificationsDraft = class_exists(Notification::class)
                                ? Notification::whereNull('published_at')->count()
                                : 0;

        $stats = [
            'students_total'      => $totalStudents,
            'students_by_me'      => $addedByYou,     // null if no creator column exists
            'classes_total'       => $totalClasses,
            'courses_total'       => $totalCourses,
            'vacations_pending'   => $vacationsPending,
            'notifications_draft' => $notificationsDraft,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
