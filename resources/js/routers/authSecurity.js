export default [
    {
        path: '/auth/security',
        name: 'authSecurity',
        component: () => import(/* webpackChunkName: "js/auth/security" */ '@views/auth/security'),
    },
    {
        path: '/auth/lock',
        name: 'authLock',
        component: () => import(/* webpackChunkName: "js/auth/lock" */ '@views/auth/lock'),
    }
]