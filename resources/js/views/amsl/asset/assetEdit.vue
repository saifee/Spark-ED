<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-th-list" /> - Update Asset Transaction
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postAsset"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <datepicker
                    v-model="asset.asset_date"
                    :bootstrap-styling="true"

                    :config="options"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Ref *</label>
                  <input
                    v-model="asset.ref"

                    class="form-control"
                    type="text"
                    name="ref"
                    placeholder="Enter Ref"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Asset Type *</label>
                  <v-select
                    v-model="asset.account"

                    outlined
                    dense
                    hide-details
                    :items="accounts"
                    item-text="name"
                    return-object

                    name="account"

                    :class="{ 'is-danger': false }"
                    @input="changePaymentType(),changeTransactionType()"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-6 pt-2">
                <div class="form-group">
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="asset.transaction_type"

                    outlined
                    dense
                    hide-details
                    :items="transactionOptions"

                    name="transaction_type"

                    :class="{ 'is-danger': false }"
                    @input="changePaymentType()"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
                <div
                  v-if="asset.transaction_type=='Adjust'"
                  class="form-group"
                >
                  <label class="control-label">Adjust For*</label>
                  <v-select
                    v-model="asset.expense"

                    outlined
                    dense
                    hide-details
                    :items="expenses"
                    item-text="name"
                    return-object

                    name="expense"

                    :class="{ 'is-danger': false }"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">Amount *</label>
                  <input
                    v-model="asset.amount"

                    class="form-control"
                    type="number"
                    step="any"
                    name="amount"
                    placeholder="Enter Amount"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
                <div
                  v-if="asset.transaction_type!='Adjust'"
                  class="form-group"
                >
                  <label class="control-label">Payment Type *</label>
                  <v-select
                    v-model="asset.payment_type"

                    outlined
                    dense
                    hide-details
                    :items="paymentOptions"

                    name="payment_type"

                    :class="{ 'is-danger': false }"
                    @input="resetLiability"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>

                <div
                  v-if="asset.payment_type=='Accounts Payable'"
                  class="form-group"
                >
                  <label class="control-label">Payable Holder *</label>
                  <v-select
                    v-model="asset.liability"

                    outlined
                    dense
                    hide-details
                    :items="payableHolders"
                    item-text="name"
                    return-object

                    name="liability"

                    :class="{ 'is-danger': false }"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="asset.description"
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
    import { VSelect } from 'vuetify/lib'
    export default {
        components: {
          VSelect,
        },
        data() {
            return {
                asset:{},
                accounts:[],
                payableHolders:[],
                paymentOptions: [],
                transactionOptions: [],
                btnDisabled:false,options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
                count:0,
                anotherCount:0,
            }
        },
        created(){
            axios.get('/amsl-api'+'/asset/'+this.$route.params.id+'/edit').then(res=>{
                this.accounts=res.accounts
                this.payableHolders=res.payableHolders
                this.asset=res.asset
                this.asset.liability=res.asset.account_payable
                this.asset.asset_date=this.parseDate(res.asset.asset_date)
            })
        },
        methods: {
            postAsset(){

                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.asset['liability_id']=this.asset.liability?this.asset.liability.id:null
                        axios.patch('/amsl-api'+'/asset/'+this.$route.params.id,this.asset).then(response => {
                            toastr.success(response.message);
                            this.btnDisabled=false
                        }).catch(error => {
                            let err
                            let errs = error.response.data.errors
                            for (err in errs) {
                                /* this.errors.add */({
                                    'field': err,
                                    'msg': errs[err][0]
                                })
                            }
                        })
                    }
                })
            },
            resetLiability(){
                this.count++
                if(this.count>1){
                    this.asset.liability=null
                }
            },
            resetTransactionType(){
                this.anotherCount++
                if(this.anotherCount>1){
                    this.asset.transaction_type=null
                }

            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },
            cancel(){
                this.$router.push({path:'/asset'})
            },
            changePaymentType() {
                var accountType = this.asset.account.account_type
                var tranType = this.asset.transaction_type
                if (accountType == 'Fixed Asset' && (tranType != 'Sold' && tranType!='Initial')) {

                    this.paymentOptions = ['Cash', 'Bank', 'Accounts Payable', 'Owner Equity']
                } else if ((accountType == 'Current Asset' || tranType == 'Sold')&& tranType!='Initial') {
                    this.paymentOptions = ['Cash', 'Bank', 'Owner Equity']
                } else if(tranType == 'Initial'){
                    this.paymentOptions = ['Owner Equity']
                }
                else {
                    this.paymentOptions = ['Cash', 'Bank']
                }
            },
            changeTransactionType() {
                this.resetTransactionType();
                var accountType = this.asset.account.account_type
                if (accountType == 'Fixed Asset') {
                    this.transactionOptions = ['Purchase', 'Sold', 'Initial']
                } else if (accountType == 'Current Asset') {
                    this.transactionOptions = ['Initial', 'Adjust']
                } else {
                    this.transactionOptions = ['Receive', 'Payment']
                }


            }

        }
    }
</script>
