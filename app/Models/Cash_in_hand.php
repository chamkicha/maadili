<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cash_in_hand extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'family_member_id',
        'cash'
    ];

    public function member(): BelongsTo
    {

        return $this->belongsTo(Family_member::class,'family_member_id','id');
    }
}
