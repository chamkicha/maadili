<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class integrity_pledge_approval extends Model
{
    protected $table='integrity_pledge_approval';
    use HasFactory;

    protected $fillable = [
        'integrity_pledge_id',
        'staff_id',
        'send_to',
        'approval_status',
        'comment',
        'created_at'
    ];

    public function integrity_pledge()
    {
        return $this->belongsTo(integrity_pledge::class,'integrity_pledge_id','id');
    }


    public function staff()
    {
        return $this->belongsTo(staff::class,'staff_id','id');
    }
}
