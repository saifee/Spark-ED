<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-th-list" /> Add Liability Transaction
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
                    v-model="liability.liability_date"

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
                    v-model="liability.ref"

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
                  <label class="control-label">Liability Type *</label>
                  <v-select
                    v-model="liability.account"

                    outlined
                    dense
                    hide-details
                    :items="accounts"
                    item-text="name"
                    return-object

                    name="account"

                    :class="{ 'is-danger': false }"
                    @input="setTansactionType()"
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
                    v-model="liability.transaction_type"

                    outlined
                    dense
                    hide-details
                    :items="transactionOptions"

                    name="transaction_type"

                    :class="{ 'is-danger': false }"
                    @input="setPaymentType()"
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
                    v-model="liability.amount"

                    class="form-control"
                    type="number"
                    name="amount"
                    placeholder="Enter Amount"

                    step="any"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">Payment Type *</label>
                  <v-select
                    v-model="liability.payment_type"

                    outlined
                    dense
                    hide-details
                    :items="paymentOptions"

                    name="payment_type"

                    :class="{ 'is-danger': false }"
                    @input="liability.asset=null"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>

                <div
                  v-if="liability.payment_type=='Accounts Receivable'"
                  class="form-group"
                >
                  <label class="control-label">Receivable Holder *</label>
                  <v-select
                    v-model="liability.asset"

                    outlined
                    dense
                    hide-details
                    :items="receivableHolders"
                    item-text="name"
                    return-object

                    name="asset"

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
                  Add
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
                liability:{liability_date:new Date()},
                accounts:[],
                receivableHolders:[],
                btnDisabled:false,options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
                paymentOptions:[],
                transactionOptions:[],
            }
        },
        created(){
            axios.get('/amsl-api'+'/liability/create').then(res=>{
                this.accounts=res.accounts
                this.receivableHolders=res.receivableHolders
            })
        },
        methods: {
            postAsset(){

                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.liability['asset_id']=this.liability.asset?this.liability.asset.id:null
                        axios.post('/amsl-api'+'/liability',this.liability).then(response => {
                            toastr.success(response.message);
                            // this.$validator.reset()
                            this.liability={liability_date:new Date()}
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
            cancel(){
                this.$router.push({path:'/liability'})
            },
            setTansactionType(){
                this.liability.transaction_type=null
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
