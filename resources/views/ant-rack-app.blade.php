<div id="ant-rack">
    <input type="text" value="{{$shop}}" class="shop" id="shop" hidden>
    <input type="text" value="{{$url}}" class="url" id="url" hidden>
    <div class="cart" id="cart" style="display: none">{{$cart}}</div>
    <div class="currency" id="currency" style="display: none">{{$currency}}</div>
    <div class="upsells" id="upsells" style="display: none">{{$upsells}}</div>
    <div class="setting" id="setting" style="display: none">{{$setting}}</div>
    <div class="smart-products" id="smart-products" style="display: none">{{$smartProducts}}</div>
    <div class="currencies" id="currencies" style="display: none">{{$currencies}}</div>
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="ant-bg-wrap">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div id="ant-bg-overlay" class="fixed inset-0 bg-gray-700 bg-opacity-75 transition-opacity ease-out duration-300 opacity-0" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <ant-rack></ant-rack>
        </div>
    </div>
</div>