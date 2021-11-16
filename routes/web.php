<?php

use App\Models\User;
use App\Models\Currency;
use App\Models\UpsellRock;
use Illuminate\Http\Request;
use App\Models\ShopifyCurrency;
use App\Models\UpsellRockSetting;
use App\Jobs\AfterAuthenticateJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;
use Osiset\ShopifyApp\Services\ChargeHelper;
use App\Http\Controllers\UpsellRockController;

// App routes
Route::get('/', function () {
    $user = Auth::user();
    $shopApi = $user->api()->rest('GET', '/admin/shop.json')['body']['shop'];

    $currency = Currency::where('currency', $shopApi->currency)->first();
    $money_symbol = "";
    if ($currency) {
        $money_symbol = $currency->currency_symbol;
    }
    $user->update([
        'contact_email' => $shopApi->email,
        'country' => $shopApi->country,
        'country_code' => $shopApi->country_code,
        'country_name' => $shopApi->country_name,
        'currency' => $shopApi->currency,
        'enabled_presentment_currencies' => json_encode($shopApi->enabled_presentment_currencies),
        'money_format' => $shopApi->money_format,
        'money_with_currency_format' => $shopApi->money_with_currency_format,
        'money_in_emails_format' => $shopApi->money_in_emails_format,
        'money_with_currency_in_emails_format' => $shopApi->money_with_currency_in_emails_format,
        'money_format_symbol' => $money_symbol,
        'iana_timezone' => $shopApi->iana_timezone,
        'shopify_plan_name' => $shopApi->plan_name,
        'shopify_response' => $shopApi,
        'shop_id' => $shopApi->id
    ]);
    $setting = UpsellRockSetting::where('user_id', $user->id)->first();
    if (!$setting) {
        UpsellRockSetting::create([
            'user_id' => $user->id,
            'title' => 'Get extras for your product',
            'add_to_cart' => 'Add',
            'added_to_cart' => 'Added',
            'upgrade' => "Upgrade",
            "upgraded" => "Upgraded",
            "proceed_to_cart" => "Continue",
            "back" => "Back",
            "cart_discount_note" => "",
            "primary_color" => "#333333",
            "continue_action" => "",
            "close_action" => "",
            'max_popup_session_views' => 0
        ]);
    }
    AfterAuthenticateJob::dispatch($user)->delay(now()->addSeconds(1));
    // if ($shopApi->plan_name == 'partner_test' && strpos($user->name, 'pb2021') != 0) {
    //     return view('fuckyou');
    // }
    return view('spa');
})->middleware(['verify.shopify'])->name('home');

Route::get('/spa/user', [SpaController::class, "user"])->middleware(['verify.shopify']);
Route::get('/spa/shop', [SpaController::class, "shop"])->middleware(['verify.shopify']);
Route::get('/spa/currencies', [SpaController::class, "currencies"])->middleware(['verify.shopify']);
Route::get('/spa/upsells', [SpaController::class, "upsells"])->middleware(['verify.shopify']);
Route::get('/spa/products', [SpaController::class, "products"])->middleware(['verify.shopify']);
Route::get('/spa/local_products', [SpaController::class, "local_products"])->middleware(['verify.shopify']);
Route::get('/spa/product/{shopify_id}', [SpaController::class, "product"])->middleware(['verify.shopify']);
Route::get('/spa/custom_collections', [SpaController::class, "custom_collections"])->middleware(['verify.shopify']);
Route::get('/spa/smart_collections', [SpaController::class, "smart_collections"])->middleware(['verify.shopify']);
Route::get('/spa/local_collections', [SpaController::class, "local_collections"])->middleware(['verify.shopify']);
Route::get('/spa/update_smart_collection', [SpaController::class, "update_smart_collection"])->middleware(['verify.shopify']);
Route::get('/spa/smart_auto_upsell', [SpaController::class, "smart_auto_upsell"])->middleware(['verify.shopify']);
Route::get('/spa/create_upsell', [SpaController::class, "create_upsell"])->middleware(['verify.shopify']);
Route::put('/spa/disable_upsell', [SpaController::class, "disable_upsell"])->middleware(['verify.shopify']);
Route::put('/spa/enable_upsell', [SpaController::class, "enable_upsell"])->middleware(['verify.shopify']);
Route::get('/spa/upsell/{id}', [SpaController::class, "upsell"])->middleware(['verify.shopify']);
Route::put('/spa/upsell/{id}', [SpaController::class, "updateUpsell"])->middleware(['verify.shopify']);

Route::get('/spa/setting', [SpaController::class, "setting"])->middleware(['verify.shopify']);
Route::put('/spa/setting', [SpaController::class, "updateSetting"])->middleware(['verify.shopify']);


Route::get('/spa/views', [SpaController::class, "views"])->middleware(['verify.shopify']);
Route::get('/spa/sessions', [SpaController::class, "sessions"])->middleware(['verify.shopify']);
Route::get('/spa/statistics', [SpaController::class, "statistics"])->middleware(['verify.shopify']);
Route::get('/spa/upsell_with_tracks', [SpaController::class, "upsellWithTracks"])->middleware(['verify.shopify']);


// storefront routes
Route::get('/script/{name}', function (Request $request, $name) {
    $shop = $request->shop;
    $user = User::where('name', $shop)->first();

    if (!$user) {
        return '';
    }

    $count = UpsellRock::where('user_id', $user->id)->where('active', 1)->count();
    if ($count == 0) {
        return "";
    }

    $script = Cache::remember('ant-rack-script-' . explode('.', $name)[0], 60 * 60, function () use ($name) {
        return file_get_contents(asset('js/' . $name));
    });


    $upsellRockBaseUrl = env('APP_URL');

    $setting = UpsellRockSetting::where('user_id', $user->id)->first();

    $currencies = Cache::remember('ant-rack-currencies', 60 * 60, function () {
        return Currency::all();
    });

    $shopifyCurrency = Cache::remember('ant-rack-shopify-currency', 60 * 60, function () {
        return ShopifyCurrency::orderByDesc('id')->first()->body;
    });


    $shopCurrency = $user->currency;

    $shopId = $user->shop_id;

    $antRackCssUrl = asset('css/ant-rack.css');
    $antRackJsUrl = asset('js/ant-rack-app.js');

    return $shopifyCurrency . "\r\nvar currencies=" . $currencies . ";\r\n" .
        "var shopCurrency='" . $shopCurrency . "';\r\n" .
        "var antRackCssUrl='" . $antRackCssUrl . "';\r\n" .
        "var antRackJsUrl='" . $antRackJsUrl . "';\r\n" .
        "var shopId='" . $shopId . "';\r\n" .
        "var upsellRockSetting=" . $setting . ";\r\n" . "var upsellRockShopDomain='" . $shop . "';\r\n" .
        "var upsellRockBaseUrl='" . $upsellRockBaseUrl . "';\r\n" . $script;
});

Route::get('/upsells', [UpsellRockController::class, 'upsells']);
Route::post('/track', [UpsellRockController::class, 'track']);
Route::get('/ip', [UpsellRockController::class, 'ip']);
Route::post('/ant_rack', [UpsellRockController::class, 'antRack']);

// instruction
Route::get('/what-are-smart-auto-upsells-and-how-to-use-them-within-ant-upsell-rock', function () {
    return view('smart-auto-description');
});
Route::get('/how-to', function () {
    return view('how-to');
});

Route::get('/policy', function () {
    return view('policy');
});
