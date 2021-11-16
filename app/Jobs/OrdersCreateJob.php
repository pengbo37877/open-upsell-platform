<?php

namespace App\Jobs;

use stdClass;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\UpsellRockOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class OrdersCreateJob implements ShouldQueue
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
        $order = json_decode(json_encode($this->data), true);

        // cart_token
        $cart_token = "";
        foreach ($order['note_attributes'] as $attr) {
            if ($attr['name'] == 'antrack_token') {
                $cart_token = $attr['value'];
            }
        }

        UpsellRockOrder::updateOrCreate([
            'user_id' => $user->id,
            'shopify_id' => $order['id'],
        ], [
            'currency' => $order['currency'],
            'total_price' => $order['total_price'],
            'cart_token' => $cart_token,
            'shopify_response' => $order
        ]);
    }
}
