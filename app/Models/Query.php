<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Query extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bidder() : BelongsTo {
        return $this->belongsTo(User::class,'bidder_id');
    }

}
