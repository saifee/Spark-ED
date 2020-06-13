<template>
    <div>
        <div class="page-titles">
            <h3 class="text-themecolor">{{trans('exam.report_analysis')}}</h3>
        </div>
        <div class="container-fluid container-body">
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="card card-box">
                        <div class="card-body">
                            <h4 class="card-title">{{trans('exam.export_data_in_excel')}}</h4>
                            <p class="card-text font-80pc">{{trans('exam.export_data_in_excel_description')}}</p>
                            <router-link to="/exam/report/analysis/export" class="btn btn-info btn-sm">{{trans('general.go_to_link', {link: trans('exam.export_data_in_excel')})}}</router-link>
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