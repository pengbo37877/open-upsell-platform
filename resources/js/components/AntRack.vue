<template>
    <div
        id="ant-modal-panel"
        class="relative rounded ease-out duration-300 opacity-0 translate-y-[16px] sm:translate-y-0 sm:scale-95 inline-block align-bottom bg-white text-left overflow-hidden shadow-xl transform transition-all sm:my-[32px] sm:align-middle sm:max-w-[896px] w-full"
    >
        <div class="absolute top-[12px] right-[12px] ant-cancel-btn cursor-pointer">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-[24px] w-[24px] text-gray-500 hover:text-gray-800"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </div>
        <div class="bg-white pt-[20px]">
            <div class="sm:flex sm:items-start">
                <div
                    class="mt-[12px] border-b border-gray-200 flex-1 sm:mt-0 sm:text-left"
                >
                    <div
                        class="px-[16px] pb-[32px] sm:pb-[24px] sm:px-[24px] text-[20px] leading-[24px] text-center font-bold text-gray-900"
                        id="modal-title"
                    >
                        {{ setting.title }}
                    </div>
                </div>
            </div>
            <div class="overflow-y-scroll h-auto pb-[16px]" style="max-height:45vh">
                <div
                    class="flex items-center py-[16px] px-[16px] sm:px-[24px]"
                    :style="{
                        backgroundColor: setting.primary_color + '05'
                    }"
                >
                    <div class="relative h-[56px] w-[56px] rounded bg-white shadow" v-if="Object.keys(currentProduct).length > 0">
                        <img
                            class="object-cover h-[56px] w-[56px] overflow-hidden"
                            :src="currentProduct.featured_image"
                            alt=""
                        />
                        <span class="flex h-[16px] w-[16px] absolute -right-[8px] -top-[8px]">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"
                            ></span>
                            <span
                                class="relative flex justify-center items-center rounded-full h-[16px] w-[16px] bg-green-600 text-white "
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-[12px] w-[12px]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="ml-[16px] flex flex-col justify-evenly flex-1" v-if="Object.keys(currentProduct).length > 0">
                        <div class="text-gray-900 font-bold">
                            {{ currentProduct.title }}
                        </div>
                        <div class="text-gray-800 flex items-center">
                            <div
                                class="text-gray-500 line-through mr-[4px] money anti-money"
                                :data-amount="
                                    currentVariant.compare_at_price / 100
                                "
                                :data-currency="currency.currency"
                                v-if="currentProductComparePrice"
                            >
                                {{ currentProductComparePrice }}
                            </div>
                            <div
                                class="money anti-money"
                                :data-amount="currentVariant.price / 100"
                                :data-currency="currency.currency"
                            >
                                {{ currentProductPrice }}
                            </div>
                        </div>
                    </div>
                    <div class="ml-[8px] font-mono font-bold">
                        <!-- 这里是当前产品价格 -->
                    </div>
                </div>
                <div v-if="upsells.length>0">
                    <div v-for="(upsell, index) in upsells" :key="index">
                        <ant-rack-upsell
                            :upsell="upsell"
                            v-if="
                                upsell.type == 'product' ||
                                    upsell.type == 'custom-service'
                            "
                        ></ant-rack-upsell>
                        <ant-rack-smart
                            :upsell="upsell"
                            v-if="upsell.type == 'smart-auto'"
                        ></ant-rack-smart>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="bg-gray-50 px-[16px] py-[12px] sm:px-[24px] sm:flex sm:flex-row-reverse sticky bottom-0 left-0 w-full"
        >
            <button
                type="button"
                id="ant-atc-btn"
                :style="{ backgroundColor: setting.primary_color }"
                @click="addToCart"
                v-if="!adding"
                class="ant-act-btn w-full flex justify-center items-center  border border-transparent shadow pl-[16px] pr-[8px] py-[8px] leading-[24px] text-base font-bold text-white sm:ml-[12px] sm:w-auto sm:text-sm"
            >
                <div class="leading-[24px] mr-[4px]">{{ setting.proceed_to_cart }}</div>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-[20px] w-[20px]"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
            <button
                type="button"
                :style="{ backgroundColor: setting.primary_color }"
                v-if="adding"
                class="w-full inline-flex justify-center items-center border border-transparent shadow pl-[16px] pr-[8px] py-[8px] leading-[24px] text-base font-bold text-white sm:ml-[12px] sm:w-auto sm:text-sm"
            >
                <div class="leading-6 mr-1">{{ setting.proceed_to_cart }}</div>
                <svg
                    class="animate-spin h-[20px] w-[20px] text-white"
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
            </button>
            <button
                id="ant-cancel-btn"
                type="button"
                class="ant-cancel-btn mt-[12px] w-full rounded-full inline-flex justify-center px-[16px] py-[8px] text-base text-gray-400 sm:mt-0 sm:ml-[12px] sm:w-auto sm:text-sm sm:hidden"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-[24px] w-[24px]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
import AntRackUpsell from "./AntRackUpsell.vue";
import AntRackSmart from "./AntRackSmart.vue";
export default {
    components: {
        AntRackUpsell,
        AntRackSmart
    },
    data() {
        return {
            url: "",
            shop: "",
            setting: {},
            currency: {},
            cart: {},
            upsells: [],
            currencies: [],
            currentProduct: {},
            currentVariant: {},
            adding: false
        };
    },
    computed: {
        currentProductPrice() {
            // get variant_id
            let addToCartForm = document.querySelector(
                'form[action="/cart/add"]'
            );
            let formData = new FormData(addToCartForm);
            let variant_id = 0;
            formData.forEach((v, k) => {
                if (k == "id") {
                    variant_id = v;
                }
            });
            this.currentVariant = this.currentProduct.variants.find(
                v => v.id == variant_id
            );
            this.currency = this.currencies.find(
                c => c.currency == this.cart.currency
            );
            return (
                this.currency.currency_symbol +
                Number(this.currentVariant.price / 100).toFixed(2)
            );
        },
        currentProductComparePrice() {
            // get variant_id
            let addToCartForm = document.querySelector(
                'form[action="/cart/add"]'
            );
            let formData = new FormData(addToCartForm);
            let variant_id = 0;
            formData.forEach((v, k) => {
                if (k == "id") {
                    variant_id = v;
                }
            });
            this.currentVariant = this.currentProduct.variants.find(
                v => v.id == variant_id
            );
            if (!this.currentVariant.compare_at_price) {
                return null;
            }
            if (
                this.currentVariant.price ==
                this.currentVariant.compare_at_price
            ) {
                return null;
            }
            this.currency = this.currencies.find(
                c => c.currency == this.cart.currency
            );
            return (
                this.currency.currency_symbol +
                Number(this.currentVariant.compare_at_price / 100).toFixed(2)
            );
        }
    },
    mounted() {
        console.log("AntRack mounted.");
        this.url = document.getElementById("url").value;
        this.shop = document.getElementById("shop").value;
        this.setting = JSON.parse(document.getElementById("setting").innerText);
        this.currency = JSON.parse(
            document.getElementById("currency").innerText
        );
        this.currencies = JSON.parse(
            document.getElementById("currencies").innerText
        );
        this.cart = JSON.parse(document.getElementById("cart").innerText);
        this.upsells = JSON.parse(document.getElementById("upsells").innerText);
        if (this.upsells.length == 0) {
            resetATCs();
        }
        this.currentProduct = JSON.parse(
            document.getElementById("current-product").innerText
        );
        // get variant_id
        let addToCartForm = document.querySelector(
            'form[action="/cart/add"]'
        );
        let formData = new FormData(addToCartForm);
        let variant_id = 0;
        formData.forEach((v, k) => {
            if (k == "id") {
                variant_id = v;
            }
        });
        this.currentVariant = this.currentProduct.variants.find(
            v => v.id == variant_id
        );
        this.currency = this.currencies.find(
            c => c.currency == this.cart.currency
        );
        console.log("upsells", this.upsells);
    },
    methods: {
        resetATCs() {
            var newATCs = document.querySelectorAll(
                ".ant-rack.ant-rack-cloned"
            );
            newATCs.forEach(atc => {
                atc.style.display = "none";
            });

            var originalATCs = document.querySelectorAll(
                ".ant-rack.ant-rack-original"
            );
            originalATCs.forEach(atc => {
                atc.style.display = "";
            });
        },
        addCurrentVariantToCart() {
            let addToCartForm = document.querySelector(
                'form[action="/cart/add"]'
            );
            let formData = new FormData(addToCartForm);

            fetch("/cart/add.js", {
                method: "POST",
                body: formData
            });
        },
        addToCart() {
            var antBgWrap = document.getElementById("ant-bg-wrap");
            var antBgOverlay = document.getElementById("ant-bg-overlay");
            var antModalPanel = document.getElementById("ant-modal-panel");
            antModalPanel.classList.remove("ease-out");
            antModalPanel.classList.remove("duration-300");
            antModalPanel.classList.remove("opacity-100");
            antModalPanel.classList.remove("translate-y-0");
            antModalPanel.classList.remove("sm:scale-100");
            antModalPanel.classList.add("ease-in");
            antModalPanel.classList.add("duration-200");
            antModalPanel.classList.add("opacity-0");
            antModalPanel.classList.add("translate-y-4");
            antModalPanel.classList.add("sm:translate-y-0");
            antModalPanel.classList.add("sm:scale-95");

            antBgOverlay.classList.remove("ease-out");
            antBgOverlay.classList.remove("duration-300");
            antBgOverlay.classList.remove("opacity-100");
            antBgOverlay.classList.add("ease-in");
            antBgOverlay.classList.add("duration-200");
            antBgOverlay.classList.add("opacity-0");
            setTimeout(() => {
                antBgWrap.classList.add("hidden");
                console.log("outside cancel");

                // dell with close action
                if (this.setting.continue_action == "") {
                    // add current product to cart
                    var antRackOriginal = document.querySelector(
                        ".ant-rack.ant-rack-original"
                    );
                    if (antRackOriginal) {
                        antRackOriginal.click();
                    }
                } else if (this.setting.continue_action == "close") {
                    this.addCurrentVariantToCart();
                } else if (this.setting.continue_action == "redirect_to_cart") {
                    this.addCurrentVariantToCart();
                    setTimeout(() => {
                        location.href = "/cart";
                    }, 200);
                } else if (
                    this.setting.continue_action == "redirect_to_checkout"
                ) {
                    this.addCurrentVariantToCart();
                    setTimeout(() => {
                        location.href = "/checkout";
                    }, 200);
                }
            }, 200);
        }
    }
};
</script>
