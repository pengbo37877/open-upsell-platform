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
        <div
            class="max-w-6xl py-6 mx-auto sm:px-6 lg:px-8"
            v-if="upsells.length === 0 || show_type_list"
        >
            <div class="flex justify-start">
                <div
                    class="flex items-center text-gray-500 cursor-pointer"
                    v-if="show_type_list"
                    @click="show_type_list = false"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 17l-5-5m0 0l5-5m-5 5h12"
                        />
                    </svg>
                </div>
                <div
                    :class="
                        `${
                            show_type_list ? 'ml-4' : ''
                        } text-xl text-gray-900 text-center font-medium py-4`
                    "
                >
                    Fill Up Your Ant Rack
                </div>
            </div>
            <instructor></instructor>
            <div class="flex flex-col space-y-2 mt-4">
                <product-upsell></product-upsell>
                <smart-auto-upsell></smart-auto-upsell>
                <custom-service></custom-service>
            </div>
        </div>
        <div class="max-w-6xl py-6 mx-auto sm:px-6 lg:px-8" v-else>
            <upsell-rock-list></upsell-rock-list>
            <!-- new upsell -->
            <div class="flex items-center justify-center mt-4">
                <div
                    @click="show_type_list = true"
                    class="w-auto px-3 py-2 text-sm font-light text-center text-white bg-green-700 border border-green-800 rounded-sm shadow-sm cursor-pointer hover:bg-green-800"
                >
                    New Upsell
                </div>
            </div>
        </div>
        <!-- end content -->
    </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";
import ProductUpsell from "./ProductUpsell.vue";
import SmartAutoUpsell from "./SmartAutoUpsell.vue";
import CustomService from "./CustomService.vue";
import UpsellRockList from "./UpsellRockList.vue";
import Instructor from "./Instructor.vue";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
    components: {
        DatePicker,
        ProductUpsell,
        SmartAutoUpsell,
        CustomService,
        UpsellRockList,
        Instructor
    },
    data() {
        return {
            views_range: [],
            statistics: {},
            interval: null,
            loaded: false,
            show_type_list: false
        };
    },
    computed: {
        ...mapState({
            user: state => state.upsellrock.user,
            shop: state => state.upsellrock.shop,
            currencies: state => state.upsellrock.currencies,
            upsells: state => state.upsellrock.upsells,
            local_products: state => state.upsellrock.local_products
        })
    },
    mounted() {
        console.log("upsell mounted");
        // views range
        var today = new Date();
        this.views_range.unshift(today.getTime());
        today.setDate(today.getDate() - 30);
        this.views_range.unshift(today.getTime());

        console.log("token=" + window.sessionToken);
        this.interval = setInterval(() => {
            if (!this.loaded && window.sessionToken != null) {
                this.loaded = true;
                this.getStatistics();
                this.getUpsells();
                this.getUser();
                this.getShop();
                this.getCurrencies();
                this.getCustomCollections();
                this.getSmartCollections();
                this.getLocalProducts();
                this.getLocalCollections();
                this.updateSmartCollection();
                clearInterval(this.interval);
            }
        }, 100);
    },
    methods: {
        getStatistics() {
            axios
                .get(
                    "/spa/statistics?start=" +
                        this.views_range[0] +
                        "&end=" +
                        this.views_range[1]
                )
                .then(res => {
                    this.statistics = res.data;
                });
        },
        getUpsells() {
            axios.get("/spa/upsells").then(res => {
                this.$store.commit("upsellrock/SET_UPSELLS", {
                    upsells: res.data
                });
            });
        },
        getUser() {
            axios.get("/spa/user").then(res => {
                this.$store.commit("upsellrock/SET_USER", { user: res.data });
            });
        },
        getShop() {
            axios.get("/spa/shop").then(res => {
                console.log(res.data);
                this.$store.commit("upsellrock/SET_SHOP", { shop: res.data });
            });
        },
        getCurrencies() {
            axios.get("/spa/currencies").then(res => {
                this.$store.commit("upsellrock/SET_CURRENCIES", {
                    currencies: res.data
                });
            });
        },
        getCustomCollections() {
            axios.get("/spa/custom_collections").then(res => {
                // console.log("getCustomCollections");
                // console.log(res.data);
                this.$store.commit("upsellrock/SET_CUSTOM_COLLECTIONS", {
                    custom_collections: res.data
                });
            });
        },
        getSmartCollections() {
            axios.get("/spa/smart_collections").then(res => {
                // console.log("getSmartCollections");
                // console.log(res.data);
                this.$store.commit("upsellrock/SET_SMART_COLLECTIONS", {
                    smart_collections: res.data
                });
            });
        },
        updateSmartCollection() {
            axios.get("/spa/update_smart_collection").then(res => {
                // console.log("updateSmartCollection");
                // console.log(res.data);
            });
        },
        getLocalProducts() {
            axios.get("/spa/local_products").then(res => {
                console.log("getLocalProducts");
                console.log(res.data);
                this.$store.commit("upsellrock/SET_LOCAL_PRODUCTS", {
                    local_products: res.data
                });
            });
        },
        getLocalCollections() {
            axios.get("/spa/local_collections").then(res => {
                console.log("getLocalCollections");
                console.log(res.data);
                this.$store.commit("upsellrock/SET_LOCAL_COLLECTIONS", {
                    local_collections: res.data
                });
            });
        },
        gotoCCA() {
            window
                .open(
                    "https://apps.shopify.com/currency-converter-ant",
                    "_blank"
                )
                .focus();
        }
    }
};
</script>
