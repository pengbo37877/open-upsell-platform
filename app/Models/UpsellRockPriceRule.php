<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpsellRockPriceRule extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $casts = [
        'shopify_response' => 'json'
    ];
}