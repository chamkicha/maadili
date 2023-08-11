<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_lookup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stage_one',
        'stage_two',
        'stage_three',
        'user_declaration_id',
    ];
}
