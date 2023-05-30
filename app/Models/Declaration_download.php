<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Declaration_download extends Model
{
    use HasFactory;

    protected $fillable = [
        'secure_token',
        'downloader_secure_token',
        'user_declaration_id',
        'password'
    ];

    public function user_declaration(): BelongsTo
    {

        return $this->belongsTo(User_declaration::class,'user_declaration_id','id');
    }

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class,'downloader_secure_token','secure_token');
    }
}
