<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'family_member_id',
        'debt_type_id',
        'institute',
        'amount'
    ];

    public function debt_type(): BelongsTo
    {

        return $this->belongsTo(Debt_type::class,'debt_type_id','id');
    }

    public function member(): BelongsTo
    {

        return $this->belongsTo(Family_member::class,'family_member_id','id');
    }
}
