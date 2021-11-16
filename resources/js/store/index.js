import Vue from 'vue'
import Vuex from 'vuex'
import upsellrock from "./modules/upsellrock"

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        upsellrock
    }
})
