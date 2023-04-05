<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'family_member_id',
        'size_of_the_area',
        'value_or_costs_of_construction_or_purchase',
        'source_of_income_id',
        'country_id',
        'region_id',
        'ward_id',
        'type_of_use_id',
        'street'
    ];

    public function property_type(): BelongsTo
    {

        return $this->belongsTo(Property_type::class,'property_type_id','id');
    }

    public function member(): BelongsTo
    {

        return $this->belongsTo(family_member::class,'Family_member_id','id');
    }

    public function country(): BelongsTo
    {

        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function region(): BelongsTo
    {

        return $this->belongsTo(Region::class,'region_id','id');
    }

    public function ward(): BelongsTo
    {

        return $this->belongsTo(Ward::class,'ward_id','id');
    }

    public function revenue_source(): BelongsTo
    {

        return $this->belongsTo(Source_of_income::class,'source_of_income_id','id');
    }

    public function usage(): BelongsTo
    {

        return $this->belongsTo(Type_of_use::class,'type_of_use_id','id');
    }
}
