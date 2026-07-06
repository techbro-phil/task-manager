<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This protects against security vulnerabilities by strictly defining 
     * which database columns a user can fill or modify.
     */
    protected $fillable = [
        'title',
        'is_completed',
    ];
}
