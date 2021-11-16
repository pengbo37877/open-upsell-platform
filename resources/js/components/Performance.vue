<template>
  <div>
    <!-- navs -->
    <nav class="bg-gray-800">
      <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
          <div class="flex items-center justify-center flex-1 sm:items-stretch sm:justify-start">
            <div class="flex items-center flex-shrink-0">
              <img class="w-auto h-8" src="/images/upsell-logo.svg" alt />
            </div>
            <div class="hidden sm:block sm:ml-6">
              <div class="flex space-x-4">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <!-- <router-link
                  to="/"
                  class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                  aria-current="page"
                >Dashboard</router-link>-->
                <router-link
                  to="/"
                  class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                  aria-current="page"
                >Dashboard</router-link>
                <router-link
                  to="/setting"
                  class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
                  aria-current="page"
                >Setting</router-link>
                <router-link
                  to="/performance"
                  class="block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-md"
                  aria-current="page"
                >Performance</router-link>
                <div
                  class="block px-3 py-2 text-sm font-medium text-gray-300 rounded-md cursor-pointer hover:bg-gray-700 hover:text-white"
                  aria-current="page"
                  @click="gotoCCA"
                >Add 170+ Currencies to your shop</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Mobile menu, show/hide based on menu state. -->
      <div class="sm:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
          <!-- <router-link
            to="/"
            class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
            aria-current="page"
          >Dashboard</router-link>-->
          <router-link
            to="/"
            class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
            aria-current="page"
          >Dashboard</router-link>
          <router-link
            to="/setting"
            class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white"
            aria-current="page"
          >Setting</router-link>
          <router-link
            to="/performance"
            class="block px-3 py-2 text-base font-medium text-white bg-gray-900 rounded-md"
            aria-current="page"
          >Performance</router-link>
          <div
            class="block px-3 py-2 text-base font-medium text-gray-300 rounded-md cursor-pointer hover:bg-gray-700 hover:text-white"
            aria-current="page"
            @click="gotoCCA"
          >Add 170+ Currencies to your shop</div>
        </div>
      </div>
    </nav>
    <!-- end navs -->

    <!-- content -->
    <div class="max-w-6xl py-6 mx-auto sm:px-6 lg:px-8">
      <div class="w-full pb-6 text-3xl font-bold">Performance</div>

      <!-- app statistics -->
      <div class="flex flex-col p-6 bg-white rounded shadow">
        <div class="flex items-center">
          <div class="text-gray-500">
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
              />
            </svg>
          </div>
          <div class="flex-1 ml-4 font-medium">App statistics</div>
          <div class="ml-2">
            <date-picker
              v-model="views_range"
              value-type="timestamp"
              type="date"
              range
              placeholder="Select date range "
              @change="rangeChanged"
            ></date-picker>
          </div>
        </div>
        <div
          class="mt-4 text-sm font-light text-gray-500"
        >See how many store visitors interacted with your upsell rock</div>
        <div class="grid grid-cols-5 mt-3 border border-gray-300 rounded">
          <div class="flex flex-col py-6 text-center border-r border-gray-300">
            <div class="text-2xl font-medium">{{statistics.views_count}}</div>
            <div class="font-light text-gray-500">Total Views</div>
          </div>
          <div class="flex flex-col py-6 text-center border-r border-gray-300">
            <div class="text-2xl font-medium">{{statistics.add_to_carts_count}}</div>
            <div class="font-light text-gray-500">Add to carts</div>
          </div>
          <div class="flex flex-col py-6 text-center border-r border-gray-300">
            <div class="text-2xl font-medium">{{statistics.transactions_count}}</div>
            <div class="font-light text-gray-500">Transactions</div>
          </div>
          <div class="flex flex-col py-6 text-center border-r border-gray-300">
            <div
              class="text-2xl font-medium"
            >{{ shopCurrencySymbol }} {{ Number(statistics.sales).toFixed(2)}}</div>
            <div class="font-light text-gray-500">Sales</div>
          </div>
          <div class="flex flex-col py-6 text-center">
            <div
              class="text-2xl font-medium"
            >{{statistics.transactions_count * 100 / (statistics.views_count==0?(statistics.views_count+1):statistics.views_count)}}%</div>
            <div class="font-light text-gray-500">Take Rate</div>
          </div>
        </div>
      </div>

      <!-- App sessions -->
      <div class="flex flex-col p-6 mt-6 bg-white rounded shadow">
        <div class="flex items-center">
          <div class="text-gray-500">
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
          <div class="flex-1 ml-4 font-medium">Sessions</div>
        </div>
        <div
          class="mt-4 text-sm font-light text-gray-500"
        >Your plan has {{getPlanSessionCount()}} session usage per month, session count will be reset to 0 at very first day of the month.</div>
        <div class="grid grid-cols-4 mt-3 border border-gray-300 rounded">
          <plan-free :plan_id="user.plan_id" :sessions="sessions"></plan-free>
          <plan-2000 :plan_id="user.plan_id" :sessions="sessions"></plan-2000>
          <plan-5000 :plan_id="user.plan_id" :sessions="sessions"></plan-5000>
          <plan-unlimited :plan_id="user.plan_id" :sessions="sessions"></plan-unlimited>
        </div>
      </div>

      <!-- App clicks -->
      <div class="flex flex-col p-6 mt-6 bg-white rounded shadow">
        <div class="flex items-center">
          <div class="text-gray-500">
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"
              />
            </svg>
          </div>
          <div class="flex-1 ml-4 font-medium">App views</div>
        </div>
        <div
          class="mt-4 text-sm font-light text-gray-500"
        >See how many store visitors viewed your upsells</div>
        <div class="mt-4">
          <v-chart class="chart" :option="option" v-if="option.xAxis.data.length>0" />
        </div>
      </div>

      <!-- upsells statistics -->
      <div class="flex-col p-6 mt-6 bg-white rounded shadow felx">
        <div class="flex items-center">
          <div class="text-gray-500">
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
              />
            </svg>
          </div>
          <div class="flex-1 ml-4 font-medium">Upsells overview</div>
        </div>
        <div class="mt-4 mb-10">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >Name</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >Views</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >Add to Carts</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >Transactions</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >sales</th>
                <th
                  scope="col"
                  class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
                >Take rate</th>
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
                      <div class="text-sm font-medium text-gray-900">{{ upsell.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ upsell.views_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ upsell.add_to_carts_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ upsell.transactions_count }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div
                    class="text-sm text-gray-900"
                  >{{shopCurrencySymbol}}{{ Number(upsell.sales).toFixed(2) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div
                    class="text-sm text-gray-900"
                  >{{ upsell.transactions_count * 100 / (upsell.views_count + 1) }}%</div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
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
    <!-- end content -->
  </div>
</template>

<script>
import { mapState } from "vuex";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import { use } from "echarts/core";
import { CanvasRenderer } from "echarts/renderers";
import { BarChart } from "echarts/charts";
import {
  GridComponent,
  TitleComponent,
  TooltipComponent,
  LegendComponent
} from "echarts/components";
import VChart, { THEME_KEY } from "vue-echarts";
import axios from "axios";

use([
  CanvasRenderer,
  BarChart,
  GridComponent,
  TitleComponent,
  TooltipComponent,
  LegendComponent
]);

import PlanFree from "./PlanFree.vue";
import Plan2000 from "./Plan2000.vue";
import Plan5000 from "./Plan5000.vue";
import PlanUnlimited from "./PlanUnlimited.vue";

export default {
  components: {
    DatePicker,
    VChart,
    PlanFree,
    Plan2000,
    Plan5000,
    PlanUnlimited
  },
  data() {
    return {
      views_range: [],
      option: {
        title: {
          text: ""
        },
        tooltip: {},
        legend: {
          data: ["views"]
        },
        xAxis: {
          data: []
        },
        yAxis: {},
        series: [
          {
            name: "views",
            type: "bar",
            data: []
          }
        ]
      },
      upsells: []
    };
  },
  computed: {
    ...mapState({
      user: state => state.upsellrock.user,
      currencies: state => state.upsellrock.currencies,
      views: state => state.upsellrock.views,
      sessions: state => state.upsellrock.sessions,
      statistics: state => state.upsellrock.statistics
    }),
    shopCurrencySymbol() {
      var c = this.currencies.find(
        currency => currency.currency === this.user.currency
      );
      if (c) {
        return c.currency_symbol;
      }
    }
  },
  mounted() {
    console.log("performance mounted");
    var today = new Date();
    this.views_range.unshift(today.getTime());
    today.setDate(today.getDate() - 30);
    this.views_range.unshift(today.getTime());

    if (this.views.length > 0) {
      this.option.xAxis.data = [];
      this.option.series[0].data = [];
      this.views.forEach(view => {
        this.option.xAxis.data.push(view.day);
        this.option.series[0].data.push(parseInt(view.cc));
      });
    }

    this.rangeChanged();
    this.getSessions();
  },
  methods: {
    rangeChanged() {
      this.getStatistics();
      this.getViews();
      this.getUpsellWithTracks();
    },
    getUpsellWithTracks() {
      axios
        .get(
          "/spa/upsell_with_tracks?start=" +
            this.views_range[0] +
            "&end=" +
            this.views_range[1]
        )
        .then(res => {
          console.log(res.data);
          this.upsells = res.data;
        });
    },
    getStatistics() {
      axios
        .get(
          "/spa/statistics?start=" +
            this.views_range[0] +
            "&end=" +
            this.views_range[1]
        )
        .then(res => {
          this.$store.commit("upsellrock/SET_STATISTICS", {
            statistics: res.data
          });
        });
    },
    getViews() {
      axios
        .get(
          "/spa/views?start=" +
            this.views_range[0] +
            "&end=" +
            this.views_range[1]
        )
        .then(res => {
          this.$store.commit("upsellrock/SET_VIEWS", { views: res.data });
          var views = res.data;
          this.option.xAxis.data = [];
          this.option.series[0].data = [];
          views.forEach(view => {
            this.option.xAxis.data.push(view.day);
            this.option.series[0].data.push(parseInt(view.cc));
          });
        });
    },
    getSessions() {
      axios.get("/spa/sessions").then(res => {
        this.$store.commit("upsellrock/SET_SESSIONS", { sessions: res.data });
        console.log(res.data);
      });
    },
    getPlanSessionCount() {
      if (
        this.user.plan_id === "" ||
        this.user.plan_id === null ||
        this.user.plan_id === 6
      ) {
        return 200;
      } else if (this.user.plan_id === 7) {
        return 2000;
      } else if (this.user.plan_id === 8) {
        return 5000;
      } else if (this.user.plan_id === 9) {
        return "Unlimited";
      }
    },
    gotoCCA() {
      window
        .open("https://apps.shopify.com/currency-converter-ant", "_blank")
        .focus();
    }
  }
};
</script>

<style scoped>
.chart {
  height: 400px;
}
</style>

