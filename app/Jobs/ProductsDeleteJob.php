<?php

namespace App\Jobs;

use App\Models\UpsellRock;
use stdClass;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\UpsellRockProduct;
use App\Models\UpsellRockVariant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class ProductsDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var ShopDomain|string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string   $shopDomain The shop's myshopify domain.
     * @param stdClass $data       The webhook data (JSON decoded).
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Convert domain
        $this->shopDomain = ShopDomain::fromNative($this->shopDomain);

        // Do what you wish with the data
        // Access domain name as $this->shopDomain->toNative()
        $user = User::where('name', $this->shopDomain->toNative())->first();
        $product = json_decode(json_encode($this->data), true);
        $product_id = $product['id'];
        $upsellRockProduct = UpsellRockProduct::where('user_id', $user->id)->where('shopify_product_id', $product_id)->first();
        $variantIds = explode(',', $upsellRockProduct->shopify_product_variants);
        // delete UpsellRock
        UpsellRock::where('shopify_product_id', $product_id)->delete();
        UpsellRock::whereIn('shopify_product_variant_id', $variantIds)->delete();
        if (count($variantIds) > 0) {
            UpsellRock::where(function ($query) use ($variantIds) {
                $query->whereJsonContains('shopify_product_variants', $variantIds[0]);
                for ($i = 1; $i < count($variantIds); $i++) {
                    $query->whereJsonContains('shopify_product_variants', $variantIds[$i]);
                }
                return $query;
            })->delete();
        }
        // delete UpsellRockProduct
        UpsellRockProduct::where('user_id', $user->id)->where('shopify_product_id', $product_id)->delete();
        // delete UpsellRockVariant
        UpsellRockVariant::where('product_id', $product_id)->delete();
    }
}
