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
              >Add collection</h3>
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
                  name="search-collections"
                  id="search-collections"
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
                  v-for="(collection, index) in filtered_collections"
                  :key="index"
                >
                  <div class="flex justify-start p-4 border-b border-gray-300">
                    <div class="flex items-center h-full w-4">
                      <input
                        :id="`collection-id-${collection.id}`"
                        name="collection-id"
                        type="checkbox"
                        v-model="condition_collections"
                        :value="collection.id"
                        @change="collectionsChanged(collection.id)"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                      />
                    </div>
                    <div
                      class="ml-3 flex items-center text-sm text-gray-800 flex-1 overflow-hidden"
                    >{{collection.title}}</div>
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
      type: "specific-collections",
      condition_collections: [],
      filtered_collections: []
    };
  },
  computed: {
    ...mapState({
      user: state => state.upsellrock.user,
      shop: state => state.upsellrock.shop,
      currencies: state => state.upsellrock.currencies,
      upsells: state => state.upsellrock.upsells,
      products: state => state.upsellrock.products,
      local_products: state => state.upsellrock.local_products,
      local_collections: state => state.upsellrock.local_collections
    })
  },
  mounted() {
    console.log("ChooseDisplayForCollectionsModal mounted.");
    document.getElementById("search-collections").focus();
    this.form = this.upsell;
    this.upsell.conditions.forEach((c, index) => {
      if (
        c.collection_id &&
        !this.condition_collections.includes(c.collection_id)
      ) {
        this.condition_collections.push(c.collection_id);
      }
    });
    if (this.query.length > 0) {
      this.filtered_collections = this.local_collections.filter(collection =>
        collection.title.toLowerCase().includes(this.query.toLowerCase())
      );
    } else {
      this.filtered_collections = this.local_collections;
    }
  },
  methods: {
    onQueryChange(e) {
      if (e.target.value.length > 0) {
        this.filtered_collections = this.local_collections.filter(collection =>
          collection.title.toLowerCase().includes(e.target.value.toLowerCase())
        );
      } else {
        this.filtered_collections = this.local_collections;
      }
      this.$emit("update:query", e.target.value);
    },
    updateConditions() {
      console.log("updateCollectionConditions");
      this.form = this.upsell;
      this.condition_collections = [];
      this.upsell.conditions.forEach((c, index) => {
        if (
          c.collection_id &&
          !this.condition_collections.includes(c.collection_id)
        ) {
          this.condition_collections.push(c.collection_id);
        }
      });
    },
    getCurrencySymbol(currency) {
      var cur = this.currencies.find(c => c.currency === currency);
      if (cur) {
        return cur.currency_symbol;
      }
    },
    hide() {
      this.condition_collections = [];
      this.$emit("update:show", false);
    },
    add() {
      this.$emit("update:show", false);
      // build form conditions
      var new_conditons = [];
      // type, product_id, product_variant_id
      this.condition_collections.forEach(collection_id => {
        new_conditons.push({
          type: "specific-collections",
          collection_id: collection_id
        });
      });
      this.form.conditions = new_conditons;
      console.log("conditions updated");
      console.log(JSON.stringify(this.form.conditions));
      this.$emit("update:upsell", this.form);
    },
    collectionsChanged(collection_id) {
      console.log("collectionsChanged:" + collection_id);
    }
  }
};
</script>

<style scoped>
.not-all {
  background-image: url('data:image/svg+xml,<svg fill="white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>');
}
</style>
