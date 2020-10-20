<template>
  <div>
    <div class="page-titles">
      <h3 class="text-themecolor">
        {{ trans('general.report') }}
      </h3>
    </div>
    <div class="container-fluid container-body">
      <div class="row">
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.cash_flow_report') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.cash_flow_report_module_description') }}
              </p>
              <v-btn
                :to="{name:'cashFlow'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.cash_flow_report')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.profit_loss_report') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.profit_loss_report_module_description') }}
              </p>
              <v-btn
                :to="{name:'profitLoss'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.profit_loss_report')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.balance_sheet_report') }}
              </h4>
              <p class="card-text font-80pc">
                {{ trans('amsl.balance_sheet_report_module_description') }}
              </p>
              <v-btn
                :to="{name:'financialStatementCreate'}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.balance_sheet_report')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
	export default {

	}
</script>
