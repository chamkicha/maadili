<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User_declaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_id',
        'declaration_type_id',
        'adf_number',
        'financial_year_id',
        'flag',
        'is_confirmed'
    ];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function declaration_type(): BelongsTo
    {

        return $this->belongsTo(Declaration_type::class,'declaration_type_id','id');
    }

    public function downloads(): HasMany
    {

        return $this->hasMany(Declaration_download::class,'user_declaration_id','id');
    }

}
