<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Declaration_section extends Model
{
    use HasFactory;

    public function requirements(): HasMany
    {

        return $this->hasMany(Section_requirement::class);
    }
}
