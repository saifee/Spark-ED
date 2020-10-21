import comp4 from './views/amsl/components/global/DataTable.vue'
import comp5 from './views/amsl/components/global/DataTableLedger.vue'
import comp6 from './views/amsl/components/global/DataTableDayBook.vue'
import comp7 from './views/amsl/components/global/DataTableOtherLedger.vue'
import comp8 from './views/amsl/components/global/datTableRegularForm.vue'
import comp9 from './views/amsl/regular/regularCreateFrom.vue'
import comp10 from './views/amsl/regular/regularEditForm.vue'

Vue.component('amsl-data-table', comp4)
Vue.component('amsl-data-table-ledger', comp5)
Vue.component('amsl-data-table-day-book', comp6)
Vue.component('amsl-data-table-other-ledger', comp7)
Vue.component('amsl-data-table-regular-form', comp8)
Vue.component('regular-create-form', comp9)
Vue.component('regular-edit-form', comp10)

Vue.filter('currency', function (value) {
  return value; //helper.formatCurrency(value)
})
