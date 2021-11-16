<template>
  <div>
    <div class="flex flex-col">
      <!-- layout -->
      <div class="flex bg-gray-50 p-4 relative">
        <div class="rounded bg-gray-200 w-10 h-10"></div>
        <div class="ml-3 flex flex-col justify-center space-y-2">
          <div class="h-2 w-40 bg-gray-200 rounded"></div>
          <div class="h-2 w-40 pr-5">
            <div class="h-2 bg-gray-200 rounded"></div>
          </div>
        </div>
        <div class="absolute left-9 top-8 rounded border-l border-b border-gray-200 w-5 h-20"></div>
      </div>
      <!-- product -->
      <div class="z-10 pl-12 mt-4">
        <div class="flex">
          <div
            class="w-12 h-12 text-gray-500 flex flex-col justify-start bg-white border rounded border-gray-200"
          >
            <img
              class="w-12 h-12"
              :src="`https://pb-fsb-bgs.s3.ap-southeast-1.amazonaws.com/icon-svgs/${upsell.icon}.svg`"
              alt
            />
          </div>
          <div class="ml-3 flex flex-col justify-center space-y-2">
            <div class="text-xs font-medium text-gray-800">{{upsell.headline}}</div>
            <div class="fle">
              <div
                class="ml-2 text-xs text-gray-700 font-light"
                v-if="upsell.apply_discount && upsell.amount_type==='percentage'"
              >{{getCurrencySymbol(user.currency)}}{{Number(upsell.price*(100-upsell.amount)/100).toFixed(2)}}</div>
              <div
                class="ml-2 text-xs text-gray-700 font-light"
                v-if="upsell.apply_discount && upsell.amount_type==='fixed'"
              >{{getCurrencySymbol(user.currency)}}{{Number(upsell.price-upsell.amount).toFixed(2)}}</div>
              <div
                class="text-xs text-gray-700 font-light"
                v-if="!upsell.apply_discount"
              >{{getCurrencySymbol(user.currency)}}{{Number(upsell.price).toFixed(2)}}</div>
            </div>
            <div class="mt-1 text-xs text-gray-400 font-light">{{upsell.short_description}}</div>
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
  props: ["upsell"],
  data() {
    return {
      title: "Smart Auto-Upsell",
      description:
        "Offer existing products from your store and increase order value."
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
    console.log("Smart Auto-Upsell mounted.");
  },
  methods: {
    getCurrencySymbol(currency) {
      var cur = this.currencies.find(c => c.currency === currency);
      if (cur) {
        return cur.currency_symbol;
      }
    }
  }
};
</script>
