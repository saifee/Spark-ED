<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.asset_transfer')}}
                        <span class="card-subtitle d-none d-sm-inline" v-if="asset_transfers.total">{{trans('general.total_result_found',{count : asset_transfers.total, from: asset_transfers.from, to: asset_transfers.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="asset_transfers.total && !showCreatePanel && hasPermission('create-asset-transfer')" @click="showCreatePanel = !showCreatePanel" v-tooltip="trans('general.add_new')"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.add_new_asset_transfer')}}</span></button>
                        <button class="btn btn-info btn-sm" v-if="!showFilterPanel" @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> <span class="d-none d-sm-inline">{{trans('general.filter')}}</span></button>
                        <sort-by :order-by-options="orderByOptions" :sort-by="filter.sort_by" :order="filter.order" @updateSortBy="value => {filter.sort_by = value}"  @updateOrder="value => {filter.order = value}"></sort-by>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                            </button>
                            <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                <button class="dropdown-item custom-dropdown" @click="print"><i class="fas fa-print"></i> {{trans('general.print')}}</button>
                                <button class="dropdown-item custom-dropdown" @click="pdf"><i class="fas fa-file-pdf"></i> {{trans('general.generate_pdf')}}</button>
                            </div>
                        </div>
                        <help-button @clicked="help_topic = 'employee_asset.asset.transfer'"></help-button>
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
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="">{{trans('asset.room')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_rooms" name="room_id" id="room_id" :options="rooms" :placeholder="trans('employee_asset.select_room')" @select="onRoomSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onRoomRemove" :selected="selected_rooms">
                                        <div class="multiselect__option" slot="afterList" v-if="!rooms.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <date-range-picker :start-date.sync="filter.date_start_date" :end-date.sync="filter.date_end_date" :label="trans('general.date_between')"></date-range-picker>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" @click="showFilterPanel = false" class="btn btn-danger">{{trans('general.cancel')}}</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" @click="getAssetTransfers">{{trans('general.filter')}}</button>
                        </div>
                    </div>
                </div>
            </transition>
            <transition name="fade" v-if="hasPermission('create-asset-transfer')">
                <div class="card card-form" v-if="showCreatePanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('employee_asset.add_new_asset_transfer')}}</h4>
                        <asset-transfer-form @completed="getAssetTransfers" @cancel="showCreatePanel = !showCreatePanel"></asset-transfer-form>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="asset_transfers.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{trans('employee_asset.asset_transfer_detail')}}</th>
                                    <th>{{trans('employee_asset.asset_transfer_date')}}</th>
                                    <th>{{trans('employee_asset.asset_item_name')}}</th>
                                    <th>{{trans('employee_asset.asset_transfer_description')}}</th>
                                    <th class="table-option">{{trans('general.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asset_transfer in asset_transfers.data">
                                    <td>
                                        <template v-if="asset_transfer.type == 'employee'">
                                            {{trans('employee.employee_name')+': '+getEmployeeName(asset_transfer.employee)}} <br />
                                            {{getEmployeeDesignationOnDate(asset_transfer.employee, asset_transfer.date)}}
                                        </template>
                                        <template v-else-if="asset_transfer.type == 'student'">
                                            {{trans('student.student_name')+': '+getStudentName(asset_transfer.student)}} <br />
                                            {{trans('student.first_guardian_name')+': '+asset_transfer.student.parent.first_guardian_name}} <br />
                                            {{trans('student.contact_number')+': '+asset_transfer.student.contact_number}} <br />
                                        </template>
                                        <template v-else-if="asset_transfer.type == 'room'">
                                            {{trans('asset.room')}}: {{asset_transfer.room.name}}
                                        </template>
                                    </td>
                                    </td>
                                    <td>{{asset_transfer.date | moment}}</td>
                                    <td>
                                        <template v-for="(detail, index) in asset_transfer.details">
                                            <span :key="`assetTransferDetails${index}`">
                                                {{ detail.item.name }}
                                                ({{ detail.item.serial_number }})
                                            </span>
                                        </template>
                                    </td>
                                    <td v-text="asset_transfer.description"></td>
                                    <td class="table-option">
                                        <div class="btn-group">
                                            <button class="btn btn-success btn-sm" v-tooltip="trans('employee_asset.asset_transfer_detail')" @click="$router.push('/employee/asset/transfer/'+asset_transfer.id)"><i class="fas fa-arrow-circle-right"></i></button>
                                            <button class="btn btn-info btn-sm" v-if="hasPermission('edit-asset-transfer')" v-tooltip="trans('employee_asset.edit_asset_transfer')" @click.prevent="editAssetTransfer(asset_transfer)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" v-if="hasPermission('delete-asset-transfer')" :key="asset_transfer.id" v-confirm="{ok: confirmDelete(asset_transfer)}" v-tooltip="trans('employee_asset.delete_asset_transfer')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!asset_transfers.total" module="employee_asset" title="asset_transfer_module_title" description="asset_transfer_module_description" icon="list">
                        <div slot="btn">
                            <button class="btn btn-info btn-md" v-if="!showCreatePanel && hasPermission('create-asset-transfer')" @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i> {{trans('general.add_new')}}</button>
                        </div>
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="asset_transfers" @updateRecords="getAssetTransfers"></pagination-record>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
    </div>
</template>

<script>
    import assetTransferForm from './form'

    export default {
        components : { assetTransferForm },
        data() {
            return {
                asset_transfers: {
                    total: 0,
                    data: []
                },
                filter: {
                    sort_by : 'date',
                    order: 'desc',
                    room_id: [],
                    date_start_date: '',
                    date_end_date: '',
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'date',
                        translation: i18n.employee_asset.asset_transfer_date
                    },
                    {
                        value: 'created_at',
                        translation: i18n.general.created_at
                    }
                ],
                showFilterPanel: false,
                showCreatePanel: false,
                rooms: [],
                selected_rooms: null,
                help_topic: ''
            };
        },
        mounted(){
            if(!helper.hasPermission('list-asset-transfer')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getAssetTransfers();
            helper.showDemoNotification(['employee_asset']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getStudentName(student){
                return helper.getStudentName(student);
            },
            getEmployeeName(employee){
                return helper.getEmployeeName(employee);
            },
            getEmployeeDesignationOnDate(employee, date){
                return helper.getEmployeeDesignationOnDate(employee, date);
            },
            getAssetTransfers(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/employee/asset/transfer?page=' + page + url)
                    .then(response => {
                        this.asset_transfers = response.asset_transfers;
                        this.rooms = response.filters.rooms;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editAssetTransfer(asset_transfer){
                this.$router.push('/employee/asset/transfer/'+asset_transfer.id+'/edit');
            },
            confirmDelete(asset_transfer){
                return dialog => this.deleteAssetTransfer(asset_transfer);
            },
            deleteAssetTransfer(asset_transfer){
                let loader = this.$loading.show();
                axios.delete('/api/employee/asset/transfer/'+asset_transfer.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getAssetTransfers();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/employee/asset/transfer/print',{filter: this.filter})
                    .then(response => {
                        let print = window.open("/print");
                        loader.hide();
                        print.document.write(response);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            pdf(){
                let loader = this.$loading.show();
                axios.post('/api/employee/asset/transfer/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            onRoomSelect(selectedOption){
                this.filter.room_id.push(selectedOption.id);
            },
            onRoomRemove(removedOption){
                this.filter.room_id.splice(this.filter.room_id.indexOf(removedOption.id), 1);
            },
            formatCurrency(amount) {
                return helper.formatCurrency(amount);
            }
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          },
          momentTime(time) {
            return helper.formatTime(time);
          }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getAssetTransfers();
            },
            'filter.order': function(val){
                this.getAssetTransfers();
            },
            'filter.page_length': function(val){
                this.getAssetTransfers();
            }
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
    }
</script>