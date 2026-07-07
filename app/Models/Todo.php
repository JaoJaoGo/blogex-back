<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_DONE = 'done';
    
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';
    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'status',
        'starts_at',
        'due_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_IN_PROGRESS,
            self::STATUS_DONE,
        ];
    }
    
    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
            self::PRIORITY_URGENT,
        ];
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(TodoChecklist::class)->orderBy('position')->orderBy('id');
    }
}
