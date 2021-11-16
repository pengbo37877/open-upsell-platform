<template>
    <div class="bg-white rounded shadow flex">
        <div class="flex flex-1 flex-col px-8 py-6">
            <div class="font-medium text-gray-900">{{ title }}</div>
            <div class="flex-1 mt-4 mb-10 text-sm text-gray-900">
                {{ description }}
            </div>
            <div
                @click="createUpsell"
                v-if="!creating"
                class="w-20 h-10 flex justify-center items-center text-sm font-light text-white bg-green-700 border border-green-800 rounded-sm shadow-sm cursor-pointer hover:bg-green-800"
            >
                Select
            </div>
            <div
                v-else
                class="flex items-center justify-center w-20 h-10 text-sm font-light text-center text-white bg-green-800 border border-green-800 rounded-sm shadow-sm cursor-pointer"
            >
                <svg
                    class="animate-spin h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
            </div>
        </div>
        <div class="flex flex-1 flex-col col-span-2 px-8 py-6">
            <div class="font-medium text-gray-500">Preview</div>
            <div class="relative flex p-4 mt-4 bg-gray-50">
                <div class="w-10 h-10 bg-gray-200 rounded"></div>
                <div class="flex flex-col justify-center ml-3 space-y-2">
                    <div class="w-40 h-2 bg-gray-200 rounded"></div>
                    <div class="w-40 h-2 pr-5">
                        <div class="h-2 bg-gray-200 rounded"></div>
                    </div>
                </div>
                <div
                    class="absolute w-5 h-20 border-b border-l border-gray-200 rounded left-9 top-8"
                ></div>
            </div>
            <div class="z-10 pl-12 mt-4" v-if="Object.keys(product).length > 0">
                <div class="flex">
                    <div class="flex flex-col justify-start bg-white image">
                        <img
                            :src="product.image.src"
                            class="w-12 h-12"
                            alt
                            v-if="product.image"
                        />
                        <div v-else>
                            <svg
                                t="1626758737081"
                                class="w-12 h-12 icon"
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
                    <div class="flex flex-col ml-3">
                        <div class="text-xs font-medium text-gray-800">
                            {{ product.title }}
                        </div>
                        <div class="flex">
                            <div
                                class="text-xs font-light text-gray-400 line-through"
                            >
                                {{ getCurrencySymbol(shop.currency)
                                }}{{ product.variants[0].price }}
                            </div>
                            <div class="ml-2 text-xs font-light text-gray-700">
                                {{ getCurrencySymbol(shop.currency)
                                }}{{
                                    Number(
                                        product.variants[0].price - 2
                                    ).toFixed(2)
                                }}
                            </div>
                        </div>
                        <div class="mt-1 text-xs font-light text-gray-400">
                            Unique offer!
                        </div>
                        <select
                            id="product-variants"
                            name="product-variants"
                            autocomplete="product-variants"
                            v-if="product.variants.length > 1"
                            class="block w-40 px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                            <option
                                v-for="(variant, index) in product.variants"
                                :key="index"
                                >{{ variant.option1 }}</option
                            >
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";
export default {
    data() {
        return {
            title: "Product Upsell",
            description: "Choose products from your store to increase sales",
            product: {},
            interval: null,
            creating: false
        };
    },
    computed: {
        ...mapState({
            user: state => state.upsellrock.user,
            shop: state => state.upsellrock.shop,
            currencies: state => state.upsellrock.currencies,
            local_products: state => state.upsellrock.local_products
        })
    },
    mounted() {
        console.log("Product Upsell mounted.");
        this.interval = setInterval(() => {
            if (window.sessionToken != null) {
                this.getRandomProduct();
                clearInterval(this.interval);
            }
        }, 100);
    },
    methods: {
        getCurrencySymbol(currency) {
            var cur = this.currencies.find(c => c.currency === currency);
            if (cur) {
                return cur.currency_symbol;
            }
        },
        getRandomProduct() {
            if (this.local_products.length > 0) {
                this.product = this.local_products.find(
                    p => p.product_type === ""
                );
            }
            axios.get("/spa/products").then(res => {
                // this.types["Product Upsell"].product = res.data;
                console.log(res.data);
                this.product = res.data.find(p => p.product_type === "");
            });
        },
        createUpsell() {
            this.creating = true;
            axios.get("/spa/create_upsell?type=product").then(res => {
                this.creating = false;
                console.log(res.data);
                this.$router.push("/upsell/" + res.data.id + "/edit");
            });
        }
    }
};
</script>
