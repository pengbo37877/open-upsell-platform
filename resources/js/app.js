require("./bootstrap");

window.Vue = require("vue").default;
window.Vapor = require("laravel-vapor");

import VueLoading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
Vue.use(VueLoading);

import VueRouter from "vue-router";
Vue.use(VueRouter);

import store from "./store";

import UpsellRocks from "./components/UpsellRocks.vue";
import EditUpsellRock from "./components/EditUpsellRock.vue";
import Setting from "./components/Setting.vue";
import Performance from "./components/Performance.vue";

const routes = [
    {
        path: "/",
        component: UpsellRocks,
        name: "UpsellRocks"
    },
    {
        path: "/upsell/:id/edit",
        component: EditUpsellRock,
        name: "EditUpsellRock"
    },
    {
        path: "/setting",
        component: Setting,
        name: "Setting"
    },
    {
        path: "/performance",
        component: Performance,
        name: "Performance"
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

const app = new Vue({
    el: "#ant-rack-app",
    router,
    store
});
