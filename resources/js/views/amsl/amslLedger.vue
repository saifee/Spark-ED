<template>
  <div>
    <div class="page-titles">
      <h3 class="text-themecolor">
        {{ trans('amsl.ledger') }}
      </h3>
    </div>
    <div class="container-fluid container-body">
      <div class="row">
        <div class="col-12">
          <h3>{{ trans('amsl.vat_ledger') }}</h3>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.vat_receive') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.vat_receive_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerShow',params:{id:'vat',type:'income-vat',name:'received-vat'}}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.vat_receive')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.vat_paid') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.vat_paid_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerShow',params:{id:'vat',type:'expense-vat',name:'paid-vat'}}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.vat_paid')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <h3>{{ trans('amsl.asset') }}</h3>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.receivable_ledger') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.receivable_ledger_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerAccountReceivableShow'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.receivable_ledger')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.ledger') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.ledger_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerAccountFixedAsset'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.ledger')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <h3>{{ trans('amsl.liability') }}</h3>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.payable_ledger') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.payable_ledger_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerAccountPayableShow'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.payable_ledger')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.ledger') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.ledger_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerAccountLiability'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.ledger')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <h3>{{ trans('amsl.employee') }}</h3>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.employee_ledger') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.employee_ledger_description') }}
              </p>
              <v-btn
                :to="{name:'ledgerEmployeeShow'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.employee_ledger')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
      <div
        v-if="accounts.length"
        class="row"
      >
        <div class="col-12">
          <h3>{{ trans('amsl.ledger') }}</h3>
          <v-list-item
            v-for="(account, i) in accounts"
            :key="`ledger${i}`"
            :to="{name:'ledgerShow',params:{id:account.id,type:account.account_type,name:account.name}}"
          >
            <v-list-item-action><i class="fas fa-dot-circle-o fa-fw" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ account.name }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
	export default {
     data(){
      return{
         accounts:[],
      }
    },
     created(){
        this.getAccounts()
     },
     methods:{
         getAccounts(){
                axios.get('/amsl-api'+'/account-list').then(res=>{
                    this.accounts=res
                })
            },
     }
	}
</script>
