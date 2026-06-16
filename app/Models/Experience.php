<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Experience
 * 
 * Model responsável por representar uma experiência profissional vinculada a um usuário.
 * 
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $workplace
 * @property string $start_date
 * @property bool $is_current
 * @property string|null $end_date
 * @property string|null $description
 * 
 * @package App\Models
 */
class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'workplace',
        'start_date',
        'is_current',
        'end_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
