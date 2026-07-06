<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Add user_id to our protected mass assignable list
        'title',
        'is_completed',
    ];

    /**
     * A task belongs to a single specific User account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
