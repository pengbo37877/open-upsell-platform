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
            <svg
              class="w-12 h-12"
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
          <div class="ml-3 flex flex-col justify-center space-y-2">
            <div class="h-2 w-40 bg-gray-100 rounded"></div>
            <div class="h-2 w-40 pr-5">
              <div class="h-2 bg-gray-100 rounded"></div>
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
  }
};
</script>
