<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class integrity_pledge extends Model
{
    
    protected $table='integrity_pledge';
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_id',
        'date_of_appointment',
        'title_id',
        'current_stage',
        'approval_status'
    ];
}
