export default function auth({ next, store }) {
    let isLoggedIn = store.getters.getIsLoggedIn;

    if (!isLoggedIn) {
        return next({
            name: 'NotFound'
        })
    }

    return next()
}