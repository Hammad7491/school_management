<?php

namespace App\Models;
use App\Models\NotificationRead; // add this
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active'    => 'boolean',
    ];

    /**
     * Auto-fill created_by when creating.
     */
    protected static function booted(): void
    {
        static::creating(function (self $n): void {
            if (empty($n->created_by) && Auth::check()) {
                $n->created_by = Auth::id();
            }
        });
    }

    /**
     * Author (admin) who created the notification.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Reads (per-user read receipts).
     */
    public function reads(): HasMany
    {
        return $this->hasMany(NotificationRead::class, 'notification_id');
    }

    /**
     * Scope: published & active notifications only.
     */
    public function scopePublished($q)
    {
        return $q->where('is_active', true)
                 ->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }
}
