<template>
    <div>
        <div class="page-titles">
            <h3 class="text-themecolor">{{trans('general.report')}}</h3>
        </div>
        <div class="container-fluid container-body">
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.fee_summary_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.fee_summary_report_module_description')}}</p>
                            <v-btn to="/finance/fee/report/summary" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.fee_summary_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.fee_concession_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.fee_concession_report_module_description')}}</p>
                            <v-btn to="/finance/fee/report/concession" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.fee_concession_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.fee_due_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.fee_due_report_module_description')}}</p>
                            <v-btn to="/finance/fee/report/due" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.fee_due_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.fee_payment_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.fee_payment_report_module_description')}}</p>
                            <v-btn to="/finance/fee/report/payment" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.fee_payment_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.transaction_summary_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.transaction_summary_report_module_description')}}</p>
                            <v-btn to="/finance/transaction/report/summary" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.transaction_summary_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('finance.transaction_day_book_report')}}</h4>
                            <p class="card-text font-80pc">{{trans('finance.transaction_day_book_report_module_description')}}</p>
                            <v-btn to="/finance/transaction/report/day-book" x-small color="#009efb" dark depressed>{{trans('general.go_to_link', {link: trans('finance.transaction_day_book_report')})}}</v-btn>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">Detail</h4>
                            <p class="card-text font-80pc">Get all transaction summary including payments, receipts at one place.</p>
                            <v-btn :to="{ name: 'financeTransactionDetailReport' }" x-small color="#009efb" dark depressed>Go to Transaction Day Book Detail</v-btn>
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