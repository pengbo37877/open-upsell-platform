<?php

namespace App\Console\Commands;

use App\Jobs\FetchProductFromShopify;
use App\Models\User;
use Illuminate\Console\Command;

class FetchProductFromShopifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:products {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user products from shopify';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->arguments()['user_id'];
        $user = User::find($user_id);
        if (!$user) {
            info('User ' . $user_id . ' is not found.');
        }
        FetchProductFromShopify::dispatch($user);
        return 0;
    }
}
