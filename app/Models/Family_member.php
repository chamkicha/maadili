<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family_member extends Model
{
    use HasFactory;

    public function member_type(): BelongsTo
    {

        return $this->belongsTo(Family_member_type::class,'family_member_type_id','id');
    }
}
