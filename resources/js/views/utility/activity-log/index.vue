<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('utility.activity_log')}}
                        <span class="card-subtitle d-none d-sm-inline" v-if="activity_logs.total">{{trans('general.total_result_found',{count : activity_logs.total, from: activity_logs.from, to: activity_logs.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="!showFilterPanel" @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> <span class="d-none d-sm-inline">{{trans('general.filter')}}</span></button>
                        <sort-by :order-by-options="orderByOptions" :sort-by="filter.sort_by" :order="filter.order" @updateSortBy="value => {filter.sort_by = value}"  @updateOrder="value => {filter.order = value}"></sort-by>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <transition name="fade">
                <div class="card card-form" v-if="showFilterPanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('general.filter')}}</h4>
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('user.user')}}</label>
                                    <v-autocomplete :items="users" outlined dense :label="trans('user.user')" item-value="id" item-text="username" v-model="filter.user_id"></v-autocomplete>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <date-range-picker :start-date.sync="filter.start_date" :end-date.sync="filter.end_date" :label="trans('general.date_between')"></date-range-picker>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('utility.activity_log_description')}}</label>
                                    <input class="form-control" name="name" v-model="filter.description">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" @click="showFilterPanel = false" class="btn btn-danger">{{trans('general.cancel')}}</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" @click="getActivityLogs">{{trans('general.filter')}}</button>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="activity_logs.total">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{trans('utility.activity_log_activity_id')}}</th>
                                    <th>{{trans('utility.activity_log_event_time')}}</th>
                                    <th>{{trans('utility.activity_log_user_id')}}</th>
                                    <th>{{trans('utility.activity_log_user_name')}}</th>
                                    <th>{{trans('utility.activity_log_ip_address')}}</th>
                                    <th>{{trans('utility.activity_log_log_name')}}</th>
                                    <th>{{trans('utility.activity_log_subject_id')}}</th>
                                    <th>{{trans('utility.activity_log_description')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity_log in activity_logs.data">
                                    <td v-text="activity_log.id"></td>
                                    <td>{{activity_log.created_at | moment }}</td>
                                    <td v-text="activity_log.causer_id"></td>
                                    <td v-text="activity_log.user_name"></td>
                                    <td>{{JSON.parse(activity_log.properties).ip}}</td>
                                    <td v-text="activity_log.log_name"></td>
                                    <td v-text="activity_log.subject_id"></td>
                                    <td v-text="activity_log.description"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!activity_logs.total" module="utility" title="activity_log_module_title" description="activity_log_module_description" icon="list"></module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="activity_logs" @updateRecords="getActivityLogs" @change.native="getActivityLogs"></pagination-record>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        components: {},
        data(){
            return {
                activity_logs: {
                    total: 0,
                    data: []
                },
                filter: {
                    page_length: helper.getConfig('page_length'),
                    sort_by: 'created_at',
                    order: 'desc'
                },
                activity_log: {},
                orderByOptions: [
                    {
                        value: 'created_at',
                        translation: i18n.general.created_at
                    }
                ],
                showFilterPanel: false,
                users: [],
                showDetailModal: false
            };
        },
        mounted(){
            if(!helper.featureAvailable('activity_log')){
                helper.featureNotAvailableMsg();
                this.$router.push('/dashboard');
            }

            if(!helper.hasPermission('access-configuration')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getActivityLogs();
            this.getUsers();
        },
        methods: {
            getUsers(){
                axios.get('/api/yousers')
                    .then(response => {
                        this.users = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getActivityLogs(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/activity-log?page=' + page + url)
                    .then(response => {
                        this.activity_logs = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
        },
        filters: {
          moment(date) {
            return helper.formatDateTime(date);
          }
        },
        watch: {
            filter: {
                handler(val){
                    this.getActivityLogs();
                },
                deep: true
            }
        }
    }
</script>
