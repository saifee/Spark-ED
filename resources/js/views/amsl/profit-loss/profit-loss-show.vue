<template>
  <div>
    <div class="card p-4">
      <div class="col-md-12 mt-3 d-flex justify-content-end">
        <div class="search-container mr-1">
          <datepicker
            v-model="from_date"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container mr-1">
          <datepicker
            v-model="to_date"
            :config="dateOptions"
            @input="searched=false"
          />
        </div>
        <div class="search-container  mr-1">
          <button @click.prevent="getData(),searched=true">
            Search
          </button>
        </div>
      </div>
      <div class="col-md-9 mx-auto pb-5">
        <div class="report_title">
          <span v-if="$root.$data.company">{{ $root.$data.company.name }}</span>
          <span>PROFIT AND LOSE ACCOUNT </span>

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
                  <span>PROFIT AND LOSE ACCOUNT </span><br>
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
              <td>Amounts(£) </td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td
                class="text-uppercase font-weight-bold"
                colspan="3"
              >
                Turn Over Sales
              </td>
            </tr>
            <tr v-for="income in incomes">
              <td class="pl-4 fs-13">
                {{ income.name }}
              </td>
              <td>{{ income.amount != null ? income.amount : 0 }}</td>
            </tr>
            <tr>
              <td class="font-weight-bold text-right">
                Total Income
              </td>
              <td>{{ totalIncome }}</td>
            </tr>
            <tr>
              <td class="font-weight-bold text-right">
                Gross Profit
              </td>
              <td>{{ totalIncome }}</td>
            </tr>

            <tr>
              <td
                class="font-weight-bold"
                colspan="3"
              >
                EXPENDITURE:
              </td>
            </tr>
            <tr v-for="expense in expenses">
              <td class="pl-4 fs-13">
                {{ expense.name }}
              </td>
              <td>
                {{ expense.amount != null ? expense.prepaid_amount ? (parseFloat(expense.amount) + parseFloat(expense.prepaid_amount)).toFixed(2) : expense.amount : 0 }}
              </td>
            </tr>
            <tr>
              <td class="font-weight-bold text-right">
                Total Expense
              </td>
              <td>{{ totalExpense }}</td>
            </tr>
            <tr>
              <td class="font-weight-bold text-right">
                Profit Before Tax
              </td>
              <td>{{ (totalIncome - totalExpense).toFixed(2) }}</td>
            </tr>
            <tr>
              <td class="text-right">
                Tax Provision
              </td>
              <td>{{ tax.toFixed(2) }}</td>
            </tr>
            <tr>
              <td class="font-weight-bold text-right">
                Profit After Tax
              </td>
              <td class="font-weight-bold text-right">
                <div class="jer">
                  {{ ((totalIncome - totalExpense) - tax).toFixed(2) }}
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
                searched: false,
                from_date: null,
                to_date: null,
                incomes: [],
                expenses: [],
                tax: 0,
            }
        },
        computed: {

            totalIncome: function () {
                var amount = 0;
                this.incomes.forEach(value => {
                    amount += Number(value.amount)
                })
                return amount.toFixed(2)
            },
            totalExpense: function () {
                var amount = 0;
                this.expenses.forEach(value => {
                    amount += Number(value.amount) + Number(value.prepaid_amount)
                })
                return amount.toFixed(2)
            }
        },
        created() {
            this.getData()
        },
        methods: {
            getData() {
                axios.get('/amsl-api'+'profit-loss', {
                    params: {
                        fromDate: this.from_date,
                        toDate: this.to_date,
                    }
                }).then(res => {
                    this.incomes = res.incomes
                    this.expenses = res.expenses
                    this.tax = Number(res.tax)
                })
            },

            printMe() {
                window.print()
            },

        }

    }
</script>
