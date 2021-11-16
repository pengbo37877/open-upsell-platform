var state = {
    user: {},
    shop: {},
    setting: {},
    currencies: [],
    upsells: [],
    products: [],
    local_products: [],
    custom_collections: [],
    smart_collections: [],
    local_collections: [],
    views: [],
    sessions: [],
    statistics: {}
}

var getters = {
    getUser: (state) => {
        return state.user;
    }

}

var mutations = {
    SET_USER(state, { user }) {
        state.user = user;
    },
    SET_SHOP(state, { shop }) {
        state.shop = shop;
    },
    SET_SETTING(state, { setting }) {
        state.setting = setting
    },
    SET_CURRENCIES(state, { currencies }) {
        state.currencies = currencies
    },
    SET_UPSELLS(state, { upsells }) {
        state.upsells = upsells;
    },
    SET_PRODUCTS(state, { products }) {
        state.products = products
    },
    SET_LOCAL_PRODUCTS(state, { local_products }) {
        state.local_products = local_products
    },
    SET_CUSTOM_COLLECTIONS(state, { custom_collections }) {
        state.custom_collections = custom_collections
    },
    SET_SMART_COLLECTIONS(state, { smart_collections }) {
        state.custom_collections = smart_collections
    },
    SET_LOCAL_COLLECTIONS(state, { local_collections }) {
        state.local_collections = local_collections
    },
    SET_VIEWS(state, { views }) {
        state.views = views;
    },
    SET_SESSIONS(state, { sessions }) {
        state.sessions = sessions;
    },
    SET_STATISTICS(state, { statistics }) {
        state.statistics = statistics
    }
}

var actions = {
    allCollections: state => {
        return [].concat(state.custom_collections).concat(state.smart_collections)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
