<template>
  <div
    class="fixed z-10 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    v-if="show"
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
                  @input="onQueryChange"
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
                        v-model="condition_products"
                        :value="product.id"
                        @change="productsChanged(product.id)"
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
                      <div class="flex items-center">
                        <input
                          id="variant-id"
                          name="variant-id"
                          type="checkbox"
                          v-model="condition_variants"
                          :value="variant.id"
                          @change="variantsChanged(product.id, variant.id)"
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
export default {
  props: ["upsell", "show", "query"],
  data() {
    return {
      form: {},
      type: "specific-products",
      condition_products: [],
      condition_variants: [],
      variant_product_relations: {},
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
  created() {
    console.log("ChooseDisplayForProductsModal created");
  },
  mounted() {
    console.log("ChooseDisplayForProductsModal mounted.");
    document.getElementById("search-products").focus();
    this.form = this.upsell;
    this.upsell.conditions.forEach((c, index) => {
      if (c.product_id && !this.condition_products.includes(c.product_id)) {
        this.condition_products.push(c.product_id);
      }
      this.condition_variants.push(c.product_variant_id);
      this.variant_product_relations[c.product_variant_id] = c.product_id;
    });
    if (this.query.length > 0) {
      this.filtered_products = this.local_products.filter(product =>
        product.title.toLowerCase().includes(this.query.toLowerCase())
      );
    } else {
      this.filtered_products = this.local_products;
    }
  },
  methods: {
    onQueryChange(e) {
      if (e.target.value.length > 0) {
        this.filtered_products = this.local_products.filter(product =>
          product.title.toLowerCase().includes(e.target.value.toLowerCase())
        );
      } else {
        this.filtered_products = this.local_products;
      }
      this.$emit("update:query", e.target.value);
    },
    updateConditions() {
      console.log("updateConditions");
      this.form = this.upsell;
      this.condition_products = [];
      this.condition_variants = [];
      this.variant_product_relations = {};
      this.upsell.conditions.forEach((c, index) => {
        if (c.product_id && !this.condition_products.includes(c.product_id)) {
          this.condition_products.push(c.product_id);
        }
        this.condition_variants.push(c.product_variant_id);
        this.variant_product_relations[c.product_variant_id] = c.product_id;
      });
    },
    getCurrencySymbol(currency) {
      var cur = this.currencies.find(c => c.currency === currency);
      if (cur) {
        return cur.currency_symbol;
      }
    },
    hide() {
      this.condition_products = [];
      this.condition_variants = [];
      this.$emit("update:show", false);
    },
    add() {
      this.$emit("update:show", false);
      // build form conditions
      var new_conditons = [];
      // type, product_id, product_variant_id
      this.condition_variants.forEach(variant_id => {
        if (variant_id !== null) {
          new_conditons.push({
            type: "specific-products",
            product_id: this.variant_product_relations[variant_id],
            product_variant_id: variant_id
          });
        }
      });
      this.form.conditions = new_conditons;
      console.log("conditions updated");
      console.log(JSON.stringify(this.form.conditions));
      this.$emit("update:upsell", this.form);
    },
    productsChanged(product_id) {
      document
        .getElementById("product-id-" + product_id)
        .classList.remove("not-all");
      // find product
      var p = this.local_products.find(product => product.id === product_id);
      if (p && !this.condition_products.includes(product_id)) {
        p.variants.forEach(variant => {
          var i = this.condition_variants.indexOf(variant.id);
          if (i > -1) {
            this.condition_variants.splice(i, 1);
          }
          delete this.variant_product_relations[variant.id];
        });
      }
      if (p && this.condition_products.includes(product_id)) {
        p.variants.forEach(variant => {
          this.condition_variants.push(variant.id);
          this.variant_product_relations[variant.id] = product_id;
        });
      }

      console.log("products:" + JSON.stringify(this.condition_products));
      console.log("variants" + JSON.stringify(this.condition_variants));
      console.log(
        "vp-relations:" + JSON.stringify(this.variant_product_relations)
      );
    },
    variantsChanged(product_id, variant_id) {
      if (!this.condition_products.includes(product_id)) {
        this.condition_products.push(product_id);
        document
          .getElementById("product-id-" + product_id)
          .classList.add("not-all");
      } else {
        // 处理variant都没选中，product选中的状态
        var p = this.local_products.find(product => product.id === product_id);
        if (p) {
          var show_count = 0;
          p.variants.forEach(variant => {
            if (this.condition_variants.includes(variant.id)) {
              show_count++;
            }
          });
          if (show_count === 0) {
            // remove product_id from condition_products
            var i = this.condition_products.indexOf(product_id);
            if (i > -1) {
              this.condition_products.splice(i, 1);
            }
          } else if (show_count < p.variants.length) {
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
      if (this.condition_variants.includes(variant_id)) {
        this.variant_product_relations[variant_id] = product_id;
      } else {
        delete this.variant_product_relations[variant_id];
      }
      console.log("products:" + JSON.stringify(this.condition_products));
      console.log("variants" + JSON.stringify(this.condition_variants));
      console.log(
        "vp-relations:" + JSON.stringify(this.variant_product_relations)
      );
    }
  }
};
</script>

<style scoped>
.not-all {
  background-image: url('data:image/svg+xml,<svg fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>');
}
</style>
