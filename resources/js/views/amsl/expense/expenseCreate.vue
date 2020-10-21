<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fas fa-dollar-sign" /> Add Expense
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postExpense"
            >
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Date*</label>
                  <datepicker
                    v-model="expense.expense_date"

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
                  <label class="control-label">Expense Type *</label>
                  <v-select
                    v-model="expense.account"

                    outlined
                    dense
                    hide-details
                    :items="accounts"
                    item-text="name"
                    return-object
                    name="account"

                    :class="{ 'is-danger': false }"
                    @input="employeeReset"
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
                    v-model="expense.ref"

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
                      v-model="expense.amount"

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
                      v-model="expense.tax_rate"
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
                      step="any"
                      name="after_tax_amount"
                      disabled
                    >
                  </div>
                </div>

                <div
                  v-if="expense.account?!expense.account.name.includes('Depreci'):false ||expense.account?!expense.account.name.includes('depreci'):false"
                  class="form-group d-flex"
                >
                  <div class="col-md-3 text-right">
                    <label class="control-label">Payment Type *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="expense.payment_type"

                      outlined
                      dense
                      hide-details
                      :items="['Cash','Bank','Accounts Payable','Prepaid Expense']"

                      name="payment_type"

                      :class="{ 'is-danger': false }"
                      @input="employeeReset"
                    />
                    <div
                      v-show="false"
                      class="help is-danger"
                    />
                    <template v-if="expense.account.name=='Wages' ||expense.account.name=='wages'">
                      <a
                        v-if="expense.payment_type =='Cash' || expense.payment_type=='Bank' "
                        href=""
                        @click.prevent="dueField=!dueField"
                      >+ Add Due</a>
                    </template>
                  </div>
                </div>
                <div
                  v-if="expense.account?expense.account.name.includes('Depreci'):false || expense.account?expense.account.name.includes('depreci'):false"
                  class="form-group d-flex"
                >
                  <div class="col-md-3 text-right">
                    <label class="control-label">Payment Type *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="expense.payment_type"

                      outlined
                      dense
                      hide-details
                      :items="['Cash','Bank','Accounts Payable','Depreciation Fund']"

                      name="payment_type"

                      :class="{ 'is-danger': false }"
                      @input="employeeReset"
                    />
                    <div
                      v-show="false"
                      class="help is-danger"
                    >
                      {{ 'error' }}
                    </div>
                    <template v-if="expense.account.name=='Wages' ||expense.account.name=='wages'">
                      <a
                        v-if="expense.payment_type =='Cash' || expense.payment_type=='Bank' "
                        href=""
                        @click.prevent="dueField=!dueField"
                      >+ Add Due</a>
                    </template>
                  </div>
                </div>


                <div
                  v-if="dueField"
                  class="form-group d-flex"
                >
                  <div class="col-md-3  text-right">
                    <label class="control-label">Due Amount *</label>
                  </div>
                  <div class="col-md-9">
                    <input
                      v-model="expense.due_amount"

                      class="form-control"
                      type="number"
                      name="due_amount"
                      placeholder="Enter Due Amount"

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

                <div
                  v-if="expense.payment_type=='Depreciation Fund'"
                  class="form-group d-flex"
                >
                  <div class="col-md-3  text-right">
                    <label class="control-label">Asset *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="expense.asset"

                      outlined
                      dense
                      hide-details
                      :items="assets"
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

                <div
                  v-if="expense.payment_type=='Prepaid Expense'"
                  class="form-group d-flex"
                >
                  <div class="col-md-3  text-right">
                    <label class="control-label">Prepaid Asset *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="expense.asset"

                      outlined
                      dense
                      hide-details
                      :items="prepaidAssets"
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

                <div
                  v-if="expense.payment_type=='Accounts Payable'"
                  class="form-group d-flex"
                >
                  <div class="col-md-3  text-right">
                    <label class="control-label">Payable Holder *</label>
                  </div>
                  <div class="col-md-9">
                    <v-select
                      v-model="expense.payable_details"

                      outlined
                      dense
                      hide-details
                      :items="payableHolders"
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
                <template v-if="expense.account">
                  <div
                    v-if="(expense.payment_type=='Cash'|| expense.payment_type=='Bank')&&(expense.account.name=='Wages'|| expense.account.name=='Wage'||expense.account.name=='wages'||expense.account.name=='wage')"
                    class="form-group d-flex"
                  >
                    <div class="col-md-3  text-right">
                      <label class="control-label">Employee *</label>
                    </div>
                    <div class="col-md-9">
                      <v-select
                        v-model="expense.employee"

                        outlined
                        dense
                        hide-details
                        :items="employees"
                        item-text="name"
                        return-object

                        name="employee"
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
                </template>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="expense.description"
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
                expense:{tax_rate:0,expense_date:new Date()},
                accounts:[],
                employees:[],
                payableHolders:[],
                assets:[],
                dueField:false,
                prepaidAssets:[],
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: true,
                },
                btnDisabled:false,
            }
        },
        computed:{
            taxAmount:function () {
                var taxAmount=this.expense.tax_rate && this.expense.amount?(this.expense.amount*this.expense.tax_rate)/100:0
                this.expense['tax_amount']=taxAmount
                return taxAmount
            },
            afterTaxAmount:function () {

                var taxAmount=(this.expense.amount*this.expense.tax_rate)/100
                var taxAfterAmount= this.expense.amount && taxAmount? this.expense.amount-taxAmount:this.expense.amount
                this.expense['after_tax_amount']=taxAfterAmount
                return  taxAfterAmount
            },
            employeeRequired(){
                return this.expense.payment_type !='Accounts Payable'?'required':''
            },
            payableRequired(){
                return this.expense.payment_type =='Accounts Payable'?'required':''
            }
        },
        created(){
            axios.get('/amsl-api'+'/expense/create').then(res=>{
                this.accounts=res.accounts
                this.payableHolders=res.payableHolders
                this.assets=res.assets
                this.prepaidAssets=res.prepaidAssets
                this.employees=res.employees
            })
        },
        methods: {
            postExpense(){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        this.btnDisabled=true

                        this.expense['asset_id']=this.expense.asset?this.expense.asset.id:null
                        this.expense['employee_id']=this.expense.employee?this.expense.employee.id:null
                        axios.post('/amsl-api'+'/expense',this.expense).then(response => {
                            toastr.success(response.message);
                            // this.$validator.reset()
                            this.expense={tax_rate:0,expense_date:new Date()}
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
            employeeReset(){
                this.expense.employee = null
                this.expense.payable_details= null
                this.expense.asset= null
                this.dueField=false
                this.expense.due_amount=null

            },

            cancel(){
                this.$router.push({path:'/expense'})
            }

        }
    }
</script>

<style scoped>

</style>
