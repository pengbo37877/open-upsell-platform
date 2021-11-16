<template>
    <div class="bg-white rounded shadow flex">
        <div class="flex flex-1 flex-col px-8 py-6">
            <div class="font-medium text-gray-900">{{ title }}</div>
            <div class="flex-1 mt-4 mb-10 text-sm text-gray-900">
                {{ description }}
                <a :href="learn.link" target="_blank" class="text-blue-500"
                    >({{ learn.title }})</a
                >
            </div>
            <div
                v-if="Object.keys(smart_auto_upsell).length === 0 && !creating"
                @click="createUpsell"
                class="w-20 h-10 flex justify-center items-center text-sm font-light text-white bg-green-700 border border-green-800 rounded-sm shadow-sm cursor-pointer hover:bg-green-800"
            >
                Select
            </div>
            <div
                v-if="Object.keys(smart_auto_upsell).length === 0 && creating"
                class="flex items-center justify-center w-20 h-10 text-sm font-light text-white bg-green-800 border border-green-800 rounded-sm shadow-sm cursor-pointer"
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
            <div
                v-if="Object.keys(smart_auto_upsell).length > 0"
                class="w-20 h-10 flex justify-center items-center text-sm font-light text-white bg-green-400 border border-green-400 rounded-sm shadow-sm cursor-pointer"
            >
                Select
            </div>
            <div
                v-if="Object.keys(smart_auto_upsell).length > 0"
                class="mt-1 text-sm font-light text-gray-800"
            >
                Already exists
                <router-link
                    class="text-blue-500"
                    :to="`/upsell/${smart_auto_upsell.id}/edit`"
                    >(edit)</router-link
                >
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
            <div class="z-10 pl-12 mt-4">
                <div class="flex">
                    <div
                        class="flex flex-col justify-start bg-white border border-gray-300 rounded image"
                    >
                        <svg
                            class="w-12 h-12 opacity-75"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                            />
                        </svg>
                    </div>
                    <div class="flex flex-col ml-3">
                        <div class="text-xs font-medium text-gray-800">
                            {{ item.title }}
                        </div>
                        <div class="flex mt-1">
                            <div class="text-xs font-light text-gray-400">
                                {{ item.description }}
                            </div>
                        </div>
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
            title: "Smart Auto-Upsell",
            description:
                "Automatically select products for upsell based on the Shopify product recommendation API",
            learn: {
                title: "learn more",
                link:
                    "/what-are-smart-auto-upsells-and-how-to-use-them-within-ant-upsell-rock"
            },
            item: {
                title: "Most relevant product",
                description: "You may need this one",
                icon: ""
            },
            smart_auto_upsell: {},
            interval: null,
            creating: false
        };
    },
    computed: {
        ...mapState({
            user: state => state.upsellrock.user,
            shop: state => state.upsellrock.shop,
            currencies: state => state.upsellrock.currencies,
            upsells: state => state.upsellrock.upsells
        })
    },
    mounted() {
        console.log("Smart Auto Upsell mounted.");
        this.interval = setInterval(() => {
            if (window.sessionToken) {
                this.getSmartAutoUpsell();
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
        getSmartAutoUpsell() {
            axios.get("/spa/smart_auto_upsell").then(res => {
                console.log(res.data);
                this.smart_auto_upsell = res.data;
            });
        },
        createUpsell() {
            this.creating = true;
            axios.get("/spa/create_upsell?type=smart-auto").then(res => {
                this.creating = false;
                console.log(res.data);
                this.smart_auto_upsell = res.data;
                this.$router.push("/upsell/" + res.data.id + "/edit");
            });
        }
    }
};
</script>
