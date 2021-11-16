<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Currency;
use App\Models\UpsellRock;
use Illuminate\Http\Request;
use App\Models\ShopifyCurrency;
use App\Models\UpsellRockTrack;
use App\Models\UpsellRockProduct;
use App\Models\UpsellRockSetting;
use App\Models\UpsellRockVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\UpsellRockResouce;
use App\Models\UpsellRockDisplayCondition;

class UpsellRockController extends Controller
{
    public function antRack(Request $request)
    {
        $shop = $request->shop;
        $cart = $request->cart;
        $user = User::where('name', $shop)->first();
        if (!$user) {
            env("APP_DEBUG") ? info('user is not found') : '';
            return "";
        }
        $bs = $this->upsells($request);
        if (count($bs) == 0) {
            env('APP_DEBUG') ? info('bs is not found') : "";
            return "";
        }
        $smartAuto = null;
        foreach ($bs as $b) {
            if ($b->type == 'smart-auto') {
                $smartAuto = $b;
            }
        }
        $upsells = json_encode($bs);
        $setting = UpsellRockSetting::where('user_id', $user->id)->first();
        $currency = Currency::where('currency', $cart['currency'])->first();
        $cart = json_encode($cart);
        if ($currency) {
            $currency = json_encode($currency);
        }
        $smartProducts = "[]";
        if ($smartAuto) {
            $smartProducts = $this->smartProducts($request, $bs, $smartAuto);
        }
        $url = env('APP_URL');
        $currencies = Cache::remember('ant-rack-currencies', 60 * 60, function () {
            return Currency::all();
        });
        $shopifyCurrency = Cache::remember('ant-rack-shopify-currency', 60 * 60, function () {
            return ShopifyCurrency::orderByDesc('id')->first()->body;
        });
        return view('ant-rack-app', compact('shop', 'url', 'cart', 'upsells', 'setting', 'currency', 'smartProducts', 'currencies', 'shopifyCurrency'));
    }

    /*
    * 当前购物车从自动推荐里面已经添加到订单的产品
    */
    function smartTakesProducts($request, $user, $smartAuto)
    {
        return [];
        // already takes
        // $cart = $request->cart;
        // $takes = CartUpsellTrack::where('cart_token', $cart['token'])->where('upsell_rock_id', $smartAuto->id)->get();
        // $takesProductIds = [];
        // if (count($takes) > 0) {
        //     $takesVariantIds = $takes->pluck('variant_id')->toArray();
        //     $takesProductIds = UpsellRockVariant::select('product_id')->whereIn('variant_id', $takesVariantIds)->get();
        //     $takesProductIds = $takesProductIds->pluck('product_id')->toArray();
        //     $takesProductIds = array_unique($takesProductIds);
        //     $takesProductIds = array_values($takesProductIds);
        // }
        // return UpsellRockProduct::select('shopify_response')
        //     ->where('user_id', $user->id)
        //     ->whereIn('shopify_product_id', $takesProductIds)
        //     ->get()->pluck('shopify_response');
    }

    function smartProducts(Request $request, $bs, UpsellRock $smartAuto)
    {
        $shop = $request->shop;
        $cart = $request->cart;
        $limit = $smartAuto->recommended_product_count;
        $bsIds = $bs->pluck('shopify_product_id')->toArray();
        $user = User::where('name', $shop)->first();
        if (!$user) {
            return [];
        }

        $cartProductIds = array_map(function ($item) {
            return $item['product_id'];
        }, $cart['items']);
        $ids = UpsellRockProduct::select('shopify_product_id')
            ->where('type', '')
            ->where('image', "<>", null)
            ->where('user_id', $user->id)->get()->pluck('shopify_product_id')->toArray();
        $leftIds = array_diff($ids, $cartProductIds); // 购物车里面有的不再推荐
        $leftIds = array_diff($leftIds, $bsIds); // 其他upsell里面推的不再推荐
        shuffle($leftIds);
        env('APP_DEBUG') ? info('smartProducts ' . implode(',', $leftIds)) : false;
        $leftIds = array_slice($leftIds, 0, $limit);
        $recommendations = UpsellRockProduct::select('shopify_response')
            ->where('user_id', $user->id)
            ->whereIn('shopify_product_id', $leftIds)
            ->get()->pluck('shopify_response');
        return $recommendations;
    }

    public function recommendations(Request $request)
    {
        $shop = $request->shop;
        $cart = $request->cart;
        $limit = $request->limit;
        $user = User::where('name', $shop)->first();
        if (!$user) {
            return [
                'products' => []
            ];
        }
        $cartIds = array_map(function ($item) {
            return $item['product_id'];
        }, $cart['items']);
        $ids = UpsellRockProduct::select('shopify_product_id')->where('user_id', $user->id)->get()->pluck('shopify_product_id')->toArray();
        $leftIds = array_diff($ids, $cartIds);
        $recommendations = UpsellRockProduct::select('shopify_response')
            ->where('user_id', $user->id)
            ->whereIn('shopify_product_id', $leftIds)
            ->take($limit ? intval($limit) : 1)
            ->get()->pluck('shopify_response');
        return [
            'products' => $recommendations
        ];
    }

    public function upsells(Request $request)
    {
        $shop = $request->shop;
        $cart = $request->cart;
        $variant_id = $request->variant;
        if (empty($shop) || empty($variant_id)) return [];
        $user = User::where('name', $shop)->first();
        if (!$user) return [];

        // check plan sessions
        $track_session_count = UpsellRockTrack::select(DB::raw('cart_token as session, count(cart_token) as session_count'))
            ->where('user_id', $user->id)
            ->where('created_at', '>=', date('Y-m-01 00:00:00'))
            ->orderByDesc('session_count')
            ->groupBy('session')->get()->count();
        if (empty($user->plan_id) && $track_session_count >= 200) {
            return [];
        } else if ($user->plan_id == 7 && $track_session_count >= 2000) {
            return [];
        } else if ($user->plan_id == 8 && $track_session_count >= 5000) {
            return [];
        }

        $variant = UpsellRockVariant::where('variant_id', $variant_id)->first();
        if (!$variant) return [];
        $product_id = $variant->product_id;
        $price = $variant->price;
        info('variant price=' . $price);
        $ids = UpsellRockDisplayCondition::select('upsell_rock_id')
            ->where('user_id', $user->id)
            ->where(function ($query) use ($product_id, $variant_id) {
                return $query->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)
                    ->orWhere('type', 'all-products');
            })->get()->pluck('upsell_rock_id')->toArray();
        info('ids=' . implode(',', $ids));
        // ids 去重
        $ids = array_flip($ids);
        $ids = array_flip($ids);
        $ids = array_values($ids);
        $upsells = UpsellRock::where('active', 1)->find($ids);
        $filtered_ids = [];
        foreach ($upsells as $upsell) {
            $mini_hit = false;
            $max_hit = false;
            if ($upsell->price_type == 'range') {
                if (empty($upsell->minimum_price) || intval($upsell->minimum_price * 100) <= $price) {
                    $mini_hit = true;
                }
                if (empty($upsell->maximum_price) || intval($upsell->maximum_price * 100) >= $price) {
                    $max_hit = true;
                }
                if ($mini_hit && $max_hit) {
                    array_push($filtered_ids, $upsell->id);
                }
            } else if ($upsell->hide_upsell_product_already_in_cart && $upsell->type != 'smart-auto') {
                $cartProductIds = array_map(function ($item) {
                    return $item['product_id'];
                }, $cart['items']);
                if (!in_array($upsell->shopify_product_id, $cartProductIds)) {
                    array_push($filtered_ids, $upsell->id);
                }
            } else {
                array_push($filtered_ids, $upsell->id);
            }
        }
        return UpsellRock::with('product')->where('active', 1)->orderBy('order')->find($filtered_ids);
    }

    public function track(Request $request)
    {
        $shop = $request->shop;
        $cart_token = $request->cart_token;
        $event_type = $request->event_type;
        $upsell_rocks = $request->upsell_rocks;
        $data = $request->data;
        $stats_at = $request->stats_at;
        $user = User::where('name', $shop)->first();
        $track = UpsellRockTrack::create([
            'user_id' => $user->id,
            'cart_token' => $cart_token,
            'event_type' => $event_type,
            'ip' => $request->ip(),
            'upsell_rocks' => json_encode($upsell_rocks),
            'data' => json_encode($data),
            'stats_at' => $stats_at,
        ]);
        return $track;
    }

    public function ip(Request $request)
    {
        $shopDomain = $request->shop;
        if (!$shopDomain) {
            return [];
        }
        // $user = User::where('name', $shopDomain)->first();
        $data = file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $request->ip());
        info("from ip=" . $request->ip() . " fetch data=" . $data);
        return $data;
    }
}
