<template>
    <div v-if="asset_transfer.id">
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.asset_transfer_detail')}}
                        <span class="card-subtitle">{{'#'+asset_transfer.id}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <router-link to="/employee/asset/transfer" class="btn btn-info btn-sm"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.asset_transfer')}}</span></router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 pr-0">
                	<div class="card border-right">
                		<div class="card-body">
                            <div class="table-responsive px-2">
                                <table class="table table-sm custom-show-table">
                                    <tbody>
                                        <tr>
                                            <td>{{trans('employee_asset.asset_transfer_detail')}}</td>
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
                                        </tr>
                                        <tr>
                                        	<td>{{trans('employee_asset.asset_transfer_date')}}</td>
                                        	<td>{{asset_transfer.date | moment}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{trans('employee_asset.asset_item_name')}}</td>
                                            <td>
                                                <template v-for="(detail, index) in asset_transfer.details">
                                                    <span :key="`assetTransferDetails${index}`">
                                                        {{ detail.item.name }}
                                                        ({{ detail.item.serial_number }})
                                                    </span>
                                                </template>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{trans('employee_asset.asset_transfer_description')}}</td>
                                            <td>{{asset_transfer.description}}</td>
                                        </tr>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div v-if="attachments.length">
                                                    <ul class="m-t-10 upload-file-list">
                                                        <li class="upload-file-list-item" v-for="attachment in attachments">
                                                            <a :href="`/asset/transfer/${asset_transfer.id}/attachment/${attachment.uuid}/download?token=${authToken}`" class="no-link-color"><i :class="['file-icon', 'fas', 'fa-lg', attachment.file_info.icon]"></i> <span class="upload-file-list-item-size">{{attachment.file_info.size}}</span> {{attachment.user_filename}}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 p-0">
                    <div class="card card-form">
                        <div class="card-body">
                            <template v-if="!asset_transfer.return_details.length">
                            <h4 class="card-title">{{trans('employee_asset.asset_transfer_return')}}</h4>
                            <return-form class="pr-3" :asset-transfer="asset_transfer" @completed="get" v-if="hasPermission('edit-asset-transfer')"></return-form>
                            </template>
                            <template v-if="asset_transfer.return_details.length">
                            <h4 class="card-title">{{trans('employee_asset.asset_transfer_return_detail')}}</h4>
                            <div class="table-responsive" v-if="asset_transfer.return_details.length">
                                <table class="table table-sm pr-3">
                                    <thead>
                                        <tr>
                                            <th>{{trans('employee_asset.asset_transfer_return_date')}}</th>
                                            <th>{{trans('employee_asset.asset_transfer_description')}}</th>
                                            <th class="table-option" v-if="hasPermission('edit-asset-transfer')">{{trans('general.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="return_detail in asset_transfer.return_details">
                                            <td>{{return_detail.date | moment}}</td>
                                            <td>{{return_detail.description}}</td>
                                            <td class="table-option" v-if="hasPermission('edit-asset-transfer')">
                                                <div class="btn-group">
                                                    <button class="btn btn-danger btn-sm" :key="return_detail.id" v-confirm="{ok: confirmDelete(return_detail)}" v-tooltip="trans('employee_asset.delete_asset_transfer_return')"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 pb-4" v-else>
                                <small>{{trans('general.no_result_found')}}</small>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</template>

<script>
    import returnForm from './return-form'

	export default {
        components: {returnForm},
        data(){
        	return {
                id:this.$route.params.id,
        		asset_transfer: {},
                attachments: []
        	}
        },
        mounted() {
        	this.get();
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
        	get(){
                let loader = this.$loading.show();
                axios.get('/api/employee/asset/transfer/'+this.id)
                    .then(response => {
                    	this.asset_transfer = response.asset_transfer;
                        this.attachments = response.attachments;
                    	loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                    	helper.showErrorMsg(error);
                        this.$router.push('/employee/asset/transfer');
                    })
        	},
            getEmployeeName(employee){
                return helper.getEmployeeName(employee);
            },
            getEmployeeDesignationOnDate(employee, date){
                return helper.getEmployeeDesignationOnDate(employee, date);
            },
            getStudentName(student){
                return helper.getStudentName(student);
            },
            formatCurrency(amount) {
                return helper.formatCurrency(amount);
            },
            confirmDelete(return_detail){
                return dialog => this.deleteReturn(return_detail);
            },
            deleteReturn(return_detail){
                let loader = this.$loading.show();
                axios.delete('/api/employee/asset/transfer/'+this.asset_transfer.id+'/return/'+return_detail.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.get();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getReturnType(type) {
                return i18n.employee_asset['asset_transfer_return_type_'+type];
            },
            getCount(item, status) {
                let count = 0;
                this.asset_transfer.return_details.forEach(return_detail => {
                    if (return_detail.asset_item_id == item.id && return_detail.type == status) {
                        count = count + 1;
                    }
                })

                return count;
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
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
	}
</script>