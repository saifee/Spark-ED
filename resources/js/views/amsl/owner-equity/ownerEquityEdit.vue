<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-th-list" /> Update Owner EquityTransaction
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postEquity"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <date-picker
                    v-model="equity.equity_date"
                    v-validate="'required|min:3'"
                    :config="options"
                    data-vv-name="equity_date"
                  />
                  <div
                    v-show="errors.has('equity_date')"
                    class="help is-danger"
                  >
                    {{ errors.first('equity_date') }}
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Ref *</label>
                  <input
                    v-model="equity.ref"
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
                  <label class="control-label">Equity Type *</label>
                  <v-select
                    v-model="equity.account"
                    v-validate="'required'"
                    :options="['Capital','Withdraw']"
                    label="name"
                    name="account"
                    data-vv-name="account"
                    :class="{ 'is-danger': errors.has('account') }"
                    @input="resetTransactionType"
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
                <div
                  v-if="equity.account=='Capital'"
                  class="form-group"
                >
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="equity.transaction_type"
                    v-validate="'required|min:3'"
                    :options="['Receive']"
                    label="name"
                    name="transaction_type"
                    data-vv-name="transaction_type"
                    :class="{ 'is-danger': errors.has('transaction_type') }"
                  />
                  <div
                    v-show="errors.has('transaction_type')"
                    class="help is-danger"
                  >
                    {{ errors.first('transaction_type') }}
                  </div>
                </div>
                <div
                  v-if="equity.account=='Withdraw'"
                  class="form-group"
                >
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="equity.transaction_type"
                    v-validate="'required|min:3'"
                    :options="['Payment']"
                    label="name"
                    name="transaction_type"
                    data-vv-name="transaction_type"
                    :class="{ 'is-danger': errors.has('transaction_type') }"
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
                    v-model="equity.amount"
                    v-validate="'required'"
                    class="form-control"
                    type="text"
                    name="amount"
                    placeholder="Enter Amount"
                    data-vv-name="amount"
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
                    v-model="equity.payment_type"
                    v-validate="'required|min:3'"
                    :options="['Cash','Bank']"
                    label="name"
                    name="payment_type"
                    data-vv-name="payment_type"
                    :class="{ 'is-danger': errors.has('payment_type') }"
                    @input="equity.liability=null"
                  />
                  <div
                    v-show="errors.has('payment_type')"
                    class="help is-danger"
                  >
                    {{ errors.first('payment_type') }}
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="equity.description"
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
                equity:{},
                receivableHolders:[],
                btnDisabled:false,options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
                count:0,
                anotherCount:0,
            }
        },
        created(){
            axios.get('/owner-equity/'+this.$route.params.id+'/edit').then(res=>{
                this.receivableHolders=res.data.receivableHolders
                this.equity=res.data.equity
                this.equity.equity_date=this.parseDate(res.data.equity.equity_date)
                this.equity.asset=res.data.equity.account_receivable
            })
        },
        methods: {
            postEquity(){

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.equity['liability_id']=this.equity.liability?this.equity.liability.id:null
                        axios.patch('/owner-equity/'+this.$route.params.id,this.equity).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
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
                    this.equity.asset=null
                }
            },
            resetTransactionType(){
                this.anotherCount++
                if(this.anotherCount>1){
                    this.equity.transaction_type=null
                }

            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },

            cancel(){
                this.$router.push({path:'/owner-equity'})
            }

        }
    }
</script>

<style scoped>

</style>
