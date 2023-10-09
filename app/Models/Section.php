<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    public function requirements(): HasMany
    {

        return $this->hasMany(Section_requirement::class);
    }


    public function declarationSections()
    {

        return $this->BelongsTo(Declaration_section::class,'id','section_id');
    }


    
}
