<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank_information extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'family_member_id',
        'institute_name',
        'account_number',
        'amount',
        'source_of_income_id',
        'profit',
        'is_local',
        'type_of_use_id'
    ];

    public function member(): BelongsTo
    {

        return $this->belongsTo(Family_member::class,'family_member_id','id');
    }

    public function usage(): BelongsTo
    {

        return $this->belongsTo(Type_of_use::class,'type_of_use_id','id');
    }

    public function source(): BelongsTo
    {

        return $this->belongsTo(Source_of_income::class,'source_of_income_id','id');
    }
}
