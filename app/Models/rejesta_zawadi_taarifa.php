<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rejesta_zawadi_taarifa extends Model
{
    use HasFactory;
    // protected $table='rejesta_zawadi_taarifa';
    protected $fillable = [
        'secure_token',
        'rejesta_id',
        'kiongozi_id',
        'jina_aliyetoa_zawadi',
        'maelezo_zawadi',
        'thamani_zawadi',
        'tar_kupokea_zawadi',
        'mazingira_ilipopokelewa',
        'tar_kutoa_tamko',
        'tar_kukabidhi_zawadi',
        'zawadi_ilivyotumika',
        'taasisi_id'
    ];

    public function taasisi()
    {
        return $this->hasMany(Office::class, 'id', 'taasisi_id');
    }
    
    public function kiongozi_aliyepokea_zawadi()
    {
        return $this->hasMany(User::class, 'id', 'kiongozi_id');
    }
}


