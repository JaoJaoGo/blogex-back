<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Skill
 * 
 * Model responsável por representar uma habilidade vinculada a um usuário.
 * 
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $level
 * 
 * @package App\Models
 */
class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
