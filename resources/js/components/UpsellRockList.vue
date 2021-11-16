<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >Name</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >Status</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >Last edited</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >Created at</th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(upsell, index) in upsells" :key="index">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{upsell.name}}</div>
                      <div class="text-sm text-gray-500">{{getDisplayDesc(upsell)}}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-if="upsell.active"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                  >Active</span>
                  <span
                    v-else
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"
                  >Draft</span>
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                >{{(new Date(upsell.updated_at)).toLocaleDateString()}} {{(new Date(upsell.updated_at)).toLocaleTimeString()}}</td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                >{{(new Date(upsell.created_at)).toLocaleDateString()}} {{(new Date(upsell.created_at)).toLocaleTimeString()}}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <router-link
                    :to="`/upsell/${upsell.id}/edit`"
                    class="text-indigo-600 hover:text-indigo-900"
                  >Edit</router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {};
  },
  computed: {
    ...mapState({
      user: state => state.upsellrock.user,
      shop: state => state.upsellrock.shop,
      currencies: state => state.upsellrock.currencies,
      upsells: state => state.upsellrock.upsells
    })
  },
  methods: {
    getDisplayDesc(upsell) {
      if (upsell.display_for === "all") {
        return "All Products";
      }
    }
  }
};
</script>
