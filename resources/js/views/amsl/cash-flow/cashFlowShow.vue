<template>
  <div>
    <div class="card p-4">
      <div class="col-md-12 mt-3 d-flex justify-content-end hideOnPrint">
        <div class="search-container mr-1">
          <date-picker
            v-model="from_date"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container mr-1">
          <date-picker
            v-model="to_date"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container ">
          <button @click.prevent="getData(),previousBalace(),searched=true">
            Search
          </button>
        </div>
      </div>
      <div class="col-md-9 mx-auto pb-5">
        <div class="report_title">
          <span v-if="$root.$data.company">{{ $root.$data.company.name }}</span>
          <span>CASH FLOW STATEMENT </span>
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
          <span @click.prevent="printMe">  Print Now  <i class="fa fa-print fs-19 pl-1" /> </span>
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
                  <span>CASH FLOW STATEMENT</span><br>
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
              <td>Particulars </td>
              <td>Amounts(£)</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-uppercase font-weight-bold">
                1:Opening Balance
              </td>
              <td>{{ openingBalance }}</td>
            </tr>
            <tr>
              <td
                class="text-uppercase font-weight-bold"
                colspan="2"
              >
                2:Turnover SALES
              </td>
            </tr>
            <tr v-for="income in incomes">
              <td class="pl-4 fs-13">
                {{ income.name }}
              </td>
              <td>{{ income.amount!=null?income.amount:0 }}</td>
            </tr>

            <tr>
              <td class="font-weight-bold">
                3:Gross Cash Flow(1+2)
              </td>
              <td class="font-weight-bold">
                {{ (Number(totalIncome)+ Number(openingBalance)).toFixed(2) }}
              </td>
            </tr>

            <tr>
              <td
                class="font-weight-bold pl-2"
                colspan="2"
              >
                Overheads:
              </td>
            </tr>
            <tr v-for="item in expenses">
              <td class="pl-4 fs-13">
                {{ item.name }}
              </td>
              <td>{{ item.amount }}</td>
            </tr>
            <tr v-for="item in asset">
              <td class="pl-4 fs-13">
                {{ item.name }}
              </td>
              <td>{{ item.amount }}</td>
            </tr>
            <tr v-for="item in liabilityEm">
              <td class="pl-4 fs-13">
                {{ item.name }}
              </td>
              <td>{{ item.amount }}</td>
            </tr>
            <tr v-for="item in liability">
              <td class="pl-4 fs-13">
                {{ item.name }}
              </td>
              <td>{{ item.amount }}</td>
            </tr>

            <tr>
              <td class="font-weight-bold">
                4:Total Outgoing
              </td>
              <td class="font-weight-bold">
                {{ totalOutgoing }}
              </td>
            </tr>
            <tr>
              <td
                class="font-weight-bold pl-2"
                colspan="2"
              >
                Cash Flow Investing
              </td>
            </tr>
            <tr v-for="item in equity">
              <td class="pl-4 fs-13">
                {{ item.account }}
              </td>
              <td>{{ item.amount }}</td>
            </tr>

            <tr>
              <td class="font-weight-bold">
                5:Total Investing
              </td>
              <td class="font-weight-bold">
                {{ ownerInvestment }}
              </td>
            </tr>


            <tr>
              <td class="font-weight-bold text-right">
                6:Closing Balance[(3+5)-4]
              </td>
              <td class="font-weight-bold text-right">
                <div class="jer">
                  {{ ((Number(totalIncome)+Number(ownerInvestment)+Number(openingBalance))-Number(totalOutgoing)).toFixed(2) }}
                </div>
              </td>
            </tr>
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
                incomes:[],
                expenses:[],
                asset:[],
                liability:[],
                liabilityEm:[],
                equity:[],
                openingBalanceData:0,
                tax:0,
                cash:0,
                bank:0,
            }
        },
        computed:{
            totalIncome:function () {
                var amount=0;
                this.incomes.forEach(value=>{
                    amount += Number(value.amount)
                })
                return amount.toFixed(2)
            },
            totalOutgoing:function () {
                var amount=0;
                this.expenses.forEach(value=>{
                    amount += Number(value.amount)
                })
                this.asset.forEach(value=>{
                    amount += Number(value.amount)
                })
                this.liability.forEach(value=>{
                    amount += Number(value.amount)
                })

                this.liabilityEm.forEach(value=>{
                    amount += Number(value.amount)
                })

                return amount.toFixed(2)
            },
            ownerInvestment:function () {
                var amount=0;
                this.equity.forEach(value=>{
                    amount += Number(value.amount)
                })

                return amount.toFixed(2)
            },
            openingBalance:function () {
                var amount= Number(this.openingBalanceData)
                    + Number(this.cash)
                    +Number(this.bank)

                return amount.toFixed(2)
            }
        },
        created(){
            this.getData()
        },
        methods:{
            printMe(){
                window.print()
            },
            getData(){
                axios.get('/amsl-api'+'cash-flow',{
                    params:{
                        fromDate:this.from_date,
                        toDate:this.to_date,
                    }
                }).then(res=>{
                    this.incomes=res.data.incomes
                    this.expenses=res.data.expenses
                    this.asset=res.data.asset
                    this.liability=res.data.liability
                    this.liabilityEm=res.data.liabilityEm
                    this.equity=res.data.equity
                    this.cash=res.data.cash?res.data.cash.amount:0
                    this.bank=res.data.bank?res.data.bank.amount:0
                })
            },
            previousBalace(){
                axios.get('/amsl-api'+'cash-flow-previous-balance',{
                    params:{
                        fromDate:'01-01-1971',
                        toDate:this.subDayOne(this.from_date),
                    }
                }).then(res=>{
                    this.previousCash=res.data.previousCash
                    this.previousBank=res.data.previousBank
                })
            },
            subDayOne(date){
                // Vue moment date issue not working
                    //Refactor later.
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

        }

    }
</script>
