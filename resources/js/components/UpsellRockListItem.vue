<template>
  <div>
    <div class="grid grid-cols-3 bg-white rounded shadow" v-if="type==='Product Upsell'">
      <div class="col-span-1 px-8 py-6 flex flex-col border-r border-gray-200">
        <div class="text-gray-900 font-medium">{{ types['Product Upsell'].title }}</div>
        <div
          class="flex-1 mt-4 mb-10 text-gray-900 text-sm"
        >{{ types['Product Upsell'].description}}</div>
        <div
          class="bg-green-700 text-center text-sm text-white w-20 rounded-sm border border-green-800 px-3 py-2 shadow-sm font-light"
        >Select</div>
      </div>
      <div class="col-span-2 px-8 py-6 flex flex-col">
        <div class="text-gray-500 font-medium">Pop-Up Window Preview</div>
        <div class="mt-4 flex bg-gray-50 p-4 relative">
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
        <div class="z-10 pl-12 mt-4" v-if="Object.keys(types['Product Upsell'].product).length>0">
          <div class="flex">
            <div class="image flex flex-col justify-start bg-white">
              <img :src="types['Product Upsell'].product.image.src" class="w-12 h-12" alt />
            </div>
            <div class="ml-3 flex flex-col">
              <div
                class="text-xs font-medium text-gray-800"
              >{{types['Product Upsell'].product.title}}</div>
              <div class="flex">
                <div
                  class="text-xs text-gray-400 font-light line-through"
                >{{getCurrencySymbol(shop.currency)}}{{types['Product Upsell'].product.variants[0].price}}</div>
                <div
                  class="ml-2 text-xs text-gray-700 font-light"
                >{{getCurrencySymbol(shop.currency)}}{{types['Product Upsell'].product.variants[0].price-2}}</div>
              </div>
              <div class="mt-1 text-xs text-gray-400 font-light">Unique offer!</div>
              <select
                id="product-variants"
                name="product-variants"
                autocomplete="product-variants"
                class="mt-1 block w-40 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              >
                <option
                  v-for="(variant, index) in types['Product Upsell'].product.variants"
                  :key="index"
                >{{variant.option1}}</option>
              </select>
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
  props: ["type"],
  data() {
    return {
      types: {
        "Product Upsell": {
          title: "Product Upsell",
          description:
            "Offer existing products from your store and increase order value.",
          product: {}
        },
        "Smart Auto-Upsell": {
          title: "Smart Auto-Upsell",
          description:
            "Upsell products are chosen automatically based on the Shopify Product Recommendation API",
          learn: {
            text: "(learn more)",
            link:
              "https://www.notion.so/digismoothie/What-are-the-Smart-Auto-Upsells-and-how-to-use-them-Candy-Rack-18904af9f19a47338cc43cd985a6ef63"
          },
          exist: false,
          item: {
            title: "Most relevant product",
            icon: "",
            subtitle: "You may need this one"
          }
        },
        "Gift Packaging": {
          title: "Gift Packaging",
          description: "We'll wrap your product in style",
          item: {
            title: "Gift Packaging",
            icon: "",
            subtitle: "We'll wrap your product in style",
            price: "9.99"
          }
        },
        "Custom Gift Note": {
          title: "Custom Gift Note",
          description: "Add a personal note and make someone happy",
          item: {
            title: "Custom Gift Note",
            subtitle: "Add a personal note and make someone happy",
            icon: "",
            price: "1.99"
          }
        },
        "Extended Warranty": {
          title: "Extended Warranty",
          description: "Stay worry-free almost forever",
          item: {
            title: "Extended Warranty",
            subtitle: "Stay worry-free almost forever",
            icon: "",
            price: "9.99"
          }
        },
        "Premium Email Support": {
          title: "Premium Email Support",
          description: "Be the first one in the queue",
          item: {
            title: "Premium Email Support",
            subtitle: "Be the first one in the queue",
            icon: "",
            price: "4.99"
          }
        },
        "Mystery Product": {
          title: "Mystery Product",
          description:
            "Randomly chosen item from our store for value up to $100",
          item: {
            title: "Mystery Product",
            subtitle:
              "Randomly chosen item from our store for value up to $100",
            icon: "",
            price: "4.99"
          }
        },
        "First in Line": {
          title: "First in Line",
          description:
            "Instead of a 3 day processing time, enjoy the same day!",
          item: {
            title: "First in Line",
            subtitle: "Instead of a 3 day processing time, enjoy the same day!",
            icon: "",
            price: "4.99"
          }
        },
        "Incognito Shipping": {
          title: "Incognito Shipping",
          description: "Plain packaging without branding",
          item: {
            title: "Incognito Shipping",
            subtitle: "Plain packaging without branding",
            icon: "",
            price: "2.99"
          }
        },
        "Custom service": {
          title: "Custom service",
          description: "Custom description",
          item: {
            title: "Custom service",
            subtitle: "Custom description",
            icon: "",
            price: "9.99"
          }
        }
      }
    };
  },
  computed: {
    ...mapState({
      user: state => state.upsellrock.user,
      shop: state => state.upsellrock.shop,
      currencies: state => state.upsellrock.currencies
    })
  },
  mounted() {
    console.log("Upsell Rock List Item mounted.");
    // get random product
    if (this.type === "Product Upsell") {
      setTimeout(() => {
        this.getRandomProduct();
      }, 800);
    }
  },
  methods: {
    getCurrencySymbol(currency) {
      var cur = this.currencies.find(c => c.currency === currency);
      if (cur) {
        return cur.currency_symbol;
      }
    },
    getRandomProduct() {
      axios.get("/spa/random_product").then(res => {
        // this.types["Product Upsell"].product = res.data;
        console.log(res.data);
        this.types["Product Upsell"].product = res.data.find(
          p => p.product_type === ""
        );
        console.log(this.types["Product Upsell"]);
      });
    }
  }
};
</script>
