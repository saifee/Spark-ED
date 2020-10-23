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
                  <datepicker
                    v-model="equity.equity_date"

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
                    v-model="equity.ref"

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
                  <label class="control-label">Equity Type *</label>
                  <v-select
                    v-model="equity.account"

                    outlined
                    dense
                    hide-details
                    :items="['Capital','Withdraw']"

                    name="account"

                    :class="{ 'is-danger': false }"
                    @input="resetTransactionType"
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
                <div
                  v-if="equity.account=='Capital'"
                  class="form-group"
                >
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="equity.transaction_type"

                    outlined
                    dense
                    hide-details
                    :items="['Receive']"

                    name="transaction_type"

                    :class="{ 'is-danger': false }"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
                <div
                  v-if="equity.account=='Withdraw'"
                  class="form-group"
                >
                  <label class="control-label">Transaction Type *</label>
                  <v-select
                    v-model="equity.transaction_type"

                    outlined
                    dense
                    hide-details
                    :items="['Payment']"

                    name="transaction_type"

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
                    v-model="equity.amount"

                    class="form-control"
                    type="text"
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
                <div class="form-group">
                  <label class="control-label">Payment Type *</label>
                  <v-select
                    v-model="equity.payment_type"

                    outlined
                    dense
                    hide-details
                    :items="['Cash','Bank']"

                    name="payment_type"

                    :class="{ 'is-danger': false }"
                    @input="equity.liability=null"
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
    import { VSelect } from 'vuetify/lib'
    export default {
        components: {
          VSelect,
        },
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
            axios.get('/amsl-api'+'/owner-equity/'+this.$route.params.id+'/edit').then(res=>{
                this.receivableHolders=res.receivableHolders
                this.equity=res.equity
                this.equity.equity_date=this.parseDate(res.equity.equity_date)
                this.equity.asset=res.equity.account_receivable
            })
        },
        methods: {
            postEquity(){

                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.equity['liability_id']=this.equity.liability?this.equity.liability.id:null
                        axios.patch('/amsl-api'+'/owner-equity/'+this.$route.params.id,this.equity).then(response => {
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


