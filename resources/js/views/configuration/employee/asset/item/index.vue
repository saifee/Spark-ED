<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.asset_item')}} 
                        <span class="card-subtitle d-none d-sm-inline" v-if="asset_items.total">{{trans('general.total_result_found',{count : asset_items.total, from: asset_items.from, to: asset_items.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="asset_items.total && !showCreatePanel && hasPermission('create-asset-item')" @click="showCreatePanel = !showCreatePanel" v-tooltip="trans('general.add_new')"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.add_new_asset_item')}}</span></button>
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
                        <help-button @clicked="help_topic = 'employee_asset.asset.item'"></help-button>
                        
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
                                    <label for="">{{trans('employee_asset.asset_category')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_asset_category" name="asset_category_id" id="asset_category_id" :options="asset_categories" :placeholder="trans('employee_asset.select_asset_category')" @select="onAssetCategorySelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onAssetCategoryRemove" :selected="selected_asset_category">
                                        <div class="multiselect__option" slot="afterList" v-if="!asset_categories.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('employee_asset.asset_item_name')}}</label>
                                    <input class="form-control" name="name" v-model="filter.name">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" @click="showFilterPanel = false" class="btn btn-danger">{{trans('general.cancel')}}</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" @click="getAssetItems">{{trans('general.filter')}}</button>
                        </div>
                    </div>
                </div>
            </transition>
            <transition name="fade" v-if="hasPermission('create-asset-item')">
                <div class="card card-form" v-if="showCreatePanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('employee_asset.add_new_asset_item')}}</h4>
                        <asset-item-form @completed="getAssetItems" @cancel="showCreatePanel = !showCreatePanel"></asset-item-form>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="asset_items.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{trans('employee_asset.asset_item_name')}}</th>
                                    <th>{{trans('employee_asset.asset_category')}}</th>
                                    <th>{{trans('employee_asset.asset_item_date')}}</th>
                                    <th>{{trans('employee_asset.asset_item_serial_number')}}</th>
                                    <th>{{trans('employee_asset.asset_item_price')}}</th>
                                    <th>{{trans('employee_asset.asset_item_model_number')}}</th>
                                    <th class="table-option">{{trans('general.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="asset_item in asset_items.data">
                                    <td>{{asset_item.name}}</td>
                                    <td>{{asset_item.category.name}}</td>
                                    <td>{{asset_item.date}}</td>
                                    <td>{{asset_item.serial_number}}</td>
                                    <td>{{asset_item.price}}</td>
                                    <td>{{asset_item.model_number}}</td>
                                    <td class="table-option">
                                        <div class="btn-group">
                                            <button class="btn btn-info btn-sm" v-tooltip="trans('employee_asset.edit_asset_item')" @click.prevent="editAssetCategory(asset_item)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" :key="asset_item.id" v-confirm="{ok: confirmDelete(asset_item)}" v-tooltip="trans('employee_asset.delete_asset_item')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!asset_items.total" module="employee_asset" title="asset_item_module_title" description="asset_item_module_description" icon="list">
                        <div slot="btn">
                            <button class="btn btn-info btn-md" v-if="!showCreatePanel && hasPermission('create-asset-item')" @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i> {{trans('general.add_new')}}</button>
                        </div>
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="asset_items" @updateRecords="getAssetItems"></pagination-record>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
    </div>
</template>

<script>
    import assetItemForm from './form'

    export default {
        components : { assetItemForm},
        data() {
            return {
                asset_items: {
                    total: 0,
                    data: []
                },
                filter: {
                    sort_by : 'name',
                    order: 'desc',
                    asset_category_id: [],
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'name',
                        translation: i18n.employee_asset.asset_item_name
                    },
                    {
                        value: 'created_at',
                        translation: i18n.general.created_at
                    }
                ],
                asset_categories: [],
                selected_asset_category: null,
                showFilterPanel: false,
                showCreatePanel: false,
                help_topic: ''
            };
        },
        mounted(){
            if(!helper.hasPermission('list-asset-item') || !helper.hasPermission('create-asset-item')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            if (helper.hasPermission('list-asset-item'))
                this.getAssetItems();
            helper.showDemoNotification(['employee_asset']);
        },
        methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getAssetItems(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/configuration/employee/asset/item?page=' + page + url)
                    .then(response => {
                        this.asset_items = response.asset_items;
                        this.asset_categories = response.filters.asset_categories;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editAssetCategory(asset_item){
                this.$router.push('/configuration/employee/asset/item/'+asset_item.id+'/edit');
            },
            confirmDelete(asset_item){
                return dialog => this.deleteAssetCategory(asset_item);
            },
            deleteAssetCategory(asset_item){
                let loader = this.$loading.show();
                axios.delete('/api/configuration/employee/asset/item/'+asset_item.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getAssetItems();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/configuration/employee/asset/item/print',{filter: this.filter})
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
                axios.post('/api/configuration/employee/asset/item/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            onAssetCategorySelect(selectedOption){
                this.filter.asset_category_id.push(selectedOption.id);
            },
            onAssetCategoryRemove(removedOption){
                this.filter.asset_category_id.splice(this.filter.asset_category_id.indexOf(removedOption.id), 1);
            },
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getAssetItems();
            },
            'filter.order': function(val){
                this.getAssetItems();
            },
            'filter.page_length': function(val){
                this.getAssetItems();
            }
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
    }
</script>