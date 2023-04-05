<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employment_information extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'user_declaration_id',
        'title_id',
        'office_id',
        'employment_type_id',
        'salary_per_year',
        'allowance_per_year',
        'income_from_other_source_per_year',
        'from',
        'to',
        'is_current'
    ];

    public function title(): BelongsTo
    {

        return $this->belongsTo(Title::class,'title_id','id');
    }

    public function office(): BelongsTo
    {

        return $this->belongsTo(Office::class,'office_id','id');
    }

    public function employment_type(): BelongsTo
    {

        return $this->belongsTo(Employment_type::class,'employment_type_id','id');
    }
}
