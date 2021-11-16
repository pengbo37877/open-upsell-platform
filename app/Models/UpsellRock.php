<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpsellRock extends Model
{
    use HasFactory;

    protected $guarded = false;

    public const TYPE_PRODUCT = "product";
    public const TYPE_SMART_AUTO = "smart-auto";
    public const TYPE_GIFT_PACKAGING = "gift-packaging";
    public const TYPE_CUSTOM_GIFT_NOTE = "custom-gift-note";
    public const TYPE_EXTENDED_WARRANTY = "extended-warranty";
    public const TYPE_PREMIUM_EMAIL_SUPPORT = "premium-email-support";
    public const TYPE_MYSTERY_PRODUCT = "mystery-product";
    public const TYPE_FIRST_IN_LINE = "first-in-line";
    public const TYPE_INCOGNITO_SHIPPING = "incognito-shipping";
    public const TYPE_CUSTOM_SERVICE = "custom-service";

    // display for type
    public const DISPLAY_FOR_TYPE_ALL = "all-products";
    public const DISPLAY_FOR_TYPE_SPECIFIC_PRODUCTS = "specific-products";
    public const DISPLAY_FOR_TYPE_SPECIFIC_COLLECTIONS = "specific-collections";

    protected $casts = [
        'active' => 'boolean',
        'enable_quantity_selector' => 'boolean',
        'hide_upsell_product_already_in_cart' => 'boolean',
        'is_upgrade' => 'boolean',
        'remove_upsell_product_when_parent_product_is_removed' => 'boolean',
        'show_note_field' => 'boolean',
        'use_recommended_products' => 'boolean',
        'remove_parent_product_when_upsell_product_is_added' => 'boolean',
        'apply_discount' => 'boolean',
        'match_parent_quantity' => 'boolean'
    ];

    public function conditions()
    {
        return $this->hasMany(UpsellRockDisplayCondition::class);
    }

    public function product()
    {
        return $this->belongsTo(UpsellRockProduct::class, 'shopify_product_id', 'shopify_product_id');
    }
}
