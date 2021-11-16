<template>
    <div
        class="grid grid-cols-1 sm:grid-cols-2 w-full border-t border-gray-100"
        style="margin:0"
        v-if="selectedVariant && Object.keys(selectedVariant).length > 0"
    >
        <div class="col-span-1 flex p-[16px] sm:p-[24px]">
            <div
                class="product-image relative w-[56px] h-[56px] mr-[16px] border border-gray-100"
                :class="{ 'p-[8px]': upsell.type == 'custom-service' }"
                :style="{ backgroundColor: setting.primary_color + '11' }"
            >
                <svg
                    style="opacity: 0; position: fixed; left: -1000px; top: -1000px;"
                >
                    <defs>
                        <filter id="turnIntoRed">
                            <feFlood
                                :flood-color="setting.primary_color"
                                flood-opacity="1"
                                result="color"
                            />
                            <feComposite
                                in="color"
                                in2="SourceGraphic"
                                operator="in"
                            />
                        </filter>
                    </defs>
                </svg>
                <img
                    class="w-[56px] h-[56px]"
                    :src="upsell.product.image"
                    alt=""
                    v-if="upsell.type == 'product'"
                />
                <img
                    class="w-[40px] h-[40px]"
                    style="filter: url('#turnIntoRed')"
                    :src="
                        `https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/${upsell.icon}.svg`
                    "
                    alt=""
                    v-if="upsell.type == 'custom-service'"
                />
                <div
                    v-if="upsell.upsell_quantity > 1"
                    class="absolute -top-[8px] -left-[8px] rounded-full w-[16px] h-[16px] bg-green-600 text-white flex justify-center items-center shadow text-[12px] leading-[16px] font-medium"
                >
                    {{ upsell.upsell_quantity }}
                </div>
                <span
                    class="flex h-[16px] w-[16px] absolute -right-[8px] -top-[8px]"
                    v-if="added"
                >
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

            <div class="flex flex-col justify-evenly flex-1">
                <div class="first-line flex">
                    <div class="text-gray-900 font-bold mr-[8px]">
                        {{ upsell.product.title }}
                    </div>
                    <div
                        class="text-gray-500 line-through mr-[8px] money anti-money"
                        :data-amount="selectedVariant.price"
                        :data-currency="currency.currency"
                        v-if="upsell.apply_discount"
                    >
                        {{ originalPrice }}
                    </div>
                    <div
                        class="text-gray-900 money anti-money"
                        :data-amount="noConversionDiscontedPrice"
                        :data-currency="currency.currency"
                        v-if="upsell.apply_discount"
                    >
                        {{ discountedPrice }}
                    </div>
                    <div
                        class="text-gray-900 money anti-money"
                        :data-amount="selectedVariant.price"
                        :data-currency="currency.currency"
                        v-if="!upsell.apply_discount"
                    >
                        {{ originalPrice }}
                    </div>
                </div>
                <div class="second-line flex my-[4px] text-gray-600 font-light">
                    {{ upsell.short_description + "" }}
                </div>
                <div class="mb-[4px] hidden sm:flex" v-if="upsell.show_note_field">
                    <input
                        type="text"
                        name="note"
                        id="note1"
                        autocomplete="given-name"
                        v-model="note"
                        :disabled="disableOptions"
                        class=" disabled:bg-gray-50 disabled:text-gray-600 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-sm"
                    />
                </div>
                <div
                    class="w-auto hidden sm:flex justify-start items-center mr-0 mb-0 sm:mr-[16px]"
                    v-if="variants.length > 1"
                >
                    <option-selector
                        :variants="variants"
                        :selected="selectedVariant"
                        :selectedChanged="selectedChanged"
                        :disableOptions="disableOptions"
                        ref="options2"
                    ></option-selector>
                </div>
            </div>
        </div>

        <div
            class="col-span-1 p-[16px] sm:p-[24px] pt-0 sm:pt-[16px] flex flex-col sm:flex-row justify-end items-center"
        >
            <div
                class="mb-[16px] flex w-full sm:hidden"
                v-if="upsell.show_note_field"
            >
                <input
                    type="text"
                    name="note"
                    id="note2"
                    autocomplete="given-name"
                    v-model="note"
                    :disabled="disableOptions"
                    class=" disabled:bg-gray-50 disabled:text-gray-600 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-[14px] sm:leading-[20px] border-gray-300 rounded-sm"
                />
            </div>
            <div
                class="w-full flex sm:hidden sm:w-auto justify-end items-center mr-0 mb-[16px] sm:mb-0 sm:mr-[16px]"
                v-if="variants.length > 1"
            >
                <option-selector
                    :variants="variants"
                    :selected="selectedVariant"
                    :selectedChanged="selectedChanged"
                    :disableOptions="disableOptions"
                    ref="options1"
                ></option-selector>
            </div>
            <div
                class="flex w-full sm:w-[112px] justify-end items-center mr-0 mb-[16px] sm:mb-0 sm:mr-[16px]"
                v-if="upsell.enable_quantity_selector"
            >
                <vue-number-input
                    v-model="quantity"
                    :min="min"
                    :max="max"
                    :disabled="disableOptions"
                    size="small"
                    inline
                    controls
                ></vue-number-input>
            </div>
            <div
                class="flex w-full sm:w-auto justify-end"
                v-if="upsell.remove_parent_product_when_upsell_product_is_added"
            >
                <div
                    class="add-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold pl-[8px] pr-[16px] h-[36px] sm:text-[14px] sm:leading-[20px] text-white cursor-pointer"
                    v-if="!adding && !added"
                    @click="addToCart"
                    :style="{ backgroundColor: setting.primary_color }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[16px] w-[16px] mr-[8px]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 11l5-5m0 0l5 5m-5-5v12"
                        />
                    </svg>
                    {{ setting.upgrade }}
                </div>
                <div
                    class="add-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold pl-[8px] pr-[16px] h-[36px] sm:text-[14px] sm:leading-[20px] text-white cursor-pointer"
                    v-if="adding && !added"
                    :style="{ backgroundColor: setting.primary_color }"
                >
                    <svg
                        class="animate-spin mr-[8px] h-[16px] w-[16px] text-white"
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
                    {{ setting.upgrade }}
                </div>
                <div
                    v-if="added"
                    class="added-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold px-[12px] h-[36px] sm:text-[14px] sm:leading-[20px] text-gray-600 bg-gray-100 cursor-pointer mr-[8px]"
                >
                    {{ setting.upgraded }}
                </div>
                <div
                    class="remove-btn flex justify-center h-[36px] items-center cursor-pointer"
                    @click="removeFromCart"
                    v-if="added && !deleting"
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
                </div>
                <div
                    class="remove-btn flex justify-center items-center h-[36px] cursor-pointer"
                    v-if="added && deleting"
                >
                    <svg
                        class="animate-spin h-[24px] w-[24px]"
                        :style="{ color: setting.primary_color }"
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
            <div
                class="flex w-full sm:w-auto justify-end"
                v-if="
                    !upsell.remove_parent_product_when_upsell_product_is_added
                "
            >
                <div
                    class="add-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold pl-[8px] pr-[16px] h-[36px] sm:text-[14px] sm:leading-[20px] text-white cursor-pointer"
                    v-if="!adding && !added"
                    @click="addToCart"
                    :style="{ backgroundColor: setting.primary_color }"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[16px] w-[16px] mr-[8px]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                    </svg>
                    {{ setting.add_to_cart }}
                </div>
                <div
                    class="add-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold pl-[8px] pr-[16px] h-[36px] sm:text-[14px] sm:leading-[20px] text-white cursor-pointer"
                    v-if="adding && !added"
                    :style="{ backgroundColor: setting.primary_color }"
                >
                    <svg
                        class="animate-spin mr-[8px] h-[16px] w-[16px] text-white"
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
                    {{ setting.add_to_cart }}
                </div>
                <div
                    v-if="added"
                    class="added-btn rounded-sm shadow-sm w-full flex justify-center items-center font-mono font-bold px-[12px] h-[36px] sm:text-[14px] sm:leading-[20px] text-gray-600 bg-gray-100 cursor-pointer mr-[8px]"
                >
                    {{ setting.added_to_cart }}
                </div>
                <div
                    class="remove-btn flex justify-center h-[36px] items-center cursor-pointer"
                    @click="removeFromCart"
                    v-if="added && !deleting"
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
                </div>
                <div
                    class="remove-btn flex justify-center items-center h-[36px] cursor-pointer"
                    v-if="added && deleting"
                >
                    <svg
                        class="animate-spin h-[24px] w-[24px]"
                        :style="{ color: setting.primary_color }"
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
        </div>
    </div>
</template>

<script>
import axios from "axios";
import OptionSelector from "./OptionSelector.vue";
import QuantitySelector from "./QuantitySelector.vue";
import VueNumberInput from "@chenfengyuan/vue-number-input";
export default {
    components: {
        OptionSelector,
        QuantitySelector,
        "vue-number-input": VueNumberInput
    },
    props: ["upsell"],
    data() {
        return {
            currency: {},
            setting: {},
            cart: {},
            variantIds: [],
            variants: [],
            selectedVariantId: 0,
            selectedVariant: {},
            disableOptions: false,
            quantity: 1,
            note: "",
            min: 1,
            max: 99,
            adding: false,
            added: false,
            deleting: false,
            deleted: false
        };
    },
    computed: {
        originalPrice() {
            // auto conversion
            if (this.setting.auto_conversion) {
                let antCartlLocalCurrency = localStorage.getItem(
                    "ant-cart-local-currency"
                );
                let antiCurrencyWidget = localStorage.getItem(
                    "antiCurrencyWidget"
                );
                let currency = JSON.parse(
                    document.getElementById("currency").innerText
                );
                let currencies = JSON.parse(
                    document.getElementById("currencies").innerText
                );
                if (antiCurrencyWidget) {
                    let toCurrency = currencies.find(
                        c => c.currency == antiCurrencyWidget
                    );
                    return (
                        toCurrency.currency_symbol +
                        Number(
                            window.Currency.convert(
                                this.selectedVariant.price,
                                currency.currency,
                                toCurrency.currency
                            )
                        ).toFixed(2)
                    );
                } else if (antCartlLocalCurrency) {
                    let toCurrency = currencies.find(
                        c => c.currency == antCartlLocalCurrency
                    );
                    return (
                        toCurrency.currency_symbol +
                        Number(
                            window.Currency.convert(
                                this.selectedVariant.price,
                                currency.currency,
                                toCurrency.currency
                            )
                        ).toFixed(2)
                    );
                }
            }
            return this.currency.currency_symbol + this.selectedVariant.price;
        },
        discountedPrice() {
            var price = 0;
            if (this.upsell.amount_type == "percentage") {
                price = Number(
                    (Number(this.selectedVariant.price) *
                        (100 - this.upsell.amount)) /
                        100
                ).toFixed(2);
            } else if (this.upsell.amount_type == "fixed_amount") {
                price = Number(
                    Number(this.selectedVariant.price) - this.upsell.amount
                ).toFixed(2);
            }
            // auto conversion
            if (this.setting.auto_conversion) {
                let antCartlLocalCurrency = localStorage.getItem(
                    "ant-cart-local-currency"
                );
                let antiCurrencyWidget = localStorage.getItem(
                    "antiCurrencyWidget"
                );
                let currency = JSON.parse(
                    document.getElementById("currency").innerText
                );
                let currencies = JSON.parse(
                    document.getElementById("currencies").innerText
                );
                if (antiCurrencyWidget) {
                    let toCurrency = currencies.find(
                        c => c.currency == antiCurrencyWidget
                    );
                    return (
                        toCurrency.currency_symbol +
                        Number(
                            window.Currency.convert(
                                price,
                                currency.currency,
                                toCurrency.currency
                            )
                        ).toFixed(2)
                    );
                } else if (antCartlLocalCurrency) {
                    let toCurrency = currencies.find(
                        c => c.currency == antCartlLocalCurrency
                    );
                    return (
                        toCurrency.currency_symbol +
                        Number(
                            window.Currency.convert(
                                price,
                                currency.currency,
                                toCurrency.currency
                            )
                        ).toFixed(2)
                    );
                }
            }
            return this.currency.currency_symbol + price;
        },
        noConversionDiscontedPrice() {
            var price = 0;
            if (this.upsell.amount_type == "percentage") {
                price = Number(
                    (Number(this.selectedVariant.price) *
                        (100 - this.upsell.amount)) /
                        100
                ).toFixed(2);
            } else if (this.upsell.amount_type == "fixed_amount") {
                price = Number(
                    Number(this.selectedVariant.price) - this.upsell.amount
                ).toFixed(2);
            }
            return price;
        }
    },
    mounted() {
        this.setting = JSON.parse(document.getElementById("setting").innerText);
        this.currency = JSON.parse(
            document.getElementById("currency").innerText
        );
        this.cart = JSON.parse(document.getElementById("cart").innerText);
        var shopifyProductVariants = JSON.parse(
            this.upsell.shopify_product_variants
        );
        if (shopifyProductVariants.length > 0) {
            this.variantIds = JSON.parse(this.upsell.shopify_product_variants);
            this.selectedVariantId = this.variantIds[0];
            this.selectedVariant = this.upsell.product.shopify_response.variants.find(
                v => v.id == this.selectedVariantId
                // &&
                // ((v.inventory_quantity > 0 &&
                //     v.inventory_policy == "deny" &&
                //     v.fulfillment_service == "manual" &&
                //     this.upsell.product.shopify_response.product_type !=
                //         "antrack_generated") ||
                //     v.inventory_policy != "deny" ||
                //     v.fulfillment_service != "manual" ||
                //     this.upsell.product.shopify_response.product_type ==
                //         "antrack_generated")
            );
            // TODO below logic is necessary ?
            if (this.selectedVariant) {
                this.max =
                    this.selectedVariant.inventory_quantity > 1
                        ? this.selectedVariant.inventory_quantity
                        : 1;
            }
        } else if (this.upsell.shopify_product_variant_id) {
            this.variantIds = [this.upsell.shopify_product_variant_id];
            this.selectedVariantId = this.upsell.shopify_product_variant_id;
            this.selectedVariant = this.upsell.product.shopify_response.variants.find(
                v => v.id == this.selectedVariantId
                // &&
                // ((v.inventory_quantity > 0 &&
                //     v.inventory_policy == "deny" &&
                //     v.fulfillment_service == "manual" &&
                //     this.upsell.product.shopify_response.product_type !=
                //         "antrack_generated") ||
                //     v.inventory_policy != "deny" ||
                //     v.fulfillment_service != "manual" ||
                //     this.upsell.product.shopify_response.product_type ==
                //         "antrack_generated")
            );
            // TODO below logic is necessary ?
            if (this.selectedVariant) {
                this.max =
                    this.selectedVariant.inventory_quantity > 1
                        ? this.selectedVariant.inventory_quantity
                        : 1;
            }
        }

        this.variants = this.upsell.product.shopify_response.variants.filter(
            v => this.variantIds.includes(v.id)
            // &&
            // ((v.inventory_quantity > 0 &&
            //     v.inventory_policy == "deny" &&
            //     v.fulfillment_service == "manual" &&
            //     this.upsell.product.shopify_response.product_type !=
            //         "antrack_generated") ||
            //     v.inventory_policy != "deny" ||
            //     v.fulfillment_service != "manual" ||
            //     this.upsell.product.shopify_response.product_type ==
            //         "antrack_generated")
        );

        if (this.upsell.match_parent_quantity) {
            var interval = setInterval(() => {
                let antBgWrap = document.getElementById("ant-bg-wrap");
                let addToCartForm = document.querySelector(
                    'form[action="/cart/add"]'
                );
                let formData = new FormData(addToCartForm);
                formData.forEach((v, k) => {
                    if (k == "quantity") {
                        this.quantity = parseInt(v);
                    }
                });
                if (!antBgWrap.className.includes("hidden")) {
                    clearInterval(interval);
                }
            }, 200);
        }

        console.log("AntCartUpsell mounted.");
        console.log("AntCartUpsell variants", this.variants);
        console.log("selectedVariant", this.selectedVariant);
    },
    methods: {
        selectedChanged(variant) {
            this.selectedVariant = variant;
            this.selectedVariantId = variant.id;
            console.log("selectedChanged variantId=", variant.id);
            this.$refs.options1.setSelected(variant);
            this.$refs.options2.setSelected(variant);
        },
        addToCart() {
            this.adding = true;
            this.disableOptions = true;
            let url = document.getElementById("url").value;
            let shop = document.getElementById("shop").value;
            let cart = JSON.parse(document.getElementById("cart").innerText);
            // add to cart
            if (
                this.upsell.remove_parent_product_when_upsell_product_is_added
            ) {
                // remove parent product from cart & add current variant;
                this.updateVariantToCart(
                    this.upsell.product,
                    this.selectedVariantId
                );
            } else {
                // add current variant to cart
                this.addVariantToCart(this.selectedVariantId, this.quantity);
            }

            this.trackView("add_to_cart");
        },
        trackView(event_type) {
            let url = document.getElementById("url").value;
            let shop = document.getElementById("shop").value;
            fetch(url + "/track", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    shop: shop,
                    cart_token: this.cart.token,
                    event_type: event_type,
                    upsell_rocks: [this.upsell.id],
                    data: {
                        product: this.selectedVariant.product_id,
                        variant: this.selectedVariantId
                    },
                    stats_at: new Date().getTime()
                })
            })
                .then(res => {
                    return res.json();
                })
                .then(json => {
                    console.log(json);
                });
        },
        removeFromCart() {
            this.deleting = true;
            let url = document.getElementById("url").value;
            let shop = document.getElementById("shop").value;
            let cart = JSON.parse(document.getElementById("cart").innerText);
            this.removeVariantFromCart(this.selectedVariantId);
            this.trackView("remove_from_cart");
        },
        addVariantToCart(variant, quantity) {
            var body = [{ id: variant, quantity: quantity }];
            if (this.upsell.show_note_field) {
                body = [
                    {
                        id: variant,
                        quantity: quantity,
                        properties: {
                            note: this.note
                        }
                    }
                ];
            }
            axios
                .post("/cart/add.js", {
                    items: body
                })
                .then(res => {
                    this.adding = false;
                    this.added = true;
                });
            // use discount code
            if (
                this.upsell.apply_discount &&
                this.upsell.discount_code.length > 0
            ) {
                axios.get("/discount/" + this.upsell.discount_code);
            }
        },
        removeVariantFromCart(variant) {
            axios
                .post("/cart/change.js", {
                    id: variant + "",
                    quantity: 0
                })
                .then(res => {
                    this.deleting = false;
                    this.added = false;
                    this.disableOptions = false;
                });
        },
        updateVariantToCart(product, variant) {
            var variantIds = product.shopify_response.variants.map(v => v.id);
            var updates = "";
            variantIds.forEach(vid => {
                var q = vid == variant ? this.quantity : 0;
                if (updates == "") {
                    updates = "updates[" + vid + "]=" + q;
                } else {
                    updates += "&updates[" + vid + "]=" + q;
                }
            });

            axios.post("/cart/update.js", updates).then(res => {
                this.adding = false;
                this.added = true;
            });

            if (this.upsell.show_note_field && this.note != "") {
                setTimeout(() => {
                    this.updateProperty(variant, this.note);
                }, 500);
            }
            // use discount code
            if (
                this.upsell.apply_discount &&
                this.upsell.discount_code.length > 0
            ) {
                axios.get("/discount/" + this.upsell.discount_code);
            }
        },
        updateProperty(variant, note) {
            axios.get("/cart.js").then(res => {
                var cart = res.data;
                var line = 1;
                for (var i = 0; i < cart.items.length; i++) {
                    if (cart.items[i].variant_id == variant) {
                        line = i + 1;
                    }
                }
                axios.post("/cart/change.js", {
                    line: line,
                    properties: {
                        note: note
                    }
                });
            });
        }
    }
};
</script>
