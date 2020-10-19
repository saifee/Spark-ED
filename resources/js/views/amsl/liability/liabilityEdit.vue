<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-th-list" /> Update Liability Transaction
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postLiability"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <date-picker
                    v-model="liability.liability_date"
                    v-validate="'required|min:3'"
                    :config="options"
                    data-vv-name="liability_date"
                  />
                  <div
                    v-show="errors.has('liability_date')"
                    class="help is-danger"
                  >
                    {{ errors.first('liability_date') }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Ref *</label>
                  <input
                    v-model="liability.ref"
                    v-validate="'required|min:3'"
                    class="form-control"
                    type="text"
                    name="ref"
                    placeholder="Enter Ref"
                    data-vv-name="ref"
                  >
                  <div
                    v-show="errors.has('ref')"
                    class="help is-danger"
                  >
                    {{ errors.first('ref') }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Liability Type *</label>
                  <v-select
                    v-model="liability.account"
                    v-validate="'required|min:3'"
                    :options="accounts"
                    label="name"
                    name="account"
                    data-vv-name="account"
                    :class="{ 'is-danger': errors.has('account') }"
                    @input="setTansactionType()"
                  />
                  <div
                    v-show="errors.has('account')"
                    class="help is-danger"
                  >
                    {{ errors.first('account') }}
                  </div>
                </div>
              </div>
              <div class="col-md-6 pt-2">
                <div class="form-group">
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="liability.transaction_type"
                    v-validate="'required|min:3'"
                    :options="transactionOptions"
                    label="name"
                    name="transaction_type"
                    data-vv-name="transaction_type"
                    :class="{ 'is-danger': errors.has('transaction_type') }"
                    @input="setPaymentType()"
                  />
                  <div
                    v-show="errors.has('transaction_type')"
                    class="help is-danger"
                  >
                    {{ errors.first('transaction_type') }}
                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label">Amount *</label>
                  <input
                    v-model="liability.amount"
                    v-validate="'required'"
                    class="form-control"
                    type="number"
                    name="amount"
                    placeholder="Enter Amount"
                    data-vv-name="amount"
                    step="any"
                  >
                  <div
                    v-show="errors.has('amount')"
                    class="help is-danger"
                  >
                    {{ errors.first('amount') }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">Payment Type *</label>
                  <v-select
                    v-model="liability.payment_type"
                    v-validate="'required|min:3'"
                    :options="paymentOptions"
                    label="name"
                    name="payment_type"
                    data-vv-name="payment_type"
                    :class="{ 'is-danger': errors.has('payment_type') }"
                    @input="resetAsset"
                  />
                  <div
                    v-show="errors.has('payment_type')"
                    class="help is-danger"
                  >
                    {{ errors.first('payment_type') }}
                  </div>
                </div>

                <div
                  v-if="liability.payment_type=='Accounts Receivable'"
                  class="form-group"
                >
                  <label class="control-label">Receivable Holder *</label>
                  <v-select
                    v-model="liability.asset"
                    v-validate="'required|min:3'"
                    :options="receivableHolders"
                    label="name"
                    name="asset"
                    data-vv-name="asset"
                    :class="{ 'is-danger': errors.has('asset') }"
                  />
                  <div
                    v-show="errors.has('asset')"
                    class="help is-danger"
                  >
                    {{ errors.first('asset') }}
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="liability.description"
                    class="form-control"
                    type="text"
                    name="description"
                    placeholder="Description"
                  >
                </div>
              </div>

              <div class="col-md-12">
                <button
                  class="btn btn-primary"
                  type="submit"
                  :disabled="btnDisabled"
                >
                  <i class="fa fa-fw fa-lg fa-check-circle" />
                  Update
                </button>
                <button
                  class="btn btn-secondary"
                  href="#"
                  @click.prevent="cancel"
                >
                  <i class="fa fa-fw fa-lg fa-times-circle" />
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    export default {
        data() {
            return {
                liability:{},
                accounts:[],
                receivableHolders:[],
                btnDisabled:false,options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
                accountType:'',
                count:0,
                anotherCount:0,
                paymentOptions:[],
                transactionOptions:[],
            }
        },
        created(){
            axios.get('/liability/'+this.$route.params.id+'/edit').then(res=>{
                this.accounts=res.data.accounts
                this.receivableHolders=res.data.receivableHolders
                this.liability=res.data.liability
                this.liability.account=res.data.liability.accountable
                this.accountType=res.data.liability.accountable_type
                this.liability.liability_date=this.parseDate(res.data.liability.liability_date)
                this.liability.asset=res.data.liability.account_receivable
            })
        },
        methods: {
            postLiability(){

                if(this.liability.account['accountable_type'] == undefined){
                    this.liability.account['accountable_type']=this.accountType
                }

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.liability['asset_id']=this.liability.asset?this.liability.asset.id:null
                        axios.patch('/liability/'+this.$route.params.id,this.liability).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$validator.reset()
                            this.btnDisabled=false
                        }).catch(error => {
                            let err
                            let errs = error.response.data.errors
                            for (err in errs) {
                                this.errors.add({
                                    'field': err,
                                    'msg': errs[err][0]
                                })
                            }
                        })
                    }
                })
            },
            resetAsset(){
                this.count++
                if(this.count>1){
                    this.liability.asset=null
                }
            },
            resetTransactionType(){
                this.anotherCount++
                if(this.anotherCount>1){
                    this.liability.transaction_type=null
                }

            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },

            cancel(){
                this.$router.push({path:'/liability'})
            },
            setTansactionType(){
                this.resetTransactionType()
                var accountType = this.liability.account.account_type
                if (accountType == 'Liabilities-AP') {
                    this.transactionOptions = ['Payment', 'Receive']
                } else if (accountType == 'Employee') {
                    this.transactionOptions = ['Payment']
                } else {
                    this.transactionOptions = ['Receive', 'Payment','Initial']
                }
            },
            setPaymentType(){
                var accountType = this.liability.account.account_type
                var tranType = this.liability.transaction_type
                if (tranType!='Initial' && (tranType=='Payment' || tranType=='Receive')) {
                    this.paymentOptions = ['Cash', 'Bank']
                }else if(tranType=='Initial'){
                    this.paymentOptions = ['Owner Equity']
                }
                else {
                    this.paymentOptions = ['Cash', 'Bank']
                }
            }

        }
    }
</script>

<style scoped>

</style>
