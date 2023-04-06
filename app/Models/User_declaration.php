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
        'flag'
    ];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function declaration_type(): BelongsTo
    {

        return $this->belongsTo(Declaration_type::class,'declaration_type_id','id');
    }

    public function employments(): HasOne
    {

        return $this->hasOne(Employment_information::class,'user_declaration_id','id');
    }

    public function downloads(): HasMany
    {

        return $this->hasMany(Declaration_download::class,'user_declaration_id','id');
    }

    public function cashes(): HasMany
    {

        return $this->hasMany(Cash_in_hand::class,'user_declaration_id','id');
    }

    public function banks(): HasMany
    {

        return $this->hasMany(Bank_information::class,'user_declaration_id','id');
    }

    public function share_and_dividends(): HasMany
    {

        return $this->hasMany(Share_dividend::class,'user_declaration_id','id');
    }

    public function house_and_buildings(): HasMany
    {

        return $this->hasMany(House_and_building::class,'user_declaration_id','id');
    }

    public function properties(): HasMany
    {

        return $this->hasMany(Property::class,'user_declaration_id','id');
    }

    public function transportations(): HasMany
    {

        return $this->hasMany(Transportation::class,'user_declaration_id','id');
    }

    public function debts(): HasMany
    {

        return $this->hasMany(Debt::class,'user_declaration_id','id');
    }
}
