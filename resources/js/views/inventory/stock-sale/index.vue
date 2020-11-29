<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('inventory_sale.stock_sale') }}
            <span
              v-if="stock_sales.total"
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.total_result_found',{count : stock_sales.total, from: stock_sales.from, to: stock_sales.to}) }}</span>
            <span
              v-else
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.no_result_found') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              v-if="stock_sales.total && !showCreatePanel && hasPermission('create-stock-sale')"
              v-tooltip="trans('general.add_new')"
              class="btn btn-info btn-sm"
              @click="showCreatePanel = !showCreatePanel"
            >
              <i class="fas fa-plus" /> <span class="d-none d-sm-inline">{{ trans('inventory_sale.add_new_stock_sale') }}</span>
            </button>
            <button
              v-if="!showFilterPanel"
              class="btn btn-info btn-sm"
              @click="showFilterPanel = !showFilterPanel"
            >
              <i class="fas fa-filter" /> <span class="d-none d-sm-inline">{{ trans('general.filter') }}</span>
            </button>
            <sort-by
              :order-by-options="orderByOptions"
              :sort-by="filter.sort_by"
              :order="filter.order"
              @updateSortBy="value => {filter.sort_by = value}"
              @updateOrder="value => {filter.order = value}"
            />
            <div class="btn-group">
              <button
                id="moreOption"
                v-tooltip="trans('general.more_option')"
                type="button"
                class="btn btn-info btn-sm dropdown-toggle no-caret "
                role="menu"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="fas fa-ellipsis-h" /> <span class="d-none d-sm-inline" />
              </button>
              <div
                :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']"
                aria-labelledby="moreOption"
              >
                <button
                  class="dropdown-item custom-dropdown"
                  @click="print"
                >
                  <i class="fas fa-print" /> {{ trans('general.print') }}
                </button>
                <button
                  class="dropdown-item custom-dropdown"
                  @click="pdf"
                >
                  <i class="fas fa-file-pdf" /> {{ trans('general.generate_pdf') }}
                </button>
              </div>
            </div>
            <help-button @clicked="help_topic = 'inventory.stock.sale'" />
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <transition name="fade">
        <div
          v-if="showFilterPanel"
          class="card card-form"
        >
          <div class="card-body">
            <h4 class="card-title">
              {{ trans('general.filter') }}
            </h4>
            <div class="row">
              <div class="col-12 col-sm-6">
                <date-range-picker
                  :start-date.sync="filter.date_start_date"
                  :end-date.sync="filter.date_end_date"
                  :label="trans('general.date_between')"
                />
              </div>
            </div>
            <div class="card-footer text-right">
              <button
                type="button"
                class="btn btn-danger"
                @click="showFilterPanel = false"
              >
                {{ trans('general.cancel') }}
              </button>
              <button
                type="button"
                class="btn btn-info waves-effect waves-light"
                @click="getStockSales"
              >
                {{ trans('general.filter') }}
              </button>
            </div>
          </div>
        </div>
      </transition>
      <transition
        v-if="hasPermission('create-stock-sale')"
        name="fade"
      >
        <div
          v-if="showCreatePanel"
          class="card card-form"
        >
          <div class="card-body">
            <h4 class="card-title">
              {{ trans('inventory_sale.add_new_stock_sale') }}
            </h4>
            <stock-sale-form
              @completed="getStockSales"
              @cancel="showCreatePanel = !showCreatePanel"
            />
          </div>
        </div>
      </transition>
      <div class="card">
        <div class="card-body">
          <div
            v-if="stock_sales.total"
            class="table-responsive"
          >
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>{{ trans('inventory_sale.stock_sale_detail') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_date') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_description') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_payment_method') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_total') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_paid') }}</th>
                  <th>{{ trans('inventory_sale.stock_sale_discount') }}</th>
                  <th class="table-option">
                    {{ trans('general.action') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="stock_sale in stock_sales.data">
                  <td>
                    <template v-if="stock_sale.type == 'student'">
                      {{ trans('student.student_name')+': '+getStudentName(stock_sale.student) }} <br>
                      {{ trans('student.first_guardian_name')+': '+stock_sale.student.parent.first_guardian_name }} <br>
                      {{ trans('student.contact_number')+': '+stock_sale.student.contact_number }} <br>
                    </template>
                  </td>
                  </td>
                  <td>{{ stock_sale.date | moment }}</td>
                  <td v-text="stock_sale.description" />
                  <td v-text="stock_sale.payment_method" />
                  <td>{{ getStockSaleTotal(stock_sale) }}</td>
                  <td>
                    <span v-if="stock_sale.payment_method === 'cash'">{{ getStockSaleTotal(stock_sale) }}</span>
                    <span
                      v-else
                      class="label label-info"
                    >{{ trans('inventory_sale.stock_sale_added_to_wallet') }}</span>
                  </td>
                  <td v-text="stock_sale.discount" />
                  <td class="table-option">
                    <div class="btn-group">
                      <button
                        v-tooltip="trans('inventory_sale.stock_sale_detail')"
                        class="btn btn-success btn-sm"
                        @click="$router.push('/inventory/stock/sale/'+stock_sale.id)"
                      >
                        <i class="fas fa-arrow-circle-right" />
                      </button>
                      <button
                        v-if="hasPermission('edit-stock-sale')"
                        v-tooltip="trans('inventory_sale.edit_stock_sale')"
                        class="btn btn-info btn-sm"
                        @click.prevent="editStockSale(stock_sale)"
                      >
                        <i class="fas fa-edit" />
                      </button>
                      <button
                        v-if="hasPermission('delete-stock-sale')"
                        :key="stock_sale.id"
                        v-confirm="{ok: confirmDelete(stock_sale)}"
                        v-tooltip="trans('inventory_sale.delete_stock_sale')"
                        class="btn btn-danger btn-sm"
                      >
                        <i class="fas fa-trash" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <module-info
            v-if="!stock_sales.total"
            module="inventory_sale"
            title="stock_sale_module_title"
            description="stock_sale_module_description"
            icon="list"
          >
            <div slot="btn">
              <button
                v-if="!showCreatePanel && hasPermission('create-stock-sale')"
                class="btn btn-info btn-md"
                @click="showCreatePanel = !showCreatePanel"
              >
                <i class="fas fa-plus" /> {{ trans('general.add_new') }}
              </button>
            </div>
          </module-info>
          <pagination-record
            :page-length.sync="filter.page_length"
            :records="stock_sales"
            @updateRecords="getStockSales"
          />
        </div>
      </div>
    </div>
    <right-panel :topic="help_topic" />
  </div>
</template>

<script>
    import stockSaleForm from './form'

    export default {
        components : { stockSaleForm },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          },
          momentTime(time) {
            return helper.formatTime(time);
          }
        },
        data() {
            return {
                stock_sales: {
                    total: 0,
                    data: []
                },
                filter: {
                    sort_by : 'date',
                    order: 'desc',
                    date_start_date: '',
                    date_end_date: '',
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'date',
                        translation: i18n.inventory.stock_sale_date
                    },
                    {
                        value: 'created_at',
                        translation: i18n.general.created_at
                    }
                ],
                showFilterPanel: false,
                showCreatePanel: false,
                help_topic: ''
            };
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getStockSales();
            },
            'filter.order': function(val){
                this.getStockSales();
            },
            'filter.page_length': function(val){
                this.getStockSales();
            }
        },
        mounted(){
            if(!helper.hasPermission('list-stock-sale')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getStockSales();
            helper.showDemoNotification(['inventory']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getStudentName(student){
                return helper.getStudentName(student);
            },
            getStockSales(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/stock/transfer?transfer_type=sale&page=' + page + url)
                    .then(response => {
                        this.stock_sales = response.stock_transfers;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getStockSaleTotal(stock_sale) {
                return stock_sale.details.map(ss => ss.quantity*ss.price).reduce((a,b) => a+b, 0)
            },
            editStockSale(stock_sale){
                this.$router.push('/inventory/stock/sale/'+stock_sale.id+'/edit');
            },
            confirmDelete(stock_sale){
                return dialog => this.deleteStockSale(stock_sale);
            },
            deleteStockSale(stock_sale){
                let loader = this.$loading.show();
                axios.delete('/api/stock/transfer/'+stock_sale.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getStockSales();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/stock/transfer/print',{filter: this.filter})
                    .then(response => {
                        let print = window.open("/print");
                        loader.hide();
                        print.document.write(response);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            pdf(){
                let loader = this.$loading.show();
                axios.post('/api/stock/transfer/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            formatCurrency(amount) {
                return helper.formatCurrency(amount);
            }
        }
    }
</script>
