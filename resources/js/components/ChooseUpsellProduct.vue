<template>
  <div class="bg-white rounded shadow p-6 flex flex-col">
    <div class="text-sm text-gray-900 font-medium">UPSELL PRODUCT</div>
    <div
      class="mt-4 flex justify-center"
      v-if="upsell.type==='product' && upsell.shopify_product_id.length===0"
    >
      <div
        @click="showModal"
        class="bg-green-700 text-center text-sm text-white w-auto rounded-sm border border-green-800 px-3 py-2 shadow-sm font-light cursor-pointer"
      >Select product</div>
    </div>
    <div v-else>
      <div class="mt-3 flex items-center" v-if="Object.keys(product).length>0">
        <div class="border rounded">
          <img :src="product.image.src" class="w-10 h-10" alt v-if="product.image" />
          <div v-else>
            <svg
              t="1626758737081"
              class="icon w-10 h-10"
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
        <div class="ml-2 flex flex-col justify-between flex-1">
          <div class="text-gray-800 text-sm overflow-hidden">{{product.title}}</div>
          <div class="text-gray-500 text-xs overflow-hidden">{{getVariantOptions()}}</div>
        </div>
        <div class="ml-1 flex items-center justify-center">
          <div
            class="text-sm text-blue-800 font-light cursor-pointer"
            @click="show_modal=true"
          >Change</div>
        </div>
      </div>
    </div>
    <choose-product-modal
      :onProductChanged="onProductChanged"
      :upsell="upsell"
      :show.sync="show_modal"
      v-if="show_modal"
    ></choose-product-modal>
  </div>
</template>

<script>
import axios from "axios";
import { mapState } from "vuex";
import ChooseProductModal from "./ChooseProductModal.vue";
export default {
  components: { ChooseProductModal },
  props: ["upsell"],
  data() {
    return {
      product: {},
      variants: [],
      show_modal: false
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
    console.log("Choose Upsell Product mounted");
    var p = this.local_products.find(
      lp => lp.id === this.upsell.shopify_product_id[0]
    );
    if (p) {
      this.product = p;
    }
    this.variants = this.upsell.shopify_product_variants;
  },
  methods: {
    getProductByShopifyId() {
      axios.get("/spa/product/" + this.upsell.shopify_product_id).then(res => {
        this.product = res.data;
        console.log(res.data);
      });
    },
    getVariantOptions() {
      var variants = this.product.variants.filter((variant, index) => {
        return this.variants.includes(variant.id);
      });
      var options = variants.map((variant, index) => {
        var v = variant.option1;
        if (variant.option2 !== null) {
          v += "|" + variant.option2;
        }
        if (variant.option3 !== null) {
          v += "|" + variant.option3;
        }
        return v;
      });
      return options.join(",");
    },
    showModal() {
      console.log("showModal");
      this.show_modal = true;
      this.getLocalProducts();
    },
    hideModel() {
      this.show_modal = false;
    },
    onProductChanged({ upsell }) {
      console.log("onProductChanged");
      var p = this.local_products.find(
        lp => lp.id === upsell.shopify_product_id[0]
      );
      if (p) {
        this.product = p;
      }
      this.variants = this.upsell.shopify_product_variants;
      this.$emit("update:upsell", upsell);
    },
    getLocalProducts() {
      axios.get("/spa/local_products").then(res => {
        console.log("getLocalProducts");
        console.log(res.data);
        this.$store.commit("upsellrock/SET_LOCAL_PRODUCTS", {
          local_products: res.data
        });
      });
    }
  }
};
</script>
