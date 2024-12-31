import Vuex from 'vuex'
import Auth from "./AuthStore";

const store = new Vuex.Store({
    modules: {
        Auth: Auth
    },
    state: {
        globalError: false,
    },
    mutations: {
        setGlobalError(state, error) {
            state.globalError = error;
        },
    },
});

export default store;