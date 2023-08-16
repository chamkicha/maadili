<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family_member extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'secure_token',
        'user_id',
        'family_member_type_id',
        'sex_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'occupation',
        'phone_no',
        'taasisi_id',
        'tarehe_ya_kuajiriwa',
        'taasisi_other',
        'nida',
        'tin_number'
    ];

    public function member_type(): BelongsTo
    {

        return $this->belongsTo(Family_member_type::class,'family_member_type_id','id');
    }

    public function genderName()
    {

        return $this->belongsTo(Sex::class,'sex_id','id');
    }



    public function taasisi()
    {

        return $this->belongsTo(Office::class,'taasisi_id','id');
    }
}
