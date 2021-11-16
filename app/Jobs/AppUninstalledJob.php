<?php

namespace App\Jobs;

use App\Models\User;
use Osiset\ShopifyApp\Contracts\Commands\Shop;
use Osiset\ShopifyApp\Contracts\Queries\Shop as QueriesShop;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan;

class AppUninstalledJob extends \Osiset\ShopifyApp\Messaging\Jobs\AppUninstalledJob
{
    public function handle(Shop $shopCommand, QueriesShop $shopQuery, CancelCurrentPlan $cancelCurrentPlanAction): bool
    {
        info($this->domain . ' 卸载了');
        $user = User::where('name', $this->domain)->first();
        parent::handle($shopCommand, $shopQuery, $cancelCurrentPlanAction);
        return true;
    }
}
