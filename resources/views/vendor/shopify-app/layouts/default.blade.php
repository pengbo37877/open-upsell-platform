<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Osiset\ShopifyApp\Util::getShopifyConfig('app_name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>

<body>
    <input type="hidden" id="shopify-token" name="shopify-token" value="">
    <div class="app-wrapper">
        <div class="app-content">
            <main role="main" id="ant-rack-app">
                @yield('content')
            </main>
        </div>
    </div>

    @if(\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled'))
    <script src="https://unpkg.com/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
    <script src="https://unpkg.com/@shopify/app-bridge-utils{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@'.config('shopify-app.appbridge_version') : '' }}"></script>
    <script @if(\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled')) data-turbolinks-eval="false" @endif>
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var utils = window['app-bridge-utils'];
        var createApp = AppBridge.default;
        var app = createApp({
            apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name ) }}",
            shopOrigin: "{{ $shopDomain ?? Auth::user()->name }}",
            forceRedirect: true,
        });
    </script>

    @include('shopify-app::partials.token_handler')
    @include('shopify-app::partials.flash_messages')
    @endif

    <script src="{{ asset('js/app.js')}}"></script>
    @yield('scripts')
</body>

</html>