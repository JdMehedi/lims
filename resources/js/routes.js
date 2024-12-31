import { createRouter, createWebHashHistory } from 'vue-router';

// import process routes
import ProcessRoutes from '../../app/Modules/ProcessPath/resources/js/routes/index.js'
// import settings routes
import SettingsRoutes from './routes/settings.js'


let routes = [
    {
        path: '/not-found',
        component: () => import(/* webpackChunkName: "NotFound" */ './components/NotFound'),
        name: 'NotFound',
    }
]


routes = routes.concat(ProcessRoutes, SettingsRoutes)

const router = createRouter({
    history: createWebHashHistory(),
    // base: process.env.BASE_URL,
    routes
})


import store from './store/store';
import middlewarePipeline from './middleware/middleware-pipeline'

router.beforeEach((to, from, next) => {

    /** Navigate to next if middleware is not applied */
    if (!to.meta.middleware) {
        return next()
    }

    const middleware = to.meta.middleware;
    const context = {
        to,
        from,
        next,
        store
    }

    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    })
})

export default router;
