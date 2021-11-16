<?php

namespace App\Jobs;

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

class ProductsUpdateJob implements ShouldQueue
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
        $variants = $product['variants'];
        $ids = [];
        foreach ($variants as $v) {
            array_push($ids, $v['id']);
            UpsellRockVariant::updateOrCreate([
                'product_id' => $v['product_id'],
                'variant_id' => $v['id']
            ], [
                'price' => $v['price'] * 100,
                'shopify_response' => $v
            ]);
        }
        UpsellRockProduct::updateOrCreate([
            'user_id' => $user->id,
            'shopify_product_id' => $product['id'],
        ], [
            'shopify_collection_id' => isset($product['collection_id']) ? $product['collection_id'] : 0,
            'shopify_product_variants' => implode(",", $ids),
            'title' => $product['title'],
            'type' => $product['product_type'],
            'status' => $product['status'],
            'handle' => $product['handle'],
            'image' => empty($product['image']) ? null : $product['image']['src'],
            'shopify_response' => $product
        ]);
    }
}
