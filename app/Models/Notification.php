<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {

        return $this->belongsTo(Notification_category::class,'notification_category_id','id');
    }
}
