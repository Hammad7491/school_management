<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    /**
     * List notifications (latest first, with pagination).
     */
    public function index()
    {
        $notifications = Notification::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Store a new notification (draft or published).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'publish_now'  => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $n = new Notification();
        $n->title      = $data['title'];
        $n->body       = $data['body'];
        $n->created_by = Auth::id();

        // Decide publish time
        if (!empty($data['publish_now'])) {
            $n->published_at = now();
        } elseif (!empty($data['published_at'])) {
            $n->published_at = $data['published_at'];
        }

        // If schema has is_active, set it consistently
        if (Schema::hasColumn('notifications', 'is_active')) {
            $n->is_active = $n->published_at ? 1 : 0;
        }

        $n->save();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', $n->published_at ? 'Notification saved & published.' : 'Notification saved as draft.');
    }

    /**
     * Edit form.
     */
    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', compact('notification'));
    }

    /**
     * Update a notification (you can re-publish from here too).
     */
    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'publish_now'  => 'sometimes|boolean',
            'published_at' => 'nullable|date',
        ]);

        $notification->title = $data['title'];
        $notification->body  = $data['body'];

        if (!empty($data['publish_now'])) {
            $notification->published_at = now();
        } elseif (!empty($data['published_at'])) {
            $notification->published_at = $data['published_at'];
        }

        if (Schema::hasColumn('notifications', 'is_active')) {
            $notification->is_active = $notification->published_at ? 1 : 0;
        }

        $notification->save();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notification updated.');
    }

    /**
     * Publish a draft notification.
     */
    public function publish(Notification $notification)
    {
        if (!$notification->published_at) {
            $notification->published_at = now();
        }

        if (Schema::hasColumn('notifications', 'is_active')) {
            $notification->is_active = 1;
        }

        $notification->save();

        return back()->with('success', 'Notification published.');
    }

    /**
     * Optional: toggle active flag (only if column exists).
     */
    public function toggle(Notification $notification)
    {
        if (!Schema::hasColumn('notifications', 'is_active')) {
            return back()->with('success', 'Toggle ignored (is_active column not present).');
        }

        $notification->is_active = !$notification->is_active;
        $notification->save();

        return back()->with('success', 'Notification status updated.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
