<?php

namespace App\Jobs;

use App\Models\UpsellRockCheckout;
use App\Models\UpsellRockOrder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCheckoutFromShopify implements ShouldQueue
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
            info('Fetch Checkout Started shop=' . $this->user->name);
        }
        info('FetchCheckoutFromShopify since_id=' . $this->since_id);
        if ($this->since_id == 0) {
            $checkouts = $this->user->api()->rest('GET', '/admin/checkouts.json', [
                'status' => 'any'
            ])['body']['checkouts'];
        } else {
            $checkouts = $this->user->api()->rest('GET', '/admin/checkouts.json', [
                'status' => 'any',
                'since_id' => $this->since_id
            ])['body']['checkouts'];
        }

        if (gettype($checkouts) === 'string') {
            info('Fetch Checkout Finished! shop=' . $this->user->name);
            return;
        }

        $next_id = 0;
        foreach ($checkouts as $checkout) {
            if ($next_id == 0) {
                $next_id = $checkout['id'];
            } else if ($next_id < $checkout['id']) {
                $next_id = $checkout['id'];
            }

            $upsellRockCheckout = UpsellRockCheckout::updateOrCreate([
                'user_id' => $this->user->id,
                'shopify_id' => $checkout['id'],
            ], [
                'cart_token' => $checkout['cart_token'],
                'currency' => $checkout['currency'],
                'total_price' => $checkout['total_price'],
                'shopify_response' => $checkout
            ]);

            $upsellRockCheckout->increment('times');
        }
        if (count($checkouts) >= 50) {
            FetchCheckoutFromShopify::dispatch($this->user, $next_id);
        } else {
            info('Fetch Checkout all Finished! shop=' . $this->user->name);
        }
    }
}
