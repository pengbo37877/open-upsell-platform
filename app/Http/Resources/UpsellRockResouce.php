<?php

namespace App\Http\Resources;

use App\Models\UpsellRockTrack;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UpsellRockResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // current upsell product has track record?
        return [
            'id' => $this->id,
            'type' => $this->type,
            'product' => $this->shopify_product_id,
            'handle' => $this->shopify_product_handle,
            'variant' => $this->when(!empty($this->shopify_product_variant_id), $this->shopify_product_variant_id),
            'variants' => $this->when($this->shopify_product_variants != '[]', json_decode($this->shopify_product_variants)),
            'order' => $this->order,
            'triggered_on' => $this->triggered_on,
            'short_description' => $this->short_description,
            'show_note_field' => (bool)$this->show_note_field,
            'hide_upsell_product_already_in_cart' => (bool)$this->hide_upsell_product_already_in_cart,
            'match_parent_quantity' => (bool)$this->match_parent_quantity,
            'enable_quantity_selector' => (bool)$this->enable_quantity_selector,
            'price_type' => $this->price_type,
            'minimum_price' => $this->minimum_price,
            'maximum_price' => $this->maximum_price,
            'apply_discount' => (bool)$this->apply_discount,
            'discount_code' => $this->discount_code,
            'amount_type' => $this->amount_type,
            'amount' => $this->amount,
            'remove_upsell_when_parent_product_is_removed' => (bool)$this->remove_upsell_when_parent_product_is_removed,
            'remove_parent_product_when_upsell_product_is_added' => (bool)$this->remove_parent_product_when_upsell_product_is_added,
            'recommended_product_count' => $this->when($this->type == 'smart-auto', $this->recommended_product_count),
            'icon' => $this->when($this->type == 'custom-service', $this->icon),
            'headline' => $this->when($this->type == 'custom-service', $this->headline),
            'product_name' => $this->when($this->type == 'custom-service', $this->product_name),
            'description' => $this->when($this->type == 'custom-service', $this->description),
            'price' => $this->when($this->type == 'custom-service', $this->price),
            'is_upgrade' => (bool)$this->remove_parent_product_when_upsell_product_is_added
        ];
    }
}
