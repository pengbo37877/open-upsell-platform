<?php

namespace App\Http\Controllers;

use App\Jobs\FetchCpvFromShopify;
use App\Models\Currency;
use App\Models\UpsellRock;
use App\Models\UpsellRockCollection;
use App\Models\UpsellRockDiscountCode;
use App\Models\UpsellRockDisplayCondition;
use App\Models\UpsellRockOrder;
use App\Models\UpsellRockPriceRule;
use App\Models\UpsellRockProduct;
use App\Models\UpsellRockSetting;
use App\Models\UpsellRockTrack;
use App\Models\UpsellRockVariant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpaController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function shop()
    {
        $user = Auth::user();
        $shop = $user->api()->rest('GET', '/admin/shop.json')['body']['shop'];
        return json_encode($shop);
    }

    public function currencies()
    {
        return Currency::all();
    }

    public function products()
    {
        $user = Auth::user();
        $products = $user->api()->rest('GET', '/admin/products.json')['body']['products'];
        return json_encode($products);
    }

    //TODO 这是样板
    public function local_products()
    {
        $user = Auth::user();
        $upsell_rock_products = UpsellRockProduct::where('user_id', $user->id)->select('shopify_response')->get()->toArray();
        return array_map(function ($u) {
            return $u['shopify_response'];
        }, $upsell_rock_products);
    }

    public function product(Request $request, $shopify_id)
    {
        $user = Auth::user();
        $product = $user->api()->rest('GET', '/admin/products/' . $shopify_id . '.json')['body']['product'];
        return json_encode($product);
    }

    public function custom_collections()
    {
        $user = Auth::user();
        $collections = $user->api()->rest('GET', '/admin/custom_collections.json')['body']['custom_collections'];
        foreach ($collections as $collection) {
            UpsellRockCollection::updateOrCreate([
                'user_id' => $user->id,
                'shopify_id' => $collection['id']
            ], [
                'type' => "custom",
                'title' => $collection['title'],
                'handle' => $collection['handle'],
                'rules' =>  isset($collection['rules']) ? json_encode($collection['rules']) : null,
                'shopify_response' => $collection
            ]);
        }
        return json_encode($collections);
    }

    public function smart_collections()
    {
        $user = Auth::user();
        $collections = $user->api()->rest('GET', '/admin/smart_collections.json')['body']['smart_collections'];
        foreach ($collections as $collection) {
            UpsellRockCollection::updateOrCreate([
                'user_id' => $user->id,
                'shopify_id' => $collection['id'],
            ], [
                'type' => "smart",
                'title' => $collection['title'],
                'handle' => $collection['handle'],
                'rules' => isset($collection['rules']) ? json_encode($collection['rules']) : null,
                'shopify_response' => $collection
            ]);
        }
        return json_encode($collections);
    }

    public function local_collections()
    {
        $user = Auth::user();
        $upsell_rock_collections = UpsellRockCollection::where('user_id', $user->id)->select('shopify_response')->get()->toArray();
        return array_map(function ($u) {
            return $u['shopify_response'];
        }, $upsell_rock_collections);
    }

    public function update_smart_collection()
    {
        $user = Auth::user();
        $smart_collections = UpsellRockCollection::where('user_id', $user->id)->where('type', 'smart')->get();
        foreach ($smart_collections as $collection) {
            $result = $user->api()->rest('PUT', '/admin/smart_collections/' . $collection->shopify_id . '.json', [
                'smart_collection' => [
                    "rules" => [
                        [
                            "column" => "type",
                            "relation" => "not_equals",
                            "condition" => "antrack_generated"
                        ]
                    ]
                ]
            ]);
        }
    }

    public function upsells()
    {
        $user = Auth::user();
        $upsells = UpsellRock::with('conditions')->where('user_id', $user->id)->orderByDesc('active')->get();
        return $upsells;
    }

    public function upsell(Request $request, $id)
    {
        $upsellRock = UpsellRock::find($id);
        if ($upsellRock->display_for_type == UpsellRock::DISPLAY_FOR_TYPE_SPECIFIC_COLLECTIONS) {
            $upsellRock['conditions'] = [
                UpsellRockDisplayCondition::where('upsell_rock_id', $id)->first()
            ];
        } else {
            $upsellRock['conditions'] = UpsellRockDisplayCondition::where('upsell_rock_id', $id)->get();
        }
        return $upsellRock;
    }

    public function disable_upsell(Request $request)
    {
        $user = Auth::user();
        $upsell = UpsellRock::find($request->id);
        if ($user->id != $upsell->user_id) {
            return response()->isNotFound();
        }
        $upsell->update([
            'active' => 0
        ]);
        return response()->noContent();
    }

    public function enable_upsell(Request $request)
    {
        $user = Auth::user();
        $upsell = UpsellRock::find($request->id);
        if ($user->id != $upsell->user_id) {
            return response()->isNotFound();
        }
        $upsell->update([
            'active' => 1
        ]);
        return response()->noContent();
    }

    public function updateUpsell(Request $request, $id)
    {
        $user = Auth::user();
        $upsell = UpsellRock::find($id);
        if ($upsell->user_id != $user->id) {
            throw new Exception('You can not edit upsell not belongs to you', 500);
        }
        $data = $request->all();
        // validate fail set active to 0
        if ($data['type'] == UpsellRock::TYPE_PRODUCT && count($data['shopify_product_id']) == 0) {
            $data['active'] = 0;
        } else if ($data['display_for_type'] != UpsellRock::DISPLAY_FOR_TYPE_ALL && count($data['conditions']) == 0) {
            $data['active'] = 0;
        }

        // custom service price
        if ($data['type'] == UpsellRock::TYPE_CUSTOM_SERVICE && $data['price'] <= 0 && empty($data['product_name'])) {
            throw new Exception('custom service need product name & price', 500);
        } else if ($data['type'] == UpsellRock::TYPE_CUSTOM_SERVICE) {
            $data['price'] = intval($data['price'] * 100);
            // create product
            if (empty($data['shopify_product_id'])) {
                info('custom service shopify_product_id is empty');
                $product = $user->api()->rest('POST', '/admin/products.json', [
                    'product' => [
                        'title' => $data['product_name'],
                        'product_type' => 'antrack_generated',
                        'body_html' => $data['description'],
                        'images' => [
                            [
                                "src" => 'https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-pngs/' . $data['icon'] . ".png"
                            ]
                        ],
                        'variants' => [
                            [
                                'price' => $data['price'] / 100,
                                'option1' => $data['headline'],
                            ]
                        ]
                    ]
                ])['body']['product'];

                // create or update upsell rock product
                $variants = $product['variants'];
                $ids = [];
                foreach ($variants as $variant) {
                    array_push($ids, $variant->id);
                    $upsell_variant = UpsellRockVariant::updateOrCreate([
                        'product_id' => $variant['product_id'],
                        'variant_id' => $variant['id'],

                    ], [
                        'price' => $variant['price'] * 100,
                        'shopify_response' => $variant
                    ]);
                    $upsell_variant->increment('times');
                }
                $upsell_product = UpsellRockProduct::updateOrCreate([
                    'user_id' => $user->id,
                    'shopify_product_id' => $product['id'],
                ], [
                    'shopify_collection_id' => isset($product['collection_id']) ? $product['collection_id'] : 0,
                    'shopify_product_variants' => implode(",", $ids),
                    'title' => $product['title'],
                    'type' => $product['product_type'],
                    'status' => $product['status'],
                    'handle' => $product['handle'],
                    'image' => empty($product['image']) ? null : $product['image']['src'],
                    'shopify_response' => $product
                ]);
                $upsell_product->increment('times');

                $data['shopify_product_id'] = [$product['id']];
                $data['shopify_product_variant_id'] = $product['variants'][0]['id'];
                $data['shopify_product_handle'] = $product['handle'];
            } else {
                // update product
                $product = $user->api()->rest('PUT', '/admin/products/' . $upsell->shopify_product_id . '.json', [
                    'product' => [
                        'id' => $upsell->shopify_product_id,
                        'title' => $data['product_name'],
                        'body_html' => $data['description'],
                        'images' => [
                            [
                                "src" => 'https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-pngs/' . $data['icon'] . ".png"
                            ]
                        ],
                        'variants' => [
                            [
                                'id' => $upsell->shopify_product_variant_id,
                                'option1' => $data['headline'],
                                'price' => $data['price'] / 100
                            ]
                        ]
                    ]
                ])['body']['product'];
            }
        }

        // update conditions
        UpsellRockDisplayCondition::where('user_id', $user->id)->where('upsell_rock_id', $id)->delete();
        $new_conditions = $request->get('conditions');
        foreach ($new_conditions as $condition) {
            // build the collection hit table
            if ($condition['type'] == 'specific-collections' && $condition['collection_id'] > 0) {
                $products = $user->api()->rest(
                    'GET',
                    "/admin/collections/" . $condition['collection_id'] . "/products.json",
                    [
                        'limit' => 250
                    ]
                )['body']['products'];

                if (gettype($products) === 'string') {
                    throw new Exception("can not get collection's products", 500);
                }
                foreach ($products as $product) {
                    $vs = UpsellRockVariant::where('product_id', $product['id'])->get();
                    foreach ($vs as $variant) {
                        UpsellRockDisplayCondition::create([
                            'user_id' => $user->id,
                            'upsell_rock_id' => $id,
                            'type' => $condition['type'],
                            'collection_id' => $condition['collection_id'],
                            'product_id' => $product['id'],
                            'product_variant_id' => $variant->variant_id,
                            'variant_price' => $variant->price
                        ]);
                    }
                }
            } else if ($condition['type'] == 'specific-products' && $condition['product_id'] > 0) {
                $variant = UpsellRockVariant::where('variant_id', $condition['product_variant_id'])->first();
                UpsellRockDisplayCondition::create([
                    'user_id' => $user->id,
                    'upsell_rock_id' => $id,
                    'type' => $condition['type'],
                    'product_id' => isset($condition['product_id']) ? $condition['product_id'] : null,
                    'product_variant_id' => isset($condition['product_variant_id']) ? $condition['product_variant_id'] : null,
                    'variant_price' => $variant ? $variant->price : null
                ]);
            }
        }
        unset($data['conditions']);
        if ($data['display_for_type'] === UpsellRock::DISPLAY_FOR_TYPE_ALL) {
            UpsellRockDisplayCondition::create([
                'user_id' => $user->id,
                'upsell_rock_id' => $id,
                'type' => UpsellRock::DISPLAY_FOR_TYPE_ALL
            ]);
        }
        // create discount code
        if ($data['apply_discount'] && empty($data['discount_code'])) {
            $amount_type = $data['amount_type'];
            $amount = $data['amount'];
            if ($data['type'] == UpsellRock::TYPE_SMART_AUTO) {
                $collection_ids = UpsellRockCollection::select('shopify_id')->where('user_id', $user->id)->get()->pluck('shopify_id')->toArray();
                $form = [
                    "title" => "ANT-RACK-" . $this->generateRandomString(),
                    "target_type" => "line_item",
                    "target_selection" => "entitled",
                    "allocation_method" => "each",
                    "value_type" => $amount_type,
                    "value" => -$amount,
                    "customer_selection" => "all",
                    "entitled_collection_ids" => $collection_ids,
                    "starts_at" => now()
                ];
            } else if ($data['type'] == 'product') {
                $form = [
                    "title" => "ANT-RACK-" . $this->generateRandomString(),
                    "target_type" => "line_item",
                    "target_selection" => "entitled",
                    "allocation_method" => "each",
                    "value_type" => $amount_type,
                    "value" => -$amount,
                    "customer_selection" => "all",
                    "entitled_variant_ids" =>
                    empty($data['shopify_product_variant_id']) ? $data['shopify_product_variants'] : [$data['shopify_product_variant_id']],
                    "starts_at" => now()
                ];
            }
            $price_rule = $user->api()->rest('POST', '/admin/price_rules.json', [
                "price_rule" => $form
            ])['body']['price_rule'];
            // return $price_rule;
            UpsellRockPriceRule::create([
                'user_id' => $user->id,
                'shopify_id' => $price_rule['id'],
                'title' => $price_rule['title'],
                'value_type' => $price_rule['value_type'],
                'value' => $price_rule['value'],
                'target_type' => $price_rule['target_type'],
                'target_selection' => $price_rule['target_selection'],
                'customer_selection' => $price_rule['customer_selection'],
                'starts_at' => $price_rule['starts_at'],
                'shopify_response' => $price_rule
            ]);
            $discount_code = $user->api()->rest('POST', '/admin/price_rules/' . $price_rule['id'] . '/discount_codes.json', [
                "discount_code" => [
                    "code" => $form['title']
                ]
            ])['body']['discount_code'];
            UpsellRockDiscountCode::create([
                'user_id' => $user->id,
                'code' => $discount_code['code'],
                'shopify_id' => $discount_code['id'],
                'shopify_price_rule_id' => $discount_code['price_rule_id'],
                'shopify_response' => $discount_code
            ]);
            $data['discount_code'] = $discount_code['code'];
        }
        $data['shopify_product_id'] = count($data['shopify_product_id']) == 1 ? $data['shopify_product_id'][0] : null;

        $upsell->update($data);

        // return current upsell
        $upsellRock = UpsellRock::find($id);
        if ($upsellRock->display_for_type == UpsellRock::DISPLAY_FOR_TYPE_SPECIFIC_COLLECTIONS) {
            $upsellRock['conditions'] = [
                UpsellRockDisplayCondition::where('upsell_rock_id', $id)->first()
            ];
        } else {
            $upsellRock['conditions'] = UpsellRockDisplayCondition::where('upsell_rock_id', $id)->get();
        }
        return $upsellRock;
    }

    function generateRandomString($length = 6)
    {
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function smart_auto_upsell()
    {
        $user = Auth::user();
        $upsell = UpsellRock::with('conditions')->where('user_id', $user->id)->where('type', UpsellRock::TYPE_SMART_AUTO)->first();
        if (!$upsell) return [];
        return $upsell;
    }

    public function create_upsell(Request $request)
    {
        $type = $request->type;
        if (empty($type)) {
            return response()->isNotFound();
        }
        $user = Auth::user();
        $type_upsell_count = UpsellRock::where('user_id', $user->id)->where('type', $type)->count();
        if ($type == UpsellRock::TYPE_SMART_AUTO && $type_upsell_count > 0) {
            throw new Exception("you already has one smart auto upsell", 500);
        }
        $upsell = UpsellRock::create([
            'user_id' => $user->id,
            'active' => 0,
            'type' => $type,
            'name' => $this->getUpsellName($type, $type_upsell_count + 1),
            'apply_discount' => 0,
            'amount_type' => "percentage",
            'amount' => 10,
            'enable_quantity_selector' => 0,
            'use_recommended_products' => ($type == UpsellRock::TYPE_SMART_AUTO) ? 1 : 0
        ]);

        return $upsell;
    }

    function getUpsellName($type, $number)
    {
        if ($type === UpsellRock::TYPE_PRODUCT) {
            return "Product Upsell " . $number;
        }
        if ($type === UpsellRock::TYPE_SMART_AUTO) {
            return "Smart Auto-Upsell " . $number;
        }
        if ($type === UpsellRock::TYPE_CUSTOM_SERVICE) {
            return "Custom Service " . $number;
        }
        return "Upsell Name";
    }

    public function setting()
    {
        $user = Auth::user();
        return UpsellRockSetting::where('user_id', $user->id)->first();
    }

    public function updateSetting(Request $request)
    {
        $user = Auth::user();
        $setting =
            UpsellRockSetting::where('user_id', $user->id)->first();
        $setting->update($request->all());
        return $setting;
    }

    public function statistics(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->iana_timezone;
        if (empty($timezone)) {
            $timezone = 'GMT';
        }
        $start = date('Y-m-d H:i:s', intval($request->start) / 1000);
        $end = date('Y-m-d H:i:s', intval($request->end) / 1000);
        $views_count = UpsellRockTrack::where('user_id', $user->id)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('event_type', 'view')->count();

        $add_to_carts_count = UpsellRockTrack::where('user_id', $user->id)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('event_type', 'add_to_cart')->count();

        $transactions_count = UpsellRockOrder::where('user_id', $user->id)
            ->where('cart_token', '<>', "")
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->count();

        $sales = UpsellRockOrder::where('user_id', $user->id)
            ->where('cart_token', '<>', "")
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->sum('total_price');

        return [
            'views_count' => $views_count,
            'add_to_carts_count' => $add_to_carts_count,
            'transactions_count' => $transactions_count,
            'sales' => $sales
        ];
    }

    public function sessions(Request $request)
    {
        $user = Auth::user();
        $sessions = UpsellRockTrack::select(DB::raw('cart_token as session, count(cart_token) as session_count'))
            ->where('user_id', $user->id)
            ->where('created_at', '>=', date('Y-m-01 00:00:00'))
            ->orderByDesc('session_count')
            ->groupBy('session')->get()->toArray();
        return $sessions;
    }

    public function views(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->iana_timezone;
        if (empty($timezone)) {
            $timezone = 'GMT';
        }
        $start = date('Y-m-d H:i:s', intval($request->start) / 1000);
        $end = date('Y-m-d H:i:s', intval($request->end) / 1000);
        $raw = "DATE(CONVERT_TZ(DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s'), 'GMT', '" . $timezone . "')) as day, count(*) as cc";
        $stats = DB::table("upsell_rock_tracks")->select(DB::raw($raw))
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->where('event_type', 'view')
            ->groupBy(DB::raw('day'))->get()->toArray();
        return $stats;
    }

    public function upsellWithTracks(Request $request)
    {
        $user = Auth::user();
        $timezone = $user->iana_timezone;
        if (empty($timezone)) {
            $timezone = 'GMT';
        }
        $start = date('Y-m-d H:i:s', intval($request->start) / 1000);
        $end = date('Y-m-d H:i:s', intval($request->end) / 1000);
        $upsells = UpsellRock::select(['id', 'name'])->where('user_id', $user->id)->get();
        foreach ($upsells as $upsell) {
            $views = DB::select("select count(*) as cc from upsell_rock_tracks where JSON_CONTAINS(upsell_rocks, ?, '$') and event_type='view' and created_at >= ? and created_at <= ?", [$upsell->id . '', $start, $end]);
            $upsell['views_count'] = $views[0]->cc;
            $add_to_carts = DB::select("select count(*) as cc from upsell_rock_tracks where JSON_CONTAINS(upsell_rocks, ?, '$') and event_type='add_to_cart' and created_at >= ? and created_at <= ?", [$upsell->id . '', $start, $end]);
            $upsell['add_to_carts_count'] = $add_to_carts[0]->cc;
            $cart_tokens = DB::select("select cart_token from upsell_rock_tracks where user_id=? and created_at >= ? and created_at <= ? group by cart_token", [$user->id, $start, $end]);
            $arr_tokens = array_map(function ($t) {
                return $t->cart_token;
            }, $cart_tokens);
            $transactions = UpsellRockOrder::where('cart_token', '<>', "")->whereIn('cart_token', $arr_tokens)->get();
            $transactions_count = count($transactions);
            $sales = $transactions->sum('total_price');
            $upsell['transactions_count'] = $transactions_count;
            $upsell['sales'] = $sales;
        }
        $sorted = $upsells->sortByDesc('add_to_carts_count');
        return $sorted->values()->all();
    }
}
