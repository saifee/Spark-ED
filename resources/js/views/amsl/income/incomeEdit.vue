<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="tile">
          <h3 class="tile-title">
            Update Income / Sales
          </h3>
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postIncome"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <date-picker
                    v-model="income.income_date"

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
                  <label class="control-label">Inocme Type *</label>
                  <v-select
                    v-model="income.account"

                    :options="accounts"
                    label="name"
                    name="account"

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
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Ref *</label>
                  <input
                    v-model="income.ref"

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
              <div class="col-md-6 pt-2">
                <div class="form-group d-flex">
                  <div class="col-md-3  text-right">
                    <label class="control-label">Amount *</label>
                  </div>
                  <div class="col-md-9">
                    <input
                      v-model="income.amount"

                      class="form-control"
                      type="number"
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
                </div>
                <div class="form-group d-flex ">
                  <div class="col-md-3 text-right">
                    <label class="control-label">VAT(%) </label>
                  </div>
                  <div class="col-md-9">
                    <input
                      v-model="income.tax_rate"
                      class="form-control"
                      type="number"
                      step="any"
                      name="tax_rate"
                      placeholder="Enter Tax"
                    >
                  </div>
                </div>
                <div class="form-group d-flex">
                  <div class="col-md-3  text-right">
                    <label class="control-label">VAT Amount </label>
                  </div>
                  <div class="col-md-9">
                    <input
                      v-model="taxAmount"
                      class="form-control"
                      type="text"
                      step="any"
                      name="tax_amount"
                      disabled
                    >
                  </div>
                </div>
                <div class="form-group d-flex">
                  <div class="col-md-3  text-right">
                    <label class="control-label">Total Amount </label>
                  </div>
                  <div class="col-md-9">
                    <input
                      v-model="afterTaxAmount"
                      class="form-control"
                      type="text"
                      name="after_tax_amount"
                      disabled
                    >
                  </div>
                </div>

                <div class="form-group d-flex">
                  <div class="col-md-3 text-right">
                    <label class="control-label">Payment Type *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="income.payment_type"

                      :options="['Cash','Bank','Accounts Receivable']"
                      label="name"
                      name="payment_type"

                      :class="{ 'is-danger': false }"
                      @input="receivableReset"
                    />
                    <div
                      v-show="false"
                      class="help is-danger"
                    >
                      {{ 'error' }}
                    </div>
                  </div>
                </div>
                <div
                  v-if="income.payment_type=='Accounts Receivable'"
                  class="form-group d-flex"
                >
                  <div class="col-md-3  text-right">
                    <label class="control-label">Receivable Holder*</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="income.payable_details"

                      :options="receivableHolders"
                      label="name"
                      name="payable_details"

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
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="income.description"
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
                income:{tax_rate:0},
                accounts:[],
                receivableHolders:[],
                btnDisabled:false,
                count:0,
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
            }
        },
        computed:{
            taxAmount:function () {
                var taxAmount=this.income.tax_rate && this.income.amount?(this.income.amount*this.income.tax_rate)/100:0
                this.income['tax_amount']=taxAmount
                return taxAmount
            },
            afterTaxAmount:function () {

                var taxAmount=(this.income.amount*this.income.tax_rate)/100
                var taxAfterAmount= this.income.amount && taxAmount? this.income.amount-taxAmount:this.income.amount
                this.income['after_tax_amount']=taxAfterAmount
                return  taxAfterAmount
            }
        },
        created(){
            axios.get('/amsl-api'+'/income/'+this.$route.params.id+'/edit').then(res=>{
                this.accounts=res.data.accounts
                this.receivableHolders=res.data.receivableHolders
                this.income=res.data.income
                this.income.payable_details=res.data.income.account_receivable
                this.income.income_date=res.data.income.income_date?this.parseDate(res.data.income.income_date):null


            })
        },
        methods: {
            postIncome(){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=false
                        this.income['asset_id']=this.income.payable_details?this.income.payable_details.id:null
                        axios.patch('/amsl-api'+'/income/'+this.$route.params.id,this.income).then(response => {
                            /* this.$swal */({
                                // type: response.data.type,
                                // title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.btnDisabled=true
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
            receivableReset(){
                this.count++
                if(this.count>1){
                    this.income.payable_details=null
                }

            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },
            cancel(){
                this.$router.push({path:'/income'})
            }

        }
    }
</script>

<style scoped>

</style>
