<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rejesta_zawadi extends Model
{
    use HasFactory;
    protected $table='rejesta_zawadi';
    
    public function rejesta_zawadi_taarifa()
    {
        return $this->hasMany(rejesta_zawadi_taarifa::class, 'rejesta_id', 'id');
    }
}
