<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="far fa-money-bill-alt" /> Add Income / Sales
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postIncome"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <datepicker
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
                  <label class="control-label">Income  Type *</label>
                  <v-select
                    v-model="income.account"

                    outlined
                    dense
                    hide-details
                    :items="accounts"
                    item-text="name"
                    return-object

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

                      step="any"
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

                      outlined
                      dense
                      hide-details
                      :items="['Cash','Bank','Accounts Receivable']"

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

                      outlined
                      dense
                      hide-details
                      :items="receivableHolders"
                      item-text="name"
                      return-object

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
                income:{tax_rate:0,income_date:new Date()},
                accounts:[],
                receivableHolders:[],
                btnDisabled:false,
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
            axios.get('/amsl-api'+'/income/create').then(res=>{
                this.accounts=res.accounts
                this.receivableHolders=res.receivableHolders
            })
        },
        methods: {
            postIncome(){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=true
                        this.income['asset_id']=this.income.payable_details?this.income.payable_details.id:null
                        axios.post('/amsl-api'+'/income',this.income).then(response => {
                            toastr.success(response.message);
                            // this.$validator.reset()
                            this.income={tax_rate:0,income_date:new Date()}
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
            receivableReset(){
                this.income.payable_details=null
            },
            cancel(){
                this.$router.push({path:'/income'})
            }

        }
    }
</script>

<style scoped>

</style>
