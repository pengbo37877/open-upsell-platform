<?php

namespace App\Jobs;

use App\Models\UpsellRockCollection;
use App\Models\UpsellRockCpv;
use App\Models\UpsellRockProduct;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCpvFromShopify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $collection_id;
    public $since_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $collection_id, $since_id = 0)
    {
        $this->user = $user;
        $this->collection_id = $collection_id;
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
            info('Fetch Cpv Start shop=' . $this->user->name);
        }
        $products = $this->user->api()->rest('GET', "/admin/collections/" . $this->collection_id . "/products.json", [
            'limit' => 250,
            'since_id' => $this->since_id
        ])['body']['products'];
        if (gettype($products) === 'string') {
            info('Fetch Cpv Finished! shop=' . $this->user->name);
            return;
        }

        $next_id = 0;
        foreach ($products as $product) {
            if ($product->id > $next_id) {
                $next_id = $product->id;
            }
            $upsell_product = UpsellRockProduct::where('user_id', $this->user->id)->where('shopify_product_id', $product->id)->first();
            if ($upsell_product) {
                $variants = explode(",", $upsell_product->shopify_product_variants);
                foreach ($variants as $variant) {
                    UpsellRockCpv::updateOrCreate([
                        'user_id' => $this->user->id,
                        'collection_id' => $this->collection_id,
                        'product_id' => $product->id,
                        'variant_id' => $variant,
                    ]);
                }
            }
        }

        if (count($products) == 250) {
            FetchCpvFromShopify::dispatch($this->user, $this->collection_id, $next_id);
        }
    }
}
