import authMiddleware from '../../../../../../resources/js/middleware/authMiddleware'

let routes = [
    {
        path: '/list/:process_type_id?',
        component: () => import(/* webpackChunkName: "CommonList" */ '../components/CommonList.vue'),
        name: 'CommonList',
        // As this is the first route, and vue app created here so, IsLoggedIn store not found here
        // meta: {
        //     middleware: [
        //         authMiddleware
        //     ]
        // },
    },
    {
        path: '/:module/add/:process_type_id',
        component: () => import(/* webpackChunkName: "CommonForm" */ '../components/CommonForm.vue'),
        name: 'ApplicationAdd',
        meta: {
            middleware: [
                authMiddleware
            ]
        },
    },
    {
        path: '/:module/edit/:app_id/:process_type_id',
        component: () => import(/* webpackChunkName: "CommonForm" */ '../components/CommonForm.vue'),
        name: 'ApplicationEdit',
        meta: {
            middleware: [
                authMiddleware
            ]
        },
    },
    {
        path: '/:module/view/:app_id/:process_type_id',
        component: () => import(/* webpackChunkName: "CommonForm" */ '../components/CommonForm.vue'),
        name: 'ApplicationView',
        meta: {
            // middleware: [
            //     authMiddleware
            // ]
        },
    },
]

export default routes;