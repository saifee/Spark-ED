<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container modal-lg">
                    <div class="modal-header">
                        <slot name="header">
                            {{trans('finance.fee_payment')}}
                            <span class="float-right pointer" @click="$emit('closeFeePaymentForm')">&times;</span>
                        </slot>
                    </div>
                    <div class="modal-body">
                        <slot name="body">
                            <h4>{{feePayment.fee_group_name}} <span class="pull-right">{{feePayment.date | moment}}</span></h4>
                            <div style="max-height:600px;">
                                <form @submit.prevent="submit" @keydown="studentWalletPaymentForm.errors.clear($event.target.name)">
                                    <div class="table-responsive p-2">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>{{trans('general.total')}}</th>
                                                    <th colspan="2"></th>
                                                    <th class="text-right" v-bind:class="{ 'text-danger': total < 0, 'text-success': total > 0 }">{{formatCurrency(total)}}</th>
                                                </tr>
                                                <tr v-if="hasPermission('make-partial-fee-payment')">
                                                    <th>{{trans('finance.amount')}}</th>
                                                    <th colspan="2"></th>
                                                    <th class="text-right">
                                                        <input class="invoice-input" type="text" v-model="studentWalletPaymentForm.amount" name="amount" :placeholder="trans('finance.amount')">
                                                        <show-error :form-name="studentWalletPaymentForm" prop-name="amount"></show-error>
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="">{{trans('finance.fee_payment_remarks')}}</label>
                                                <autosize-textarea v-model="studentWalletPaymentForm.remarks" rows="2" name="remarks" :placeholder="trans('finance.fee_payment_remarks')"></autosize-textarea>
                                                <show-error :form-name="studentWalletPaymentForm" prop-name="remarks"></show-error>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info waves-effect waves-light pull-right">{{trans('general.save')}}</button>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        components: {},
        props: ['id','uuid','feePayment'],
        data() {
            return {
                studentWalletPaymentForm: new Form({
                    amount: 0,
                    date: '',
                    remarks: ''
                }),
                total: 0,
            }
        },
        mounted() {
            this.loadFeePayment(this.feePayment);
            this.getPreRequisite();
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            loadFeePayment(feePayment){
                this.studentWalletPaymentForm.date = feePayment.date;
                this.studentWalletPaymentForm.amount = feePayment.amount < 0 ? 0 - feePayment.amount : 0;
                this.total = feePayment.amount;
            },
            getPreRequisite(){
            },
            formatCurrency(amount){
                return helper.formatCurrency(amount);
            },
            submit(){
                this.studentWalletPaymentForm.date = moment(this.studentWalletPaymentForm.date).format('YYYY-MM-DD');
                let loader = this.$loading.show();
                this.studentWalletPaymentForm.post('/api/student/'+this.uuid+'/wallet_payment/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            }
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        }
    }
</script>
