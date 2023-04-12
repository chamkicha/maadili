<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Declaration_type extends Model
{
    use HasFactory;

    public function sections(): HasMany
    {

        return $this->hasMany(Declaration_section::class);
    }


}
