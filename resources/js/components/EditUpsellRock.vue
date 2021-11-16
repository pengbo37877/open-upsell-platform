<template>
    <div>
        <!-- navs -->
        <nav class="bg-gray-800">
            <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    <div
                        class="flex items-center justify-center flex-1 sm:items-stretch sm:justify-start"
                    >
                        <div class="flex items-center flex-shrink-0">
                            <img
                                class="w-auto h-8"
                                src="/images/upsell-logo.svg"
                                alt
                            />
                        </div>
                        <div class="hidden sm:block sm:ml-6">
                            <div class="flex space-x-4">
                                <router-link
                                    to="/"
                                    class="block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-md"
                                    aria-current="page"
                                    >Dashboard</router-link
                                >
                                <router-link
                                    to="/setting"
                                    class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                                    aria-current="page"
                                    >Setting</router-link
                                >
                                <router-link
                                    to="/performance"
                                    class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                                    aria-current="page"
                                    >Performance</router-link
                                >
                                <div
                                    class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md cursor-pointer hover:bg-gray-700 hover:text-white"
                                    aria-current="page"
                                    @click="gotoCCA"
                                >
                                    Add 170+ Currencies to your shop
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile menu, show/hide based on menu state. -->
            <div class="sm:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <router-link
                        to="/"
                        class="block px-3 py-2 text-base font-medium text-white bg-gray-900 rounded-md"
                        aria-current="page"
                        >Dashboard</router-link
                    >
                    <router-link
                        to="/setting"
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                        aria-current="page"
                        >Setting</router-link
                    >
                    <router-link
                        to="/performance"
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                        aria-current="page"
                        >Performance</router-link
                    >
                    <div
                        class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md cursor-pointer hover:bg-gray-700 hover:text-white"
                        aria-current="page"
                        @click="gotoCCA"
                    >
                        Add 170+ Currencies to your shop
                    </div>
                </div>
            </div>
        </nav>
        <!-- end navs -->

        <!-- content -->
        <div class="max-w-6xl py-6 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 lg:grid-cols-3">
                <!-- for edit -->
                <div class="col-span-1 pb-24">
                    <!-- upsell name -->
                    <div class="flex flex-col p-6 bg-white rounded shadow">
                        <div>
                            <label
                                for="upsell-name"
                                class="block text-sm text-gray-800"
                                >Upsell name</label
                            >
                            <input
                                type="text"
                                name="upsell-name"
                                id="upsell-name"
                                autocomplete="given-name"
                                v-model="form.name"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                            <p class="mt-1 text-xs font-light text-gray-500">
                                For your own internal reference. Only you can
                                see it.
                            </p>
                        </div>
                    </div>
                    <!-- active -->
                    <div
                        class="flex flex-col p-6 mt-4 transition-all ease-in-out transform bg-white rounded shadow"
                        :class="{ 'bg-green-50': form.active }"
                    >
                        <label
                            for="upsell-name"
                            class="block text-sm text-gray-800"
                            >Status</label
                        >
                        <div
                            class="flex items-center justify-between mt-1"
                            v-if="form.active"
                        >
                            <div class="flex font-bold text-green-900">
                                <div>
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-2">Active</div>
                            </div>
                            <div
                                class="flex items-center w-auto px-3 py-2 text-sm font-light text-white bg-gray-600 rounded-sm cursor-pointer hover:bg-gray-700"
                                @click="disableUpsell"
                            >
                                <svg
                                    class="w-4 h-4 mr-2 animate-spin"
                                    viewBox="0 0 1024 1024"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    v-if="updating"
                                >
                                    <path
                                        d="M510.5 958.7c-60.4 0-119-11.8-174.2-35.2-53.3-22.5-101.1-54.8-142.2-95.9s-73.3-88.9-95.9-142.2C74.9 630.2 63 571.6 63 511.2s11.8-119 35.2-174.2c22.5-53.3 54.8-101.1 95.9-142.2S283 121.5 336.3 99c55.2-23.3 113.8-35.2 174.2-35.2 49.3 0 97.8 8 144.1 23.7 26.1 8.9 40.1 37.3 31.2 63.4-7.1 20.8-26.5 33.9-47.3 33.9-5.3 0-10.8-0.9-16.1-2.7-35.9-12.2-73.6-18.4-111.9-18.4-92.8 0-180 36.1-245.7 101.8C199.2 331.1 163 418.4 163 511.2c0 92.8 36.1 180 101.8 245.7 65.6 65.6 152.9 101.8 245.7 101.8s180-36.1 245.7-101.8C821.8 691.3 858 604 858 511.2c0-58.6-14.9-116.6-43-167.6-13.3-24.2-4.6-54.6 19.6-67.9 24.2-13.3 54.6-4.6 67.9 19.6 36.3 65.7 55.4 140.4 55.4 215.9 0 60.4-11.8 119-35.2 174.2-22.5 53.3-54.8 101.1-95.9 142.2-41.1 41.1-88.9 73.3-142.2 95.9-55.1 23.3-113.7 35.2-174.1 35.2z"
                                        fill="#ffffff"
                                    />
                                </svg>
                                Disable
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between mt-1"
                            v-else
                        >
                            <div class="font-bold text-gray-500">Disabled</div>
                            <div
                                class="flex items-center w-auto px-3 py-2 text-sm font-light text-white bg-green-700 rounded-sm cursor-pointer hover:bg-green-800"
                                @click="enableUpsell"
                            >
                                <svg
                                    class="w-4 h-4 mr-2 animate-spin"
                                    viewBox="0 0 1024 1024"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    v-if="updating"
                                >
                                    <path
                                        d="M510.5 958.7c-60.4 0-119-11.8-174.2-35.2-53.3-22.5-101.1-54.8-142.2-95.9s-73.3-88.9-95.9-142.2C74.9 630.2 63 571.6 63 511.2s11.8-119 35.2-174.2c22.5-53.3 54.8-101.1 95.9-142.2S283 121.5 336.3 99c55.2-23.3 113.8-35.2 174.2-35.2 49.3 0 97.8 8 144.1 23.7 26.1 8.9 40.1 37.3 31.2 63.4-7.1 20.8-26.5 33.9-47.3 33.9-5.3 0-10.8-0.9-16.1-2.7-35.9-12.2-73.6-18.4-111.9-18.4-92.8 0-180 36.1-245.7 101.8C199.2 331.1 163 418.4 163 511.2c0 92.8 36.1 180 101.8 245.7 65.6 65.6 152.9 101.8 245.7 101.8s180-36.1 245.7-101.8C821.8 691.3 858 604 858 511.2c0-58.6-14.9-116.6-43-167.6-13.3-24.2-4.6-54.6 19.6-67.9 24.2-13.3 54.6-4.6 67.9 19.6 36.3 65.7 55.4 140.4 55.4 215.9 0 60.4-11.8 119-35.2 174.2-22.5 53.3-54.8 101.1-95.9 142.2-41.1 41.1-88.9 73.3-142.2 95.9-55.1 23.3-113.7 35.2-174.1 35.2z"
                                        fill="#ffffff"
                                    />
                                </svg>
                                Active
                            </div>
                        </div>
                    </div>
                    <!-- choose upsell product -->
                    <choose-upsell-product
                        class="mt-4 rounded-b-none"
                        :upsell.sync="form"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type == 'product'
                        "
                    ></choose-upsell-product>
                    <!-- smart auto upsell product -->
                    <div
                        class="flex flex-col p-6 mt-4 bg-white rounded rounded-b-none shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type == 'smart-auto'
                        "
                    >
                        <div class="text-sm font-medium text-gray-900">
                            UPSELL PRODUCT
                        </div>
                        <div class="flex p-4 mt-4 bg-gray-100 rounded">
                            <div>
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="flex flex-col ml-2">
                                <div class="text-gray-800">
                                    Smart Auto-Upsell
                                </div>
                                <div
                                    class="mt-1 text-sm font-light text-gray-500"
                                >
                                    We'll use
                                    <span class="italic"
                                        >Shopify Product Recommendation
                                        API</span
                                    >
                                    to offer products relevant to the one
                                    customer just added to cart.
                                </div>
                                <div class="flex mt-1">
                                    <a
                                        href="/what-are-smart-auto-upsells-and-how-to-use-them-within-ant-upsell-rock"
                                        target="_blank"
                                        class="px-3 py-1 text-sm font-light text-gray-500 border border-gray-500 rounded"
                                        >Read more</a
                                    >
                                    <div class="flex-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- icon -->
                    <div
                        class="flex flex-col p-6 mt-4 bg-white rounded rounded-b-none shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type == 'custom-service'
                        "
                    >
                        <label
                            for="upsell-description"
                            class="block text-sm text-gray-800"
                            >ICON</label
                        >
                        <icons :icon.sync="form.icon"></icons>
                    </div>

                    <!-- custom service info -->
                    <div
                        class="flex flex-col p-6 bg-white border-t border-b border-gray-200 shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type == 'custom-service'
                        "
                    >
                        <label
                            for="upsell-description"
                            class="block text-sm text-gray-800"
                            >Product name</label
                        >
                        <input
                            type="text"
                            name="upsell-product-name"
                            id="upsell-product-name"
                            v-model="form.product_name"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                        <p class="text-sm font-light text-gray-500">
                            Name of the product in the cart
                        </p>

                        <label
                            for="upsell-description"
                            class="block mt-3 text-sm text-gray-800"
                            >Headline</label
                        >
                        <input
                            type="text"
                            name="upsell-headline"
                            id="upsell-headline"
                            v-model="form.headline"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                        <p class="text-sm font-light text-gray-500">
                            Name of the product on upsell popup
                        </p>

                        <label
                            for="upsell-short-description"
                            class="block mt-3 text-sm text-gray-800"
                            >Description</label
                        >
                        <input
                            type="text"
                            name="upsell-short-description"
                            id="upsell-short-description"
                            v-model="form.short_description"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                        <p class="text-sm font-light text-gray-500">
                            Upsell description on the pop-up below the headline
                        </p>

                        <label
                            for="upsell-description"
                            class="block mt-3 text-sm text-gray-800"
                            >Detail description</label
                        >
                        <textarea
                            type="text"
                            rows="6"
                            name="upsell-short-description"
                            id="upsell-short-description"
                            v-model="form.description"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                        <p class="text-sm font-light text-gray-500">
                            Detailed description is shown on the upsell detail
                            after customer clicks on the headline or the icon
                        </p>

                        <label
                            for="upsell-price"
                            class="block mt-3 text-sm text-gray-800"
                            >Price</label
                        >
                        <div class="flex mt-1 rounded-md shadow-sm w-36">
                            <input
                                type="text"
                                min="0"
                                name="upsell-price"
                                id="upsell-price"
                                @change="onPriceInput"
                                v-model="form.price"
                                class="flex-1 block w-full border-gray-300 rounded-none focus:ring-indigo-500 focus:border-indigo-500 rounded-l-md sm:text-sm"
                            />
                            <span
                                class="inline-flex items-center px-3 text-sm text-gray-500 border border-l-0 border-gray-300 rounded-r-md bg-gray-50"
                                >{{ user.currency }}</span
                            >
                        </div>
                    </div>

                    <!-- description -->
                    <div
                        class="flex flex-col p-6 bg-white border-t border-b border-gray-200 shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type != 'custom-service'
                        "
                    >
                        <label
                            for="upsell-description"
                            class="block text-sm text-gray-800"
                            >Description</label
                        >
                        <input
                            type="text"
                            name="upsell-description"
                            id="upsell-description"
                            autocomplete="given-name"
                            v-model="form.short_description"
                            placeholder="Get it before it's gone"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>
                    <!-- product count -->
                    <div
                        class="flex flex-col p-6 bg-white border-t border-b border-gray-200 shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type == 'smart-auto'
                        "
                    >
                        <label
                            for="upsell-description"
                            class="block text-sm text-gray-800"
                            >Product count</label
                        >
                        <select
                            id="product-count"
                            name="product-count"
                            v-model="form.recommended_product_count"
                            class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option
                                :value="item"
                                v-for="(item, index) in [
                                    1,
                                    2,
                                    3,
                                    4,
                                    5,
                                    6,
                                    7,
                                    8,
                                    9,
                                    10
                                ]"
                                :key="index"
                                >{{ item }}</option
                            >
                        </select>
                    </div>
                    <!-- apply discount -->
                    <div
                        class="flex flex-col p-6 bg-white rounded rounded-t-none shadow"
                        v-if="
                            Object.keys(form).length > 0 &&
                                form.type != 'custom-service'
                        "
                    >
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input
                                    id="apply-discount"
                                    name="apply-discount"
                                    type="checkbox"
                                    v-model="form.apply_discount"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                />
                            </div>
                            <div class="ml-3 text-sm">
                                <label
                                    for="apply-discount"
                                    class="text-sm text-gray-800"
                                    >Apply discount</label
                                >
                            </div>
                        </div>
                        <div
                            class="flex flex-col transition-all duration-500 ease-in-out"
                            v-if="form.apply_discount"
                        >
                            <div class="mt-4 text-sm text-gray-800">
                                Discount
                            </div>
                            <div class="flex items-center justify-start mt-1">
                                <input
                                    type="number"
                                    min="0"
                                    oninput="validity.valid||(value='1');"
                                    name="discount-amount"
                                    id="discount-amount"
                                    class="block w-20 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    v-model="form.amount"
                                />
                                <select
                                    id="discount-amount-type"
                                    name="discount-amount-type"
                                    autocomplete="discount-amount-type"
                                    v-model="form.amount_type"
                                    class="block w-20 px-3 py-2 ml-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="percentage">%</option>
                                    <option value="fixed_amount">{{
                                        user.currency
                                    }}</option>
                                </select>
                            </div>
                            <div class="mt-1 text-xs font-light text-gray-500">
                                Discounts are applied in the checkout.
                            </div>

                            <div class="mt-4 text-sm text-gray-800">
                                Custom discount code (optional)
                            </div>
                            <input
                                type="text"
                                name="discount-code"
                                id="discount-code"
                                v-model="form.discount_code"
                                placeholder="optional"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                            <div class="mt-1 text-xs font-light text-gray-500">
                                Please make sure it exists in your
                                <a
                                    :href="
                                        `https://${user.name}/admin/discounts`
                                    "
                                    target="_blank"
                                    class="text-blue-600"
                                    >Discounts</a
                                >
                                page.
                            </div>
                        </div>
                    </div>

                    <!-- display for -->
                    <div class="flex flex-col p-6 mt-4 bg-white rounded shadow">
                        <div>
                            <legend class="text-base font-medium text-gray-900">
                                Display for
                            </legend>
                        </div>
                        <!-- choose condition type -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <input
                                    id="all-products"
                                    name="all-products"
                                    v-model="form.display_for_type"
                                    value="all-products"
                                    type="radio"
                                    @click="displayForTypeChanged"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="all-products"
                                    class="block ml-3 text-sm text-gray-700"
                                    >All products</label
                                >
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="specific-products"
                                    name="specific-products"
                                    v-model="form.display_for_type"
                                    value="specific-products"
                                    type="radio"
                                    @click="displayForTypeChanged"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="specific-products"
                                    class="block ml-3 text-sm text-gray-700"
                                    >Specific products</label
                                >
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="specific-collections"
                                    name="specific-collections"
                                    v-model="form.display_for_type"
                                    value="specific-collections"
                                    type="radio"
                                    @click="displayForTypeChanged"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="specific-collections"
                                    class="block ml-3 text-sm text-gray-700"
                                    >Specific collections</label
                                >
                            </div>
                        </div>
                        <!-- choose condition items -->
                        <div
                            class="mt-4"
                            v-if="form.display_for_type !== 'all-products'"
                        >
                            <div class="flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 text-sm text-gray-500 bg-white border border-r-0 border-gray-300 rounded-l-md"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="condition-item"
                                    id="condition-item"
                                    :value="query_str"
                                    @input="onQueryStrChange"
                                    class="flex-1 block w-full border-l-0 border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                                <span
                                    @click="showChoose"
                                    class="inline-flex items-center px-3 text-sm text-gray-800 border border-l-0 border-gray-300 cursor-pointer rounded-r-md bg-gray-50"
                                    >Browse</span
                                >
                            </div>
                        </div>
                        <!-- specific products condition items -->
                        <div
                            class="mt-2"
                            v-if="form.display_for_type === 'specific-products'"
                        >
                            <div class="flex flex-col">
                                <div
                                    class="flex items-center justify-between py-2 border-b border-gray-300"
                                    v-for="(condition,
                                    index) in form.conditions"
                                    :key="index"
                                >
                                    <div
                                        v-if="condition.product_id !== null"
                                        class="flex items-center justify-center overflow-hidden border border-gray-300 rounded"
                                    >
                                        <img
                                            class="w-10 h-10"
                                            :src="
                                                getProductImage(
                                                    condition.product_id
                                                )
                                            "
                                            alt
                                            v-if="
                                                getProduct(condition.product_id)
                                                    .image
                                            "
                                        />
                                        <div v-else>
                                            <svg
                                                t="1626758737081"
                                                class="w-10 h-10 icon"
                                                viewBox="0 0 1024 1024"
                                                version="1.1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                p-id="2043"
                                            >
                                                <path
                                                    d="M109.248 960L64 914.752 914.752 64 960 109.248l-64 64V832a64.224 64.224 0 0 1-64 64H173.248l-64 64z m377.376-377.376L237.248 832H832v-96l-128-128-50.752 50.752a64 64 0 0 1-90.496 0l-76.16-76.128zM704 544a63.552 63.552 0 0 1 45.248 18.72L832 645.376V237.248l-300.064 300.032L608 613.376l50.752-50.784A63.616 63.616 0 0 1 704 544zM192 704H128V192a64 64 0 0 1 64-64h512v64H192v325.504l114.784-114.784a64 64 0 0 1 90.496 0l44 44-45.312 45.312L352 448l-160 160v96z"
                                                    p-id="2044"
                                                    fill="#bfbfbf"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col justify-between flex-1 ml-3"
                                        v-if="condition.product_id !== null"
                                    >
                                        <div class="text-sm text-gray-800">
                                            {{
                                                getProductTitle(
                                                    condition.product_id
                                                )
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                getVariantOptions(
                                                    condition.product_id,
                                                    condition.product_variant_id
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center justify-center text-gray-800"
                                        v-if="condition.product_id !== null"
                                        @click="
                                            removeCondition(
                                                condition.product_id,
                                                condition.product_variant_id
                                            )
                                        "
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- specific collections condition items -->
                        <div
                            class="mt-2"
                            v-if="
                                form.display_for_type === 'specific-collections'
                            "
                        >
                            <div class="flex flex-col">
                                <div
                                    class="flex items-center justify-between py-2 border-b border-gray-300"
                                    v-for="(condition,
                                    index) in form.conditions"
                                    :key="index"
                                >
                                    <div
                                        class="flex items-center text-sm text-gray-800"
                                        v-if="
                                            condition.type ===
                                                'specific-collections'
                                        "
                                    >
                                        <div>
                                            {{
                                                getCollectionTitle(
                                                    condition.collection_id
                                                )
                                            }}
                                        </div>
                                        <div
                                            class="flex items-center justify-center text-gray-800"
                                            @click="
                                                removeCollectionCondition(
                                                    condition.collection_id
                                                )
                                            "
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <choose-display-for-products-modal
                        :upsell.sync="form"
                        :show.sync="show_choose_products"
                        :query.sync="query_str"
                        v-if="
                            Object.keys(form).length > 0 && show_choose_products
                        "
                        ref="chooseDisplayForProductsModal"
                    ></choose-display-for-products-modal>

                    <choose-display-for-collections-modal
                        :upsell.sync="form"
                        :show.sync="show_choose_collections"
                        :query.sync="query_str"
                        v-if="
                            Object.keys(form).length > 0 &&
                                show_choose_collections
                        "
                        ref="chooseDisplayForCollectionsModal"
                    ></choose-display-for-collections-modal>

                    <!-- Triggered on -->
                    <div class="flex flex-col p-6 mt-4 bg-white rounded shadow">
                        <div>
                            <legend class="text-base font-medium text-gray-900">
                                Triggered on
                            </legend>
                        </div>
                        <!-- choose Triggered type -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <input
                                    id="add-to-cart"
                                    name="add-to-cart"
                                    v-model="form.triggered_on"
                                    value="add-to-cart"
                                    type="radio"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="add-to-cart"
                                    class="block ml-3 text-sm text-gray-700"
                                    >Add to cart button click</label
                                >
                            </div>
                            <!-- <div class="flex items-center">
                <input
                  id="checkout"
                  name="checkout"
                  value="checkout"
                  type="radio"
                  disabled
                  class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                />
                <label
                  for="checkout"
                  class="block ml-3 text-sm italic text-gray-500"
                >Checkout button click (upcoming)</label>
              </div>-->
                        </div>
                    </div>

                    <!-- additional settings -->
                    <div class="flex flex-col p-6 mt-4 bg-white rounded shadow">
                        <div class="flex items-center">
                            <div
                                class="text-gray-800 transition-all transform"
                                id="additional-icon"
                                name="additional-icon"
                                @click="showAdditionalSettings"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </div>
                            <label
                                for="additional-icon"
                                class="ml-3 text-sm text-gray-800 select-none"
                                @click="showAdditionalSettings"
                                >Additional settings</label
                            >
                        </div>
                        <div
                            class="flex flex-col mt-4 transition-all ease-linear transform"
                            v-if="show_additional_settings"
                        >
                            <div class="text-sm font-medium text-gray-800">
                                Upsell position
                            </div>
                            <div class="w-full mt-1">
                                <select
                                    id="upsell-order"
                                    name="upsell-order"
                                    autocomplete="upsell-order"
                                    v-model="form.order"
                                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                    <option value="3">3rd</option>
                                    <option value="4">4th</option>
                                    <option value="5">5th</option>
                                    <option value="6">6th</option>
                                    <option value="7">7th</option>
                                    <option value="8">8th</option>
                                    <option value="9">9th</option>
                                    <option value="10">10th</option>
                                </select>
                            </div>
                            <div class="mt-1 text-xs font-light text-gray-500">
                                Upsell with the lowest number will be displayed
                                as the first one.
                            </div>
                            <div class="flex items-center mt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="remove-parent-product-when-upsell-product-is-added"
                                            name="remove-parent-product-when-upsell-product-is-added"
                                            type="checkbox"
                                            @change="showForm"
                                            v-model="
                                                form.remove_parent_product_when_upsell_product_is_added
                                            "
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        />
                                    </div>
                                    <label
                                        for="remove-parent-product-when-upsell-product-is-added"
                                        class="ml-3 text-sm text-gray-700"
                                        >True upsell(upgrade): Remove parent
                                        product when upsell product is
                                        added</label
                                    >
                                </div>
                            </div>
                            <!-- <div class="flex items-center mt-2">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input
                      id="remove-upsell-product-when-parent-product-is-removed"
                      name="remove-upsell-product-when-parent-product-is-removed"
                      type="checkbox"
                      @change="showForm"
                      v-model="form.remove_upsell_product_when_parent_product_is_removed"
                      class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                  </div>
                  <div
                    class="ml-3 text-sm text-gray-700"
                  >Remove upsell product when parent product is removed</div>
                </div>
              </div>-->
                            <div class="flex items-center mt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="show-note-field"
                                            name="show-note-field"
                                            type="checkbox"
                                            @change="showForm"
                                            v-model="form.show_note_field"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        />
                                    </div>
                                    <label
                                        for="show-note-field"
                                        class="ml-3 text-sm text-gray-700"
                                        >Display custom note field</label
                                    >
                                </div>
                            </div>
                            <div class="flex items-center mt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="hide-upsell-product-already-in-cart"
                                            name="hide-upsell-product-already-in-cart"
                                            type="checkbox"
                                            @change="showForm"
                                            v-model="
                                                form.hide_upsell_product_already_in_cart
                                            "
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        />
                                    </div>
                                    <label
                                        for="hide-upsell-product-already-in-cart"
                                        class="ml-3 text-sm text-gray-700"
                                        >Don't display if the product is already
                                        in the cart</label
                                    >
                                </div>
                            </div>

                            <div class="mt-6 text-sm font-medium text-gray-800">
                                Quantity Options
                            </div>
                            <div class="flex items-center mt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="match-parent-quantity"
                                            name="match-parent-quantity"
                                            type="checkbox"
                                            @change="showForm"
                                            v-model="form.match_parent_quantity"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        />
                                    </div>
                                    <label
                                        for="match-parent-quantity"
                                        class="ml-3 text-sm text-gray-700"
                                        >Match parent product's quantity</label
                                    >
                                </div>
                            </div>
                            <div class="flex items-center mt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="enable-quantity-selector"
                                            name="enable-quantity-selector"
                                            type="checkbox"
                                            @change="showForm"
                                            v-model="
                                                form.enable_quantity_selector
                                            "
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        />
                                    </div>
                                    <label
                                        for="enable-quantity-selector"
                                        class="ml-3 text-sm text-gray-700"
                                        >Enable quantity selector on the
                                        pop-up</label
                                    >
                                </div>
                            </div>

                            <div class="mt-6 text-sm font-medium text-gray-800">
                                Displays for specified price range
                            </div>
                            <div class="flex items-center mt-2">
                                <input
                                    id="price-type-all"
                                    name="price-type-all"
                                    type="radio"
                                    v-model="form.price_type"
                                    value="all"
                                    @change="showForm"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="price-type-all"
                                    class="block ml-3 text-sm text-gray-700"
                                    >All product prices</label
                                >
                            </div>
                            <div class="flex items-center mt-2">
                                <input
                                    id="price-type-range"
                                    name="price-type-range"
                                    type="radio"
                                    v-model="form.price_type"
                                    value="range"
                                    @change="showForm"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                />
                                <label
                                    for="price-type-range"
                                    class="block ml-3 text-sm text-gray-700"
                                    >Products in specific price range</label
                                >
                            </div>

                            <div
                                class="flex mt-6 space-x-4"
                                v-if="form.price_type === 'range'"
                            >
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-700">
                                        Minimum price
                                    </div>
                                    <div class="flex mt-1 rounded-md shadow-sm">
                                        <input
                                            type="text"
                                            min="0"
                                            name="minimum-price"
                                            id="minimum-price"
                                            @change="onMinimumPriceInput"
                                            v-model="form.minimum_price"
                                            class="flex-1 block w-full border-gray-300 rounded-none focus:ring-indigo-500 focus:border-indigo-500 rounded-l-md sm:text-sm"
                                            placeholder="0.00"
                                        />
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-500 border border-l-0 border-gray-300 rounded-r-md bg-gray-50"
                                            >{{ user.currency }}</span
                                        >
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="text-sm text-gray-700">
                                        Maximum price
                                    </div>
                                    <div class="flex mt-1 rounded-md shadow-sm">
                                        <input
                                            type="text"
                                            min="0"
                                            name="maximum-price"
                                            id="maximum-price"
                                            @change="onMaximumPriceInput"
                                            v-model="form.maximum_price"
                                            class="flex-1 block w-full border-gray-300 rounded-none focus:ring-indigo-500 focus:border-indigo-500 rounded-l-md sm:text-sm"
                                            placeholder="No limit"
                                        />
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-500 border border-l-0 border-gray-300 rounded-r-md bg-gray-50"
                                            >{{ user.currency }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="form.price_type === 'range'"
                                class="mt-1 text-xs font-light text-gray-500"
                            >
                                Upsell only shows when product in specified
                                price range is added to the cart.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- for preview -->
                <div class="col-span-1 lg:col-span-2" style="height:3000px">
                    <div
                        class="sticky flex flex-col p-6 bg-white rounded shadow top-4"
                    >
                        <div class="font-medium text-gray-800">Preview</div>
                        <div
                            class="flex justify-center mt-4 font-light text-gray-800"
                        >
                            Get extras for your product
                        </div>
                        <product-upsell-preview
                            :upsell="form"
                            class="mt-4"
                            v-if="
                                Object.keys(form).length > 0 &&
                                    form.type == 'product'
                            "
                            ref="pur"
                        ></product-upsell-preview>

                        <smart-auto-upsell-preview
                            :upsell="form"
                            class="mt-4"
                            v-if="
                                Object.keys(form).length > 0 &&
                                    form.type == 'smart-auto'
                            "
                            ref="saur"
                        ></smart-auto-upsell-preview>

                        <custom-service-upsell-preview
                            :upsell="form"
                            class="mt-4"
                            v-if="
                                Object.keys(form).length > 0 &&
                                    form.type == 'custom-service'
                            "
                            ref="csur"
                        ></custom-service-upsell-preview>
                    </div>
                </div>
            </div>
        </div>
        <!-- end content -->

        <!-- footer -->
        <footer
            class="fixed bottom-0 left-0 w-full h-16 border-t-2 border-gray-300 bg-gray-50"
        >
            <div class="flex justify-between px-4 py-3 bg-gray-50">
                <router-link
                    to="/"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-md cursor-pointer hover:bg-gray-900"
                    aria-current="page"
                    >Back</router-link
                >
                <div class="flex items-center justify-end">
                    <div
                        v-if="updated_success"
                        class="items-center justify-center flex-1 mr-4 text-sm text-gray-700"
                    >
                        updated!
                    </div>
                    <div
                        @click.prevent="updateUpsell"
                        v-if="upsell.id"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-700 border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600"
                    >
                        Save
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";
import ChooseUpsellProduct from "./ChooseUpsellProduct.vue";
import ChooseDisplayForProductsModal from "./ChooseDisplayForProductsModal.vue";
import ChooseDisplayForCollectionsModal from "./ChooseDisplayForCollectionsModal.vue";
import ProductUpsellPreview from "./ProductUpsellPreview.vue";
import SmartAutoUpsellPreview from "./SmartAutoUpsellPreview.vue";
import CustomServiceUpsellPreview from "./CustomServiceUpsellPreview.vue";
import Icons from "./Icons.vue";
export default {
    components: {
        ChooseUpsellProduct,
        ChooseDisplayForProductsModal,
        ChooseDisplayForCollectionsModal,
        ProductUpsellPreview,
        SmartAutoUpsellPreview,
        CustomServiceUpsellPreview,
        Icons
    },
    data() {
        return {
            id: 0,
            form: {},
            upsell: {},
            show_choose_products: false,
            show_choose_collections: false,
            show_additional_settings: false,
            updated_success: false,
            query_str: "",
            updating: false
        };
    },
    computed: {
        ...mapState({
            user: state => state.upsellrock.user,
            currencies: state => state.upsellrock.currencies,
            upsells: state => state.upsellrock.upsells,
            local_products: state => state.upsellrock.local_products,
            local_collections: state => state.upsellrock.local_collections
        })
    },
    watch: {
        form: {
            handler() {
                if (this.$refs.pur) {
                    this.$refs.pur.upsellUpdated();
                }
            },
            deep: true
        }
    },
    mounted() {
        console.log("edit upsell mounted");
        var ID = this.$route.params.id;
        if (ID) {
            this.id = ID;
            this.getUpsell();
        }
    },
    methods: {
        onPriceInput() {
            if (isNaN(Number.parseFloat(this.form.price))) {
                this.form.price = 0;
            } else {
                this.form.price = Math.abs(
                    Number.parseFloat(this.form.price)
                ).toFixed(2);
            }
        },
        onMinimumPriceInput() {
            if (isNaN(Number.parseFloat(this.form.minimum_price))) {
                this.form.minimum_price = 0;
            } else {
                this.form.minimum_price = Math.abs(
                    Number.parseFloat(this.form.minimum_price)
                ).toFixed(2);
            }
        },
        onMaximumPriceInput() {
            if (isNaN(Number.parseFloat(this.form.maximum_price))) {
                this.form.maximum_price = 0;
            } else {
                this.form.maximum_price = Math.abs(
                    Number.parseFloat(this.form.maximum_price)
                ).toFixed(2);
            }
        },
        disableUpsell() {
            this.updating = true;
            axios.put("/spa/disable_upsell", { id: this.id }).then(res => {
                this.form.active = false;
                this.updating = false;
            });
        },
        enableUpsell() {
            this.updating = true;
            axios.put("/spa/enable_upsell", { id: this.id }).then(res => {
                this.form.active = true;
                this.updating = false;
            });
        },
        onQueryStrChange(e) {
            this.query_str = e.target.value;
            if (e.target.value.length > 2) {
                this.showChoose();
            }
        },
        updateUpsell() {
            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                canCancel: true,
                onCancel: this.onCancel
            });
            console.log("updateUpsell");
            axios
                .put("/spa/upsell/" + this.id, this.form)
                .then(res => {
                    loader.hide();
                    console.log(res.data);
                    this.form = res.data;
                    this.upsell = res.data;
                    if (this.form.shopify_product_variants === null) {
                        this.form.shopify_product_variants = [];
                    }
                    if (this.form.shopify_product_id === null) {
                        this.form.shopify_product_id = [];
                    } else {
                        this.form.shopify_product_id = [
                            this.form.shopify_product_id
                        ];
                    }
                    this.updated_success = true;
                    setTimeout(() => {
                        this.updated_success = false;
                        this.$router.go(-1);
                    }, 1000);
                })
                .catch(error => {
                    alert(JSON.stringify(error));
                    loader.hide();
                });
        },
        gotoCCA() {
            window
                .open(
                    "https://apps.shopify.com/currency-converter-ant",
                    "_blank"
                )
                .focus();
        },
        getUpsell() {
            axios.get("/spa/upsell/" + this.id).then(res => {
                this.form = res.data;
                this.upsell = res.data;
                console.log("getUpsell");
                console.log(res.data);
                if (this.form.shopify_product_variants === null) {
                    this.form.shopify_product_variants = [];
                } else {
                    this.form.shopify_product_variants = JSON.parse(
                        this.form.shopify_product_variants
                    );
                }
                if (this.form.shopify_product_id === null) {
                    this.form.shopify_product_id = [];
                } else {
                    this.form.shopify_product_id = [
                        this.form.shopify_product_id
                    ];
                }

                if (
                    this.form.type === "custom-service" &&
                    this.form.price > 0
                ) {
                    this.form.price = Number(this.form.price / 100).toFixed(2);
                }
                console.log("getUpsell");
            });
        },
        showChoose() {
            if (this.form.display_for_type === "specific-products") {
                this.show_choose_products = true;
            }
            if (this.form.display_for_type === "specific-collections") {
                this.show_choose_collections = true;
            }
        },
        getProduct(product_id) {
            var p = this.local_products.find(
                product => product.id === product_id
            );
            if (p) {
                return p;
            }
            return "";
        },
        getProductImage(product_id) {
            var p = this.local_products.find(
                product => product.id === product_id
            );
            if (p && p.image) {
                return p.image.src;
            }
            return "";
        },
        getProductTitle(product_id) {
            var p = this.local_products.find(
                product => product.id === product_id
            );
            if (p) {
                return p.title;
            }
        },
        getVariantOptions(product_id, variant_id) {
            var p = this.local_products.find(
                product => product.id === product_id
            );
            if (p) {
                var v = p.variants.find(variant => variant.id === variant_id);
                if (v) {
                    var options = v.option1;
                    if (v.option2) {
                        options += "|" + v.option2;
                    }
                    if (v.option3) {
                        options += "|" + v.option3;
                    }
                    return options;
                }
            }
        },
        displayForTypeChanged() {
            console.log("displayForTypeChanged");
            this.form.conditions = [];
        },
        removeCondition(product_id, variant_id) {
            console.log(
                "removeCondition:p_id=" + product_id + " v_id:" + variant_id
            );
            var condition = this.form.conditions.find(c => {
                if (
                    c.product_id === product_id &&
                    c.product_variant_id === variant_id
                )
                    return c;
            });
            console.log("find condition:" + JSON.stringify(condition));
            var index = this.form.conditions.indexOf(condition);
            console.log("find index:" + index);
            if (index > -1) {
                this.form.conditions.splice(index, 1);
            }
            this.$refs.chooseDisplayForProductsModal.updateConditions();
        },
        getCollectionTitle(collection_id) {
            var c = this.local_collections.find(
                collection => collection.id === collection_id
            );
            if (c) {
                return c.title;
            }
        },
        removeCollectionCondition(collection_id) {
            console.log("removeCollectionsCondition:c_id=" + collection_id);
            var condition = this.form.conditions.find(c => {
                if (c.collection_id === collection_id) return c;
            });
            console.log("find condition:" + JSON.stringify(condition));
            var index = this.form.conditions.indexOf(condition);
            console.log("find index:" + index);
            if (index > -1) {
                this.form.conditions.splice(index, 1);
            }
            this.$refs.chooseDisplayForCollectionsModal.updateConditions();
        },
        showAdditionalSettings() {
            console.log("showAdditionalSettings");
            var icon = document.getElementById("additional-icon");
            if (icon.classList.contains("rotate-90")) {
                icon.classList.remove("rotate-90");
                this.show_additional_settings = false;
            } else {
                icon.classList.add("rotate-90");
                this.show_additional_settings = true;
            }
        },
        showForm() {
            console.log(this.form);
        }
    }
};
</script>

<style scoped>
input:disabled {
    background: #f0f0f0;
    border: 1px solid #e1e1e1;
}
</style>
