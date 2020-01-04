<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('student_wallet.student_wallet')}} <small v-if="student_record.student">{{getStudentName(student_record.student)}}  ({{student_record.academic_session.name}})</small>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <router-link to="/student/admission" class="btn btn-info btn-sm"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('student.student')}}</span></router-link>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                            </button>
                            <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                <button class="dropdown-item custom-dropdown" @click="$router.push('/student/'+student_record.student.uuid)"><i class="fas fa-arrow-circle-right"></i> {{trans('student.view_detail')}}</button>
                                <button class="dropdown-item custom-dropdown" @click="print"><i class="fas fa-print"></i> {{trans('general.print')}}</button>
                                <button class="dropdown-item custom-dropdown" @click="pdf"><i class="fas fa-file-pdf"></i> {{trans('general.generate_pdf')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-4">
                            <student-summary :student-record="student_record" class="border-bottom"></student-summary>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-between px-4" v-if="hasPermission('customize-fee-date')">
                                <div class="col-12 col-sm-7">
                                    <h3 class="card-title">
                                        {{trans('student_wallet.student_wallet_balance')}}:
                                        <span v-if="student_record.student" v-bind:class="{ 'text-danger': student_record.student.wallet < 0, 'text-success': student_record.student.wallet > 0 }">{{formatCurrency(student_record.student.wallet)}}</span>
                                        <button type="button" class="btn btn-info btn-sm" @click="showInstallmentDetail()">{{trans('finance.pay_fee')}}</button>
                                    </h3>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <datepicker v-model="feePayment.date" :bootstrapStyling="true" :placeholder="trans('student.date_of_payment')"></datepicker>
                                    </div>
                                </div>
                            </div>

                            <h4 class="card-title m-l-20">{{trans('student_wallet.student_wallet_transactions')}}</h4>
                            <div class="table-responsive p-3">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{trans('finance.fee_installment_title')}}</th>
                                            <th>{{trans('student_wallet.date')}}</th>
                                            <th>{{trans('student_wallet.debit')}}</th>
                                            <th>{{trans('student_wallet.credit')}}</th>
                                            <th>{{trans('student_wallet.description')}}</th>
                                            <th class="table-option">{{trans('general.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="fee_installment in student_wallet_transactions">
                                            <td>
                                                <span v-if="fee_installment.transactionable_type.indexOf('StockTransfer') !== -1">Purchase</span>
                                                <span v-if="fee_installment.transactionable_type.indexOf('StudentWalletPayment') !== -1">Payment</span>
                                            </td>
                                            <td>{{fee_installment.date | moment}}</td>
                                            <td>{{fee_installment.debit}}</td>
                                            <td>{{fee_installment.credit}}</td>
                                            <td>{{fee_installment.description}}</td>
                                            <td class="table-option">
                                                <div class="btn-group">
                                                    <button class="btn btn-success btn-sm" v-tooltip="trans('inventory_sale.stock_sale_detail')" @click="$router.push('/inventory/stock/sale/'+fee_installment.transactionable_id)" v-if="fee_installment.transactionable_type.indexOf('StockTransfer') !== -1"><i class="fas fa-arrow-circle-right"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <student-wallet-payment-form v-if="studentWalletPaymentForm && hasNotAnyRole(['parent','student']) && hasPermission('make-fee-payment')" :uuid="uuid" :id="record_id" :fee-payment="feePayment" @completed="paymentMade" @closeFeePaymentForm="studentWalletPaymentForm = false"></student-wallet-payment-form>
    </div>
</template>

<script>
    import studentSummary from './../summary'
    import studentWalletPaymentForm from './payment-wallet'

    export default {
        components : {studentSummary,studentWalletPaymentForm},
        data() {
            return {
                uuid:this.$route.params.uuid,
                record_id:this.$route.params.record_id,
                student_wallet_transactions: [],
                student_record: {},
                feePayment: {
                    date: moment().format('YYYY-MM-DD'),
                    amount: 0
                },
                fee: {},
                studentWalletPaymentForm: false,
            };
        },
        mounted(){
            if(!helper.hasPermission('list-student-fee')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getRecord();
            helper.showDemoNotification(['student']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            hasAnyRole(roles){
                return helper.hasAnyRole(roles);
            },
            hasNotAnyRole(roles){
                return helper.hasNotAnyRole(roles);
            },
            getConfig(config){
                return helper.getConfig(config);
            },
            getStudentName(student){
                return helper.getStudentName(student);
            },
            getRecord(){
                let loader = this.$loading.show();
                this.studentWalletPaymentForm = false;
                axios.get('/api/student/'+this.uuid+'/wallet/'+this.record_id)
                    .then(response => {
                        this.student_record = response.student_record;
                        this.student_wallet_transactions = response.student_wallet_transactions;
                        this.feePayment.amount = response.student_record.student.wallet;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            formatCurrency(amount){
                return helper.formatCurrency(amount);
            },
            showInstallmentDetail(){
                this.studentWalletPaymentForm = true;
            },
            paymentMade(){
                this.getRecord();
            },
            showDate(date){
                return helper.formatDate(date);
            },
            print(){
            },
            pdf(){
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
            },
        }
    }
</script>

<style>
.loading-overlay.is-full-page{
    z-index: 1060;
}
</style>
