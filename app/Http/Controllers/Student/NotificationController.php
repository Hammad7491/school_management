<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    /**
     * List all published notifications for the student.
     * Marks the current page's records as read (idempotent).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base = published, effective (<= now), honor is_active when present
        $base = Notification::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if (Schema::hasColumn('notifications', 'is_active')) {
            $base->where('is_active', 1);
        }

        $notifications = (clone $base)
            ->latest('published_at')
            ->paginate(15);

        // Mark currently visible page as read
        $ids = $notifications->pluck('id');

        if ($ids->isNotEmpty()) {
            $already = NotificationRead::where('user_id', $user->id)
                ->whereIn('notification_id', $ids)
                ->pluck('notification_id');

            $toInsert = $ids->diff($already)->map(fn ($id) => [
                'user_id'         => $user->id,
                'notification_id' => $id,
                'read_at'         => now(),
            ])->values()->all();

            if (!empty($toInsert)) {
                NotificationRead::insert($toInsert);
            }
        }

        return view('students.notifications.index', compact('notifications'));
    }

    /**
     * AJAX: when the student opens the header dropdown, mark the latest 5
     * as read and return how many are STILL unread overall.
     */
    public function markLatestRead(Request $request)
    {
        $user = Auth::user();

        // Base = published, effective (<= now), honor is_active when present
        $base = Notification::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if (Schema::hasColumn('notifications', 'is_active')) {
            $base->where('is_active', 1);
        }

        // IDs shown in the dropdown (latest 5)
        $topFiveIds = (clone $base)->latest('published_at')->take(5)->pluck('id');

        if ($topFiveIds->isNotEmpty()) {
            $already = NotificationRead::where('user_id', $user->id)
                ->whereIn('notification_id', $topFiveIds)
                ->pluck('notification_id');

            $toInsert = $topFiveIds->diff($already)->map(fn ($id) => [
                'user_id'         => $user->id,
                'notification_id' => $id,
                'read_at'         => now(),
            ])->values()->all();

            if (!empty($toInsert)) {
                NotificationRead::insert($toInsert);
            }
        }

        // Compute remaining unread notifications (used to update the badge)
        $remaining = (clone $base)
            ->whereNotExists(function ($q) use ($user) {
                $q->select(\DB::raw(1))
                  ->from('notification_reads as nr')
                  ->whereColumn('nr.notification_id', 'notifications.id')
                  ->where('nr.user_id', $user->id);
            })
            ->count();

        return response()->json(['remaining' => $remaining]);
    }
}
