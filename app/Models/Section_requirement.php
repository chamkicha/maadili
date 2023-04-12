<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section_requirement extends Model
{
    use HasFactory;

    public function requirement(): BelongsTo
    {

        return $this->belongsTo(Requirement::class,'requirement_id','id');
    }
}
