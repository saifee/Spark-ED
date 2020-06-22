export default [
    {
        path: '/utility/todo/:id/manage',
        component: () => import(/* webpackChunkName: "js/utility/todo/manage" */ '@views/utility/todo/manage')
    },
    {
        path: '/configuration/employee/asset/category',
        component: () => import(/* webpackChunkName: "js/configuration/employee/asset-category/index" */ '@views/configuration/employee/asset/category/index'),
        meta: { menu: 'module-configuration'}
    },
    {
        path: '/configuration/employee/asset/category/:id/edit',
        component: () => import(/* webpackChunkName: "js/configuration/employee/asset-category/edit" */ '@views/configuration/employee/asset/category/edit'),
        meta: { menu: 'module-configuration'}
    },
    {
        path: '/configuration/employee/asset/item',
        component: () => import(/* webpackChunkName: "js/configuration/employee/asset-item/index" */ '@views/configuration/employee/asset/item/index'),
        meta: { menu: 'module-configuration'}
    },
    {
        path: '/configuration/employee/asset/item/:id/edit',
        component: () => import(/* webpackChunkName: "js/configuration/employee/asset-item/edit" */ '@views/configuration/employee/asset/item/edit'),
        meta: { menu: 'module-configuration'}
    },
    {
        path: '/employee/asset/transfer',
        component: () => import(/* webpackChunkName: "js/employee/asset-transfer/index" */ '@views/employee/asset/transfer/index')
    },
    {
        path: '/employee/asset/transfer/:id/edit',
        component: () => import(/* webpackChunkName: "js/employee/asset-transfer/edit" */ '@views/employee/asset/transfer/edit')
    },
    {
        path: '/employee/asset/transfer/:id',
        component: () => import(/* webpackChunkName: "js/employee/asset-transfer/show" */ '@views/employee/asset/transfer/show')
    }
]