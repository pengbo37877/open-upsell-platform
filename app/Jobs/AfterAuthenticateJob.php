<?php

namespace App\Jobs;

use App\Models\UpsellRockSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shop;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        info('鉴权成功：' . $this->shop->name);
        FetchProductFromShopify::dispatch($this->shop);
        FetchOrderFromShopify::dispatch($this->shop);
        FetchCheckoutFromShopify::dispatch($this->shop);
    }
}
