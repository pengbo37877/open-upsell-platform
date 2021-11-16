import Vue from "vue";

Vue.component("ant-rack", require("./components/AntRack.vue").default);

var interval = setInterval(() => {
    var antRackApp = document.getElementById("ant-rack");
    if (antRackApp) {
        const app = new Vue({
            el: "#ant-rack-app",
            mounted() {
                console.log("ant-rack mounted");
            }
        });
        clearInterval(interval);
    }
}, 100);
