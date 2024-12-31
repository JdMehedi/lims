export default {

    state: {
        isLoggedIn: false,
        user: []
    },

    getters: {
        getUser(state) {
            return state.user;
        },
        getIsLoggedIn(state) {
            return state.isLoggedIn;
        }
    },

    actions: {
        getAuthenticatedUserData(context) {
            axios.get("/vue/get-auth-data")

                .then((response) => {
                    context.commit("setUser", response.data) //categories will be run from mutation
                    context.commit("setIsLoggedIn", true) //categories will be run from mutation
                })

                .catch(() => {

                    console.log("Error........")

                })
        }
    },

    mutations: {
        setUser(state, data) {
            state.user = data;
        },
        setIsLoggedIn(state, data) {
            state.isLoggedIn = data;
        }
    }
}