<template>
    <div v-if="stock_sale.id">
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('inventory_sale.stock_sale_detail')}}
                        <span class="card-subtitle">{{'#'+stock_sale.id}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <router-link to="/inventory/stock/sale" class="btn btn-info btn-sm"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('inventory_sale.stock_sale')}}</span></router-link>
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
                                            <td>{{trans('inventory_sale.stock_sale_detail')}}</td>
                                            <td>
                                                <template v-if="stock_sale.type == 'student'">
                                                    {{trans('student.student_name')+': '+getStudentName(stock_sale.student)}} <br />
                                                    {{trans('student.father_name')+': '+stock_sale.student.parent.father_name}} <br />
                                                    {{trans('student.contact_number')+': '+stock_sale.student.contact_number}} <br />
                                                </template>
                                            </td>
                                        </tr>
                                        <tr>
                                        	<td>{{trans('inventory_sale.stock_sale_date')}}</td>
                                        	<td>{{stock_sale.date | moment}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{trans('inventory_sale.stock_sale_description')}}</td>
                                            <td>{{stock_sale.description}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div v-if="attachments.length">
                                                    <ul class="m-t-10 upload-file-list">
                                                        <li class="upload-file-list-item" v-for="attachment in attachments">
                                                            <a :href="`/stock/sale/${stock_sale.id}/attachment/${attachment.uuid}/download?token=${authToken}`" class="no-link-color"><i :class="['file-icon', 'fas', 'fa-lg', attachment.file_info.icon]"></i> <span class="upload-file-list-item-size">{{attachment.file_info.size}}</span> {{attachment.user_filename}}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="card-title px-3">{{trans('inventory.stock_item')}}</h4>
                            <div class="table-responsive px-2">
                                <table class="table table-sm">
                                	<thead>
                                		<tr>
                                			<th class="p-l-20">{{trans('inventory.stock_item')}}</th>
                                			<th>{{trans('inventory_sale.stock_sale_quantity')}}</th>
                                			<th>{{trans('inventory_sale.stock_sale_price')}}</th>
                                			<th>{{trans('inventory_sale.stock_sale_total')}}</th>
                                		</tr>
                                	</thead>
                                	<tbody>
                                		<tr v-for="detail in stock_sale.details">
                                			<td class="p-l-20">{{detail.item.name}}</td>
                                			<td>{{detail.quantity}}</td>
                                			<td>{{detail.price}}</td>
                                			<td>{{detail.price*detail.quantity}}</td>
                                		</tr>
                                	</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
        data(){
        	return {
                id:this.$route.params.id,
        		stock_sale: {},
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
                axios.get('/api/stock/transfer/'+this.id)
                    .then(response => {
                    	this.stock_sale = response.stock_transfer;
                        this.attachments = response.attachments;
                    	loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                    	helper.showErrorMsg(error);
                        this.$router.push('/inventory/stock/sale');
                    })
        	},
            getStudentName(student){
                return helper.getStudentName(student);
            },
            formatCurrency(amount) {
                return helper.formatCurrency(amount);
            },
            deleteReturn(return_detail){
                let loader = this.$loading.show();
                axios.delete('/api/stock/transfer/'+this.stock_sale.id+'/return/'+return_detail.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.get();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getCount(item, status) {
                let count = 0;
                this.stock_sale.return_details.forEach(return_detail => {
                    if (return_detail.stock_item_id == item.id && return_detail.type == status) {
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
