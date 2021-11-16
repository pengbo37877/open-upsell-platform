<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpsellRockOrder extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $casts = [
        'shopify_response' => 'json'
    ];
}
