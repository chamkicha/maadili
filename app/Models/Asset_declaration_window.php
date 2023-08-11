<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset_declaration_window extends Model
{
    use HasFactory;

    public function declarations(): BelongsTo
    {

        return $this->belongsTo(Declaration_type::class,'declaration_type_id','id');
    }

}
