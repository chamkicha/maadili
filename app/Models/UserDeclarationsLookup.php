<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeclarationsLookup extends Model
{
    use HasFactory;
    protected $table='user_declarations_lookup';

    protected $fillable = [
        'pl_id',
        'family_member_id',
        'status_id',
        'user_declaration_id',
        'declaration_section_count',
        'declaration_section_completed'
    ];
}
