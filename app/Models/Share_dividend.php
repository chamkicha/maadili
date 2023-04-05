<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share_dividend extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'family_member_id',
        'amount_of_stock',
        'institute_name',
        'country_id',
        'region_id',
        'district_id',
        'amount_of_dividend'
    ];

    public function country(): BelongsTo
    {

        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function region(): BelongsTo
    {

        return $this->belongsTo(Region::class,'region_id','id');
    }

    public function district(): BelongsTo
    {

        return $this->belongsTo(District::class,'district_id','id');
    }

    public function member(): BelongsTo
    {

        return $this->belongsTo(Family_member::class,'family_member_id','id');
    }
}
