<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('finance.transaction_summary_report')}} 
                        <span class="card-subtitle d-none d-sm-inline" v-if="list.total">{{trans('general.total_result_found',{count : list.total, from: list.from, to: list.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                            </button>
                            <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                <button class="dropdown-item custom-dropdown" @click="print"><i class="fas fa-print"></i> {{trans('general.print')}}</button>
                                <button class="dropdown-item custom-dropdown" @click="pdf"><i class="fas fa-file-pdf"></i> {{trans('general.generate_pdf')}}</button>
                            </div>
                        </div>
                        <help-button @clicked="help_topic = 'finance.transaction.report.summary'"></help-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="list.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{trans('finance.voucher_number')}}</th>
                                    <th>{{trans('finance.date')}}</th>
                                    <th>{{trans('finance.payment')}}</th>
                                    <th>{{trans('finance.receipt')}}</th>
                                    <th>{{trans('finance.fee_concession')}}</th>
                                    <th>{{trans('general.description')}}</th>
                                    <th>{{trans('finance.payment_method')}}</th>
                                    <th>{{trans('general.entry_by')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in list.data">
                                    <td>{{item.sno}}</td>
                                    <td>{{item.voucher_number}}</td>
                                    <td>{{item.date | moment}}</td>
                                    <td>{{item.type == 'payment' ? item.amount : '-'}}</td>
                                    <td>{{item.type == 'receipt' ? item.amount : '-'}}</td>
                                    <td>{{item.fee_concession}}</td>
                                    <td>{{item.head}}</td>
                                    <td>
                                        {{item.payment_method}} <p v-if="item.payment_method_detail" v-html="item.payment_method_detail"></p>
                                    </td>
                                    <td>{{item.employee}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3"></th>
                                    <th>{{footer.total_payments}}</th>
                                    <th>{{footer.total_receipts}}</th>
                                    <th>{{footer.total_concessions}}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <h4 v-if="footer.fee_summary" style="margin-left: 20px;">{{trans('finance.fee_summary_report')}}</h4>
                    <ul style="list-style: none;">
                        <li v-for="(fee_head,index) in footer.fee_summary">{{index}}: {{fee_head}}</li>
                    </ul>
                    <module-info v-if="!list.total" module="finance" title="transaction_summary_report_module_title" description="transaction_summary_report_module_description" icon="list">
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="list" @updateRecords="getReport"></pagination-record>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
    </div>
</template>

<script>
    export default {
        components : {},
        data() {
            return {
                list: {
                    total: 0,
                    data: []
                },
                footer: [],
                filter: {
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'created_at',
                        translation: i18n.general.created_at
                    }
                ],
                accounts: [],
                payment_methods: [],
                transaction_categories: [],
                selected_account: null,
                selected_payment_method: null,
                help_topic: ''
            };
        },
        mounted(){
            if(!helper.hasPermission('access-transaction-report')){
                // helper.notAccessibleMsg();
                // this.$router.push('/dashboard');
            }
            this.getPreRequisite();
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/transaction/report/pre-requisite')
                    .then(response => {
                        this.accounts = response.accounts;
                        this.payment_methods = response.payment_methods;
                        this.transaction_categories = response.transaction_categories;
                        this.getReport();
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },  
            getReport(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL({});
                axios.get('/api/transaction/report/detail?page=' + page + url)
                    .then(response => {
                        this.list = response.list;
                        this.footer = response.footer;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config){
                return helper.getConfig(config);
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/transaction/report/detail/print',{})
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
                axios.post('/api/transaction/report/detail/pdf',{})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
    }
</script>