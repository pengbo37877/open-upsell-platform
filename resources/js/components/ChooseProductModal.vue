<template>
  <div
    class="fixed z-10 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

      <!-- This element is to trick the browser into centering the modal contents. -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
      >
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="w-full text-center">
              <h3
                class="text-lg leading-6 font-medium text-gray-900 text-center"
                id="modal-title"
              >Add product</h3>
              <div class="mt-3 flex w-full rounded-md shadow-sm">
                <span
                  class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-white text-gray-500 text-sm"
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
                  name="search-products"
                  id="search-products"
                  :value="query"
                  @input="onQueryChanged"
                  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 border-l-0"
                  placeholder="Search products"
                />
              </div>
              <!-- products -->
              <div class="mt-2 flex flex-col w-full overflow-y-scroll h-96">
                <div
                  class="flex flex-col"
                  v-for="(product, index) in filtered_products"
                  :key="index"
                >
                  <div class="flex justify-between p-4 border-b border-gray-300">
                    <div class="flex items-center h-full w-4">
                      <input
                        :id="`product-id-${product.id}`"
                        name="product-id"
                        type="checkbox"
                        v-model="form.shopify_product_id"
                        :value="product.id"
                        @change="productChanged(product.id)"
                        v-if="shouldShowCheckbox(product.id)"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                      />
                    </div>
                    <div class="ml-3 flex items-center">
                      <img
                        :src="product.image.src"
                        class="w-10 h-10 border border-gray-300 rounded"
                        alt
                        v-if="product.image"
                      />
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
                    <div
                      class="ml-3 flex items-center text-sm text-gray-800 flex-1 overflow-hidden"
                    >{{product.title}}</div>
                    <div
                      class="ml-3 flex items-center text-sm texy-gray-800 font-light"
                    >{{getCurrencySymbol(shop.currency)}}{{product.variants[0].price}}</div>
                  </div>
                  <!-- product variants -->
                  <div
                    class="flex flex-col pl-10"
                    v-if="product.variants && product.variants.length>1"
                  >
                    <div
                      class="flex justify-between p-4 border-b border-gray-300"
                      v-for="(variant, id) in product.variants"
                      :key="id"
                    >
                      <div class="flex items-center h-full w-4">
                        <input
                          id="variant-id"
                          name="variant-id"
                          type="checkbox"
                          v-model="form.shopify_product_variants"
                          :value="variant.id"
                          @change="variantChanged(product.id, variant.id)"
                          v-if="shouldShowVariantCheckbox(product.id, variant.id)"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                        />
                      </div>
                      <div
                        class="flex-1 ml-3 text-sm text-gray-800 flex items-center text-left"
                      >{{ variant.option1 }}{{ variant.option2!=null?"|"+variant.option2:"" }}{{variant.option3!=null?"|"+variant.option3:""}}</div>
                      <div
                        class="ml-3 flex text-gray-800 items-center text-sm font-light"
                      >{{variant.inventory_quantity}} available</div>
                      <div
                        class="ml-6 flex text-gray-800 items-center text-sm font-light"
                      >{{getCurrencySymbol(shop.currency)}}{{variant.price}}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            @click="add"
            type="button"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
          >Add</button>
          <button
            @click="hide"
            type="button"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import ProductUpsellVue from "./ProductUpsell.vue";
export default {
  props: ["upsell", "show", "onProductChanged"],
  data() {
    return {
      form: {},
      query: "",
      filtered_products: []
    };
  },
  computed: {
    ...mapState({
      user: state => state.upsellrock.user,
      shop: state => state.upsellrock.shop,
      currencies: state => state.upsellrock.currencies,
      upsells: state => state.upsellrock.upsells,
      products: state => state.upsellrock.products,
      local_products: state => state.upsellrock.local_products
    })
  },
  mounted() {
    this.form = this.upsell;
    this.filtered_products = this.local_products;
  },
  methods: {
    onQueryChanged(e) {
      this.query = e.target.value;
      if (e.target.value.length === 0) {
        this.filtered_products = this.local_products;
        return;
      }
      this.filtered_products = this.local_products.filter(product =>
        product.title.toLowerCase().includes(e.target.value.toLowerCase())
      );
    },
    getCurrencySymbol(currency) {
      var cur = this.currencies.find(c => c.currency === currency);
      if (cur) {
        return cur.currency_symbol;
      }
    },
    shouldShowCheckbox(product_id) {
      if (
        Object.keys(this.form).length > 0 &&
        this.form.shopify_product_id.length == 0
      ) {
        return true;
      }
      if (
        Object.keys(this.form).length > 0 &&
        this.form.shopify_product_id[0] === product_id
      ) {
        return true;
      }
      return false;
    },
    shouldShowVariantCheckbox(product_id, variant_id) {
      if (
        Object.keys(this.form).length > 0 &&
        this.form.shopify_product_id.length === 0
      ) {
        return true;
      }
      if (
        Object.keys(this.form).length > 0 &&
        this.form.shopify_product_id[0] === product_id
      ) {
        return true;
      }
      return false;
    },
    hide() {
      this.$emit("update:show", false);
    },
    add() {
      this.onProductChanged({ upsell: this.form });
      this.$emit("update:show", false);
    },
    productChanged(product_id) {
      document
        .getElementById("product-id-" + product_id)
        .classList.remove("not-all");
      if (this.form.shopify_product_id.length === 0) {
        this.form.shopify_product_handle = null;
        this.form.shopify_product_variant_id = null;
        this.form.shopify_product_variants = [];
        return;
      }
      var p = this.local_products.find(
        lp => lp.id === this.form.shopify_product_id[0]
      );
      if (p && p.variants.length === 1) {
        this.form.shopify_product_variant_id = p.variants[0].id;
        this.form.shopify_product_variants = [];
      }
      if (p && p.variants.length > 1) {
        this.form.shopify_product_variant_id = null;
        this.form.shopify_product_variants = p.variants.map((v, i) => v.id);
      }
      this.form.shopify_product_handle = p.handle;
    },
    variantChanged(product_id, variant_id) {
      if (this.form.shopify_product_variants.length === 0) {
        this.form.shopify_product_id = [];
      } else {
        this.form.shopify_product_id = [product_id];
      }
      var p = this.local_products.find(lp => lp.id === product_id);
      if (p.variants.length !== this.form.shopify_product_variants.length) {
        document
          .getElementById("product-id-" + product_id)
          .classList.add("not-all");
      } else {
        document
          .getElementById("product-id-" + product_id)
          .classList.remove("not-all");
      }
    }
  }
};
</script>

<style scoped>
.not-all {
  background-image: url('data:image/svg+xml,<svg fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>');
}
</style>
