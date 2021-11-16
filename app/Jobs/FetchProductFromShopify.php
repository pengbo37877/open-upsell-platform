<?php

namespace App\Jobs;

use App\Models\UpsellRockProduct;
use App\Models\UpsellRockVariant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchProductFromShopify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $since_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $since_id = 0)
    {
        $this->user = $user;
        $this->since_id = $since_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->since_id === 0) {
            info('Fetch Started shop=' . $this->user->name);
        }
        info('FetchProductFromShopify since_id=' . $this->since_id);
        if ($this->since_id == 0) {
            $products = $this->user->api()->rest('GET', '/admin/products.json')['body']['products'];
        } else {
            $products = $this->user->api()->rest('GET', '/admin/products.json', [
                'since_id' => $this->since_id
            ])['body']['products'];
        }

        if (gettype($products) === 'string') {
            info('Fetch Finished! shop=' . $this->user->name);
            return;
        }

        $next_id = 0;
        foreach ($products as $product) {
            if ($next_id == 0) {
                $next_id = $product['id'];
            } else if ($next_id < $product['id']) {
                $next_id = $product['id'];
            }
            $variants = $product['variants'];
            $ids = [];
            foreach ($variants as $v) {
                array_push($ids, $v->id);
            }
            $upsell_product = UpsellRockProduct::updateOrCreate([
                'user_id' => $this->user->id,
                'shopify_product_id' => $product['id'],
            ], [
                'shopify_collection_id' => isset($product['collection_id']) ? $product['collection_id'] : 0,
                'shopify_product_variants' => implode(",", $ids),
                'title' => $product['title'],
                'type' => $product['product_type'],
                'handle' => $product['handle'],
                'image' => empty($product['image']) ? null : $product['image']['src'],
                'shopify_response' => $product
            ]);
            $upsell_product->increment('times');

            // update or create variant
            foreach ($variants as $variant) {
                $upsell_variant = UpsellRockVariant::updateOrCreate([
                    'product_id' => $variant['product_id'],
                    'variant_id' => $variant['id'],

                ], [
                    'price' => $variant['price'] * 100,
                    'shopify_response' => $variant
                ]);
                $upsell_variant->increment('times');
            }
        }
        if (count($products) >= 50) {
            FetchProductFromShopify::dispatch($this->user, $next_id);
        } else {
            info('Fetch all Finished! shop=' . $this->user->name);
        }
    }
}
