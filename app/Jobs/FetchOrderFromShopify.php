<?php

namespace App\Jobs;

use App\Models\UpsellRockOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchOrderFromShopify implements ShouldQueue
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
            info('Fetch Order Started shop=' . $this->user->name);
        }
        info('FetchOrderFromShopify since_id=' . $this->since_id);
        if ($this->since_id == 0) {
            $orders = $this->user->api()->rest('GET', '/admin/orders.json', [
                'status' => 'any'
            ])['body']['orders'];
        } else {
            $orders = $this->user->api()->rest('GET', '/admin/products.json', [
                'status' => 'any',
                'since_id' => $this->since_id
            ])['body']['orders'];
        }

        if (gettype($orders) === 'string') {
            info('Fetch Order Finished! shop=' . $this->user->name);
            return;
        }

        $next_id = 0;
        foreach ($orders as $order) {
            if ($next_id == 0) {
                $next_id = $order['id'];
            } else if ($next_id < $order['id']) {
                $next_id = $order['id'];
            }

            // cart_token
            $cart_token = "";
            foreach ($order['note_attributes'] as $attr) {
                if ($attr['name'] == 'antrack_token') {
                    $cart_token = $attr['value'];
                }
            }

            $upsellRockOrder = UpsellRockOrder::updateOrCreate([
                'user_id' => $this->user->id,
                'shopify_id' => $order['id'],
            ], [
                'currency' => $order['currency'],
                'total_price' => $order['total_price'],
                'cart_token' => $cart_token,
                'shopify_response' => $order
            ]);

            $upsellRockOrder->increment('times');
        }
        if (count($orders) >= 50) {
            FetchOrderFromShopify::dispatch($this->user, $next_id);
        } else {
            info('Fetch Order all Finished! shop=' . $this->user->name);
        }
    }
}
