<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Es_contact extends Model
{
    use HasFactory;
 protected $fillable = [
        'id',
        'secure_token',
        'zone_id',
        'postal_address',
        'physical_address',
        'phone_number',
        'email',
        'created_by',
       
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class,'zone_id','id');
    }
}
