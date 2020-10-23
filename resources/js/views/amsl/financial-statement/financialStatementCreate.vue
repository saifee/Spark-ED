<template>
  <div>
    <div class="card p-4">
      <div class="col-md-12 mt-3 d-flex justify-content-end">
        <div class="search-container mr-1">
          <datepicker
            v-model="from_date"
            :bootstrap-styling="true"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container mr-1">
          <datepicker
            v-model="to_date"
            :bootstrap-styling="true"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container  mr-1">
          <button @click.prevent="getData(),getProfitLossData(),getPreviouProfitLossData(),searched=true">
            Search
          </button>
        </div>
      </div>
      <div class="col-md-9 mx-auto pb-4">
        <div class="report_title">
          <span v-if="$root.$data.company">{{ $root.$data.company.name }}</span>
          <span>Balance Sheet </span>
          <span>FOR THE PERIOD
            <template v-if="from_date && to_date && searched">
              <strong>{{ $root.parseDateISO(from_date) | moment("DD MMMM, YYYY") }}</strong>  To <strong>{{ $root.parseDateISO(to_date) | moment("DD MMMM, YYYY") }}</strong>
            </template>
            <template v-else>
              (All Data From Starting to End)
            </template>

          </span>
          <template v-if="$root.$data.company">
            <p>Contact: {{ $root.$data.company.mobile }}</p>
            <p>Address: {{ $root.$data.company.address }}</p>
          </template>
          <span @click.prevent="printMe">  Print Now  <i class="fa fa-print fs-19 pl-1" /></span>
          <span @click.prevent="$root.downloadExcel('section-to-print')">  Download Excel  <i class="fa fa-file-excel-o pl-1" /></span>
        </div>
        <table
          id="section-to-print"
          align="center"
          class="profit_Table"
        >
          <thead>
            <tr>
              <td colspan="4">
                <div
                  id="tableTop"
                  style="display: none;text-align: center"
                >
                  <h3 v-if="$root.$data.company">
                    {{ $root.$data.company.name }}
                  </h3><br>
                  <span>Balance Sheet </span><br>
                  <span>FOR THE PERIOD
                    <template v-if="from_date && to_date && searched">
                      <strong>{{ $root.parseDateISO(from_date) | moment("DD MMMM, YYYY") }}</strong>  To <strong>{{ $root.parseDateISO(to_date) | moment("DD MMMM, YYYY") }}</strong>
                    </template>
                    <template v-else>
                      (All Data From Starting to End)
                    </template>

                  </span>
                  <template v-if="$root.$data.company">
                    <p>Contact: {{ $root.$data.company.mobile }}</p>
                    <p>Address: {{ $root.$data.company.address }}</p>
                  </template>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="3">
                Description
              </td>

              <td>Amounts</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td
                class="font-weight-bold"
                colspan="4"
              >
                (1) Fixed Assets
              </td>
            </tr>
            <tr>
              <td
                class="pl-4 fs-13"
                colspan="3"
              >
                Tangible Asset
              </td>
              <td>
                {{ fixedAsset }}
              </td>
            </tr>
            <!--  <tr v-for="fixedAsset in fixedAssets">
                        <td class="pl-4 fs-13" colspan="3"> {{fixedAsset?fixedAsset.name:''}}</td>
                        <td>{{
                            (parseFloat(fixedAsset.assetAmount?fixedAsset.assetAmount:0)-
                            parseFloat(fixedAsset.expenseAmount?fixedAsset.expenseAmount:0)).toFixed(2)
                            }}
                        </td>

                    </tr>-->
            <tr>
              <td
                class="font-weight-bold"
                colspan="4"
              >
                (2) Current  Assets
              </td>
            </tr>
            <tr>
              <td class="pl-4 fs-13">
                Cash in Hand
              </td>
              <td>{{ cashAmount }}</td>
              <td />
              <td />
            </tr>
            <tr>
              <td class="pl-4 fs-13">
                Cash at Bank
              </td>
              <td>{{ bankAmount }}</td>
              <td />
              <td />
            </tr>
            <tr v-for="currentAsset in currentAssets">
              <td class="pl-4 fs-13">
                {{ currentAsset?currentAsset.name:'' }}
              </td>
              <td>
                {{
                  (parseFloat(currentAsset.assetAmount?currentAsset.assetAmount:0)+
                    parseFloat(currentAsset.expenseAmount?currentAsset.expenseAmount:0)).toFixed(2)
                }}
              </td>
              <td />
              <td />
            </tr>
            <tr>
              <td class="pl-4 fs-13">
                Account Receivable
              </td>
              <td>{{ accountReceivable }}</td>
              <td />
              <td />
            </tr>
            <template v-if="assetVat>0">
              <tr>
                <td
                  class="pl-4 fs-13"
                  colspan="2"
                >
                  Others
                </td>
              </tr>
              <tr>
                <td class="pl-5 fs-13">
                  Receivable VAT
                </td>
                <td>{{ assetVat }}</td>
              </tr>
            </template>
            <tr>
              <td
                class="font-weight-bold text-right"
                colspan="2"
              >
                Total  Current Assets
              </td>

              <td>
                {{ currentAsset }}
              </td>
              <td />
            </tr>
            <!--<tr>-->
            <!--<td class="font-weight-bold text-right" >Total  Assets	</td>-->

            <!--<td>-->
            <!--<div class="jer">-->
            <!--{{totalAsset}}-->
            <!--</div></td>-->

            <!--</tr>-->
            <tr>
              <td
                class="font-weight-bold"
                colspan="4"
              >
                (3) Short Term Liabilities
              </td>
            </tr>
            <tr v-for="shortTermLiabilite in shortTermLiabilites">
              <td class="pl-4 fs-13">
                {{ shortTermLiabilite?shortTermLiabilite.name:'' }}
              </td>
              <td>{{ shortTermLiabilite.amount?shortTermLiabilite.amount:0 }}</td>
              <td />
              <td />
            </tr>
            <tr>
              <td class="pl-4 fs-13">
                Accounts Payable
              </td>
              <td>
                {{ accountsPayable }}
              </td>
              <td />
              <td />
            </tr>
            <template v-if="liabilityVat>0">
              <tr>
                <td
                  class="pl-4 fs-13"
                  colspan="2"
                >
                  Others
                </td>
                <td />
                <td />
              </tr>
              <tr>
                <td class="pl-5 fs-13">
                  Paidable VAT
                </td>
                <td>{{ liabilityVat }}</td>
                <td />
                <td />
              </tr>
            </template>

            <tr>
              <td
                class="font-weight-bold text-right"
                colspan="2"
              >
                Total  Current Liabilites
              </td>

              <td>
                {{ currentLiability }}
              </td>
              <td />
            </tr>

            <tr>
              <td
                class="font-weight-bold"
                colspan="3"
              >
                (4) NET CURRENT ASSETS:(2 less 3=)
              </td>
              <td>{{ netCurrentAsset }}</td>
            </tr>
            <tr>
              <td
                class="font-weight-bold"
                colspan="3"
              >
                (5) Gross Assets-  sub Total- (1+4=)
              </td>
              <td>
                <div class="jer">
                  {{ grossAsset }}
                </div>
              </td>
            </tr>

            <tr>
              <td
                class="font-weight-bold"
                colspan="4"
              >
                (6) Long Term Liabilities
              </td>
            </tr>

            <tr v-for="longTermLiabilite in longTermLiabilites">
              <td
                class="pl-4 fs-13"
                colspan="3"
              >
                {{ longTermLiabilite?longTermLiabilite.name:'' }}
              </td>
              <td>{{ longTermLiabilite.amount?longTermLiabilite.amount:0 }}</td>
            </tr>

            <!--<tr>-->
            <!--<td class="font-weight-bold text-right" colspan="2">Total  Liabilities	</td>-->
            <!--<td class="font-weight-bold text-right"> {{totalLiabilities}}</td>-->
            <!--<td></td>-->
            <!--</tr>-->
            <tr>
              <td
                class="font-weight-bold"
                colspan="3"
              >
                Net Asset (5 less 6)
              </td>
              <td>
                <div class="jer">
                  {{ netAsset }}
                </div>
              </td>
            </tr>

            <tr>
              <td
                colspan="4"
                style="border: none; height: 50px"
              />
            </tr>

            <tr>
              <td
                class="font-weight-bold"
                colspan="4"
              >
                CAPITAL ACCOUNT
              </td>
            </tr>
            <tr>
              <td
                class="pl-4 fs-13"
                colspan="3"
              >
                BALANCE
              </td>
              <td>{{ capitalAmount }}</td>
            </tr>
            <tr>
              <td
                class="pl-4 fs-13"
                colspan="3"
              >
                PROFIT-
              </td>
              <td>{{ (totalIncome-totalExpense-tax).toFixed(2) }}</td>
            </tr>



            <tr>
              <td
                class="pl-4 fs-13"
                colspan="3"
              />
              <td>{{ (Number(capitalAmount)+(Number(totalIncome)-Number(totalExpense)-Number(tax))).toFixed(2) }}</td>
            </tr>
            <tr>
              <td
                class="pl-4 fs-13"
                colspan="3"
              >
                Dividend-
              </td>
              <td>{{ withdraw?withdraw.amount:0 }}</td>
            </tr>
            <tr>
              <td
                class="font-weight-bold"
                colspan="3"
              >
                BALANCE CARRIED FORWARD
              </td>
              <td>
                <div class="jer">
                  {{
                    ((parseFloat(capitalAmount)+(totalIncome-totalExpense-tax))-
                      parseFloat(withdraw?withdraw.amount:0)).toFixed(2)
                  }}
                </div>
              </td>
            </tr>
            <!--<tr>-->
            <!--<td class="font-weight-bold text-right" > Grand Total ( <small>Total liabilities + Total Owner Equity</small> ) </td>-->
            <!--<td>-->
            <!--<div class="jer">-->
            <!--{{((parseFloat(totalLiabilities?totalLiabilities:0)+-->
            <!--parseFloat(capitalAmount))+-->
            <!--(totalIncome-totalExpense-tax)- -->
            <!--parseFloat(withdraw?withdraw.amount!=null?withdraw.amount:0:0)).toFixed(2)-->
            <!--}}-->
            <!--</div>-->
            <!--</td>-->
            <!--</tr>-->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
    export default {

        data() {
            return {
                dateOptions: {
                    format: 'DD-MM-YYYY',
                    useCurrent: true,
                },
                searched:false,
                from_date:null,
                to_date:null,
                fixedAssets:[],
                currentAssets:[],
                currentAssetsArs:[],
                longTermLiabilites:[],
                shortTermLiabilites:[],
                liabilitesAps:[],
                liabilitesApsEmloyee:[],
                capital:[],
                withdraw:[],
                incomes:[],
                expenses:[],
                tax:0,
                cash:{},
                bank:{},
                otherCapital:{},
                liabilityVat:0,
                assetVat:0,
                previousProfitLoss:0,
            }
        },
        computed:{
            netAsset:function () {
                return (Number(this.grossAsset)-Number(this.longTermLiabiliteis)).toFixed(2)
            },
            grossAsset:function () {
              return (Number(this.fixedAsset)+Number(this.netCurrentAsset)).toFixed(2)
            },
            netCurrentAsset:function () {
              return (Number(this.currentAsset)-Number(this.currentLiability)).toFixed(2)
            },
            currentLiability:function () {
                var amount=0;
                this.shortTermLiabilites.forEach(value=>{
                    amount += Number(value.amount)
                })
                this.liabilitesAps.forEach(value=>{
                    amount += Number(value.liabilitiesAmount)+Number(value.expenseAmount)+
                        Number(value.equityAmount)+Number(value.assetAmount)
                })
                this.liabilitesApsEmloyee.forEach(value=>{
                    amount += Number(value.liabilitiesAmount)+Number(value.expenseAmount)
                })
                amount += Number(this.liabilityVat)
                return amount.toFixed(2)
            },
            currentAsset:function () {
                var amount=0;
                this.currentAssets.forEach(value=>{
                    amount += Number(value.assetAmount)
                    amount += Number(value.expenseAmount)
                })
                this.currentAssetsArs.forEach(value=>{
                    amount += Number(value.assetAmount)
                    amount += Number(value.incomeAmount)
                    amount += Number(value.liabilitiesAmount)
                })
                amount=amount+(Number(this.cashAmount))+(Number(this.bankAmount))
                amount += Number(this.assetVat)
                return amount.toFixed(2)
            },
            fixedAsset:function () {
                var amount=0;
                this.fixedAssets.forEach(value=>{
                    amount += Number(value.assetAmount)
                    amount -= Number(value.expenseAmount)
                })
                return amount.toFixed(2)
            },
            longTermLiabiliteis:function () {
                var amount=0;
                this.longTermLiabilites.forEach(value=>{
                    amount += Number(value.amount)
                })
                return amount.toFixed(2)
            },

            totalIncome:function () {
                var amount=0;
                this.incomes.forEach(value=>{
                    amount += Number(value.amount)
                })
                return amount.toFixed(2)
            },
            totalExpense:function () {
                var amount=0;
                this.expenses.forEach(value=>{
                    amount += Number(value.amount)+Number(value.prepaid_amount)
                })
                return amount.toFixed(2)
            }
            ,
            cashAmount:function () {
                var amount = Number(this.cash?this.cash.assetCashAmount:0)-
                    Number(this.cash?this.cash.expenseAmount:0)+
                    Number(this.cash?this.cash.incomeAmount:0)+
                    Number(this.cash?this.cash.liabilityAmount:0)+
                    Number(this.cash?this.cash.initialCashAmount:0)+
                    Number(this.cash?this.cash.equityAmount:0)
                return amount.toFixed(2)
            },
            bankAmount:function () {
                var amount = Number(this.bank?this.bank.assetBankAmount:0)-
                    Number(this.bank?this.bank.expenseAmount:0)+
                    Number(this.bank?this.bank.incomeAmount:0)+
                    Number(this.bank?this.bank.liabilityAmount:0)+
                    Number(this.bank?this.bank.initialBankAmount:0)+
                    Number(this.bank?this.bank.equityAmount:0)
                return amount.toFixed(2)

            },
            capitalAmount:function () {
                var amount = Number(this.capital?this.capital.amount:0)+
                    Number(this.otherCapital?this.otherCapital.assetEquityAmount:0)+
                    Number(this.otherCapital?this.otherCapital.liabilityAmount:0)

                amount=amount+Number(this.previousProfitLoss)
                return amount.toFixed(2)

            },
            accountsPayable:function () {
                var amount=0;
                this.liabilitesAps.forEach(value=>{
                    amount += Number(value.liabilitiesAmount)+Number(value.assetAmount)+Number(value.expenseAmount)
                        +Number(value.equityAmount)
                })
                this.liabilitesApsEmloyee.forEach(value=>{

                    amount += Number(value.liabilitiesAmount)+Number(value.expenseAmount)

                })
                return amount.toFixed(2)

            },
            accountReceivable:function () {
                var amount=0;
                this.currentAssetsArs.forEach(value=>{
                    amount += Number(value.assetAmount)+Number(value.incomeAmount)-
                        Number(value.liabilitiesAmount)

                })
                return amount.toFixed(2)

            },



        },
        created(){
            this.getData()
            this.getProfitLossData()
        },
        methods:{
            getData(){
                axios.get('/amsl-api'+'/financial-statement',{
                    params:{
                        fromDate:this.from_date,
                        toDate:this.to_date,
                    }
                }).then(res=>{
                    this.fixedAssets=res.fixedAssets
                    this.currentAssets=res.currentAssets
                    this.currentAssetsArs=res.currentAssetsArs
                    this.longTermLiabilites=res.longTermLiabilites
                    this.shortTermLiabilites=res.shortTermLiabilites
                    this.liabilitesAps=res.liabilitesAps
                    this.capital=res.capital
                    this.withdraw=res.withdraw
                    this.cash=res.cash
                    this.bank=res.bank
                    this.otherCapital=res.otherCapital
                    this.liabilitesApsEmloyee=res.liabilitesApsEmloyee
                    this.liabilityVat=res.liabilityVat
                    this.assetVat=res.assetVat

                })
            },
            getProfitLossData(){
                axios.get('/amsl-api'+'/profit-loss',{
                    params:{
                        fromDate:this.from_date,
                        toDate:this.to_date,
                    }
                }).then(res=>{
                    this.incomes=res.incomes
                    this.expenses=res.expenses
                    this.tax=res.tax
                })
            },
            getPreviouProfitLossData(){

                axios.get('/amsl-api'+'/profit-loss',{
                    params:{
                        fromDate:'01-01-1971',
                        toDate:this.subDayOne(this.from_date),

                    }
                }).then(res=>{

                    var incomeAmount=0;
                    res.incomes.forEach(value=>{
                        incomeAmount += Number(value.amount)
                    })


                    var expenseAmount=0;
                    res.expenses.forEach(value=>{
                        expenseAmount += Number(value.amount)+Number(value.prepaid_amount)
                    })
                   expenseAmount.toFixed(2)
                    this.previousProfitLoss=(incomeAmount-expenseAmount-res.tax).toFixed(2)

                })
            },
            subDayOne(date){
                var month = date.slice(3,5)
                var year = date.slice(6,10)
                var day = date.slice(0,2)

                var day
                if(day==1){
                    day=31
                    if(month==1){
                        month=12
                        year=Number(year-1)
                    }else{
                        month=month-1
                    }
                }else {
                    day=day-1
                    if(month==1){
                        month=12
                        year=Number(year-1)
                    }else{
                        month=month-1
                    }
                }
                return day+'-'+month+'-'+year

            },
            printMe(){
                window.print()
            },
        }

    }
</script>
