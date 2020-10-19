export default [
    {
        path: 'dashboard',
        name: 'amslDashboard',
        component: () => import(/* webpackChunkName: "js/amsl/dashboard" */ '@views/amsl/dashboard')
    },

    //Employee History
    {
        path: 'employee/history/create',
        name: 'employeeHistoryCreate',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/history/historyCreate.vue')
    },
    {
        path: 'employee/history',
        name: 'employeeHistoryList',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/history/historyList.vue')
    },
    {
        path: 'employee/history/:id/edit',
        name: 'employeeHistoryEdit',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/history/historyEdit.vue')
    },
    //Employee
    {
        path: 'employee/create',
        name: 'employeeCreate',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/employeeCreate.vue')
    },
    {
        path: 'employee',
        name: 'employeeListAmsl',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/employeeList.vue')
    },
    {
        path: 'employee/:id/edit',
        name: 'employeeEditAmsl',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/employeeEdit.vue')
    },
    //Account

    {
        path: 'account/create',
        name: 'accountCreate',
        component: () => import(/* webpackChunkName: "js/amsl/account" */ '@views/amsl/account/accountCreate.vue')
    },
    {
        path: 'account',
        name: 'accountList',
        component: () => import(/* webpackChunkName: "js/amsl/account" */ '@views/amsl/account/accountList.vue')
    },
    {
        path: 'account/:id/edit',
        name: 'accountEdit',
        component: () => import(/* webpackChunkName: "js/amsl/account" */ '@views/amsl/account/accountEdit.vue')
    },

    //Expense

    {
        path: 'expense/create',
        name: 'expenseCreate',
        component: () => import(/* webpackChunkName: "js/amsl/expense" */ '@views/amsl/expense/expenseCreate.vue')
    },
    {
        path: 'expense',
        name: 'expenseList',
        component: () => import(/* webpackChunkName: "js/amsl/expense" */ '@views/amsl/expense/expenseList.vue')
    },
    {
        path: 'expense/:id/edit',
        name: 'expenseEdit',
        component: () => import(/* webpackChunkName: "js/amsl/expense" */ '@views/amsl/expense/expenseEdit.vue')
    },

    //Income

    {
        path: 'income/create',
        name: 'incomeCreate',
        component: () => import(/* webpackChunkName: "js/amsl/income" */ '@views/amsl/income/incomeCreate.vue')
    },
    {
        path: 'income',
        name: 'incomeList',
        component: () => import(/* webpackChunkName: "js/amsl/income" */ '@views/amsl/income/incomeList.vue')
    },
    {
        path: 'income/:id/edit',
        name: 'incomeEdit',
        component: () => import(/* webpackChunkName: "js/amsl/income" */ '@views/amsl/income/incomeEdit.vue')
    },

    //Asset

    {
        path: 'asset/create',
        name: 'assetCreate',
        component: () => import(/* webpackChunkName: "js/amsl/asset" */ '@views/amsl/asset/assetCreate.vue')
    },
    {
        path: 'asset',
        name: 'assetList',
        component: () => import(/* webpackChunkName: "js/amsl/asset" */ '@views/amsl/asset/assetList.vue')
    },
    {
        path: 'asset/:id/edit',
        name: 'assetEdit',
        component: () => import(/* webpackChunkName: "js/amsl/asset" */ '@views/amsl/asset/assetEdit.vue')
    },

    //Liabilities

    {
        path: 'liability/create',
        name: 'liabilityCreate',
        component: () => import(/* webpackChunkName: "js/amsl/liability" */ '@views/amsl/liability/liabilityCreate.vue')
    },
    {
        path: 'liability',
        name: 'liabilityList',
        component: () => import(/* webpackChunkName: "js/amsl/liability" */ '@views/amsl/liability/liabilityList.vue')
    },
    {
        path: 'liability/:id/edit',
        name: 'liabilityEdit',
        component: () => import(/* webpackChunkName: "js/amsl/liability" */ '@views/amsl/liability/liabilityEdit.vue')
    },
    //owner-equity

    {
        path: 'owner-equity/create',
        name: 'ownerEquityCreate',
        component: () => import(/* webpackChunkName: "js/amsl/owner" */ '@views/amsl/owner-equity/ownerEquityCreate.vue')
    },
    {
        path: 'owner-equity',
        name: 'ownerEquityList',
        component: () => import(/* webpackChunkName: "js/amsl/owner" */ '@views/amsl/owner-equity/ownerEquityList.vue')
    },
    {
        path: 'owner-equity/:id/edit',
        name: 'ownerEquityEdit',
        component: () => import(/* webpackChunkName: "js/amsl/owner" */ '@views/amsl/owner-equity/ownerEquityEdit.vue')
    },

    //Ledger

    {
        path: 'ledger/:id/:type/:name',
        name: 'ledgerShow',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/ledgerShow.vue')
    },
    {
        path: 'account/ledger/:id/:type/:name',
        name: 'ledgerOtherShow',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/ledgerOtherShow.vue')
    },
    {
        path: 'ledger/accounts/account-receivable',
        name: 'ledgerAccountReceivableShow',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/account/accountReceivable.vue')
    },
    {
        path: 'ledger/accounts/account-payable',
        name: 'ledgerAccountPayableShow',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/account/accountPayable.vue')
    },
    {
        path: 'ledger/employee-ledger',
        name: 'ledgerEmployeeShow',
        component: () => import(/* webpackChunkName: "js/amsl/employee" */ '@views/amsl/employee/employee-ledger.vue')
    },

    {
        path: 'ledger/asset-ledger',
        name: 'ledgerAccountFixedAsset',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/assetLedgerShow.vue')
    },
    {
        path: 'ledger/liability-ledger',
        name: 'ledgerAccountLiability',
        component: () => import(/* webpackChunkName: "js/amsl/ledger" */ '@views/amsl/ledger/liabilityLedgerShow.vue')
    },

    //Daybook

    {
        path: 'day-book/:type',
        name: 'dayBookShow',
        component: () => import(/* webpackChunkName: "js/amsl/day" */ '@views/amsl/day-book/day-book-show.vue')
    },
    //Profit and Lose

    {
        path: 'profit-loss',
        name: 'profitLoss',
        component: () => import(/* webpackChunkName: "js/amsl/profit" */ '@views/amsl/profit-loss/profit-loss-show.vue')
    },
    //Profit and Lose

    {
        path: 'financial-statement',
        name: 'financialStatementCreate',
        component: () => import(/* webpackChunkName: "js/amsl/financial" */ '@views/amsl/financial-statement/financialStatementCreate.vue')
    },

    //Cash Flow
    {
        path: 'cash-flow',
        name: 'cashFlow',
        component: () => import(/* webpackChunkName: "js/amsl/cash" */ '@views/amsl/cash-flow/cashFlowShow.vue')
    },
    //Regular form
    {
        path: 'regular',
        name: 'regularForm',
        component: () => import(/* webpackChunkName: "js/amsl/regular" */ '@views/amsl/regular/regularForm.vue')
    },

    //user

    {
        path: 'user-info',
        name: 'userDetails',
        component: () => import(/* webpackChunkName: "js/amsl/user" */ '@views/amsl/user/userShow.vue')
    },

    {
        path: 'user-info/create',
        name: 'userCreate',
        component: () => import(/* webpackChunkName: "js/amsl/user" */ '@views/amsl/user/userCreate.vue')
    },
    {
        path: 'user-info/:id/edit',
        name: 'userEdit',
        component: () => import(/* webpackChunkName: "js/amsl/user" */ '@views/amsl/user/userEdit.vue')
    },
    {
        path: 'company-info',
        name: 'companyDetails',
        component: () => import(/* webpackChunkName: "js/amsl/account" */ '@views/amsl/account/company-info.vue')
    },

]
