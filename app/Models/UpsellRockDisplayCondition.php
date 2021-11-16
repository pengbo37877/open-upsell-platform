<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpsellRockDisplayCondition extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function upsell()
    {
        return $this->belongsTo(UpsellRock::class, 'upsell_rock_id');
    }
}
