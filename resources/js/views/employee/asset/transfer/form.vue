<template>
	<div>
        <form @submit.prevent="proceed" @keydown="assetTransferForm.errors.clear($event.target.name)">
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_transfer_date')}}</label>
                        <datepicker v-model="assetTransferForm.date" :bootstrapStyling="true" @selected="assetTransferForm.errors.clear('date')" :placeholder="trans('employee_asset.asset_transfer_date')" typeable></datepicker>
                        <show-error :form-name="assetTransferForm" prop-name="date"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-3">
                    <div class="form-group" v-if="assetTransferForm.type == 'employee'">
                        <label for="">{{trans('employee.employee')}}</label>
                        <v-select label="name" v-model="selected_employee" name="employee_id" id="employee_id" :options="employees" :placeholder="trans('employee.select_employee')" @select="onEmployeeSelect" @close="assetTransferForm.errors.clear('employee_id')" @remove="assetTransferForm.employee_id = ''">
                            <div class="multiselect__option" slot="afterList" v-if="!employees.length">
                                {{trans('general.no_option_found')}}
                            </div>
                        </v-select>
                        <show-error :form-name="assetTransferForm" prop-name="employee_id"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-3" v-for="(detail, index) in assetTransferForm.details">
                    <div class="form-group">
                        <label for="">
                            {{trans('employee_asset.asset_item')}}
                        </label>
                        <v-select label="name" v-model="detail.selected_asset_item" :name="getAssetItemName(index)" :id="getAssetItemName(index)" :options="asset_items" :placeholder="trans('employee_asset.select_asset_item')" @select="onAssetItemSelect" @close="assetTransferForm.errors.clear(getAssetItemName(index))" @remove="onAssetItemRemove">
                            <div class="multiselect__option" slot="afterList" v-if="!asset_items.length">
                                {{trans('general.no_option_found')}}
                            </div>
                        </v-select>
                        <show-error :form-name="assetTransferForm" :prop-name="getAssetItemName(index)"></show-error>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_transfer_description')}}</label>
                        <autosize-textarea v-model="assetTransferForm.description" rows="1" name="description" :placeholder="trans('employee_asset.asset_transfer_description')"></autosize-textarea>
                        <show-error :form-name="assetTransferForm" prop-name="description"></show-error>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <file-upload-input :button-text="trans('general.upload_document')" :token="assetTransferForm.upload_token" module="asset_transfer" :clear-file="clearAttachment" :module-id="module_id"></file-upload-input>
                        </div>
                    </div>
                </div>
            <div class="card-footer text-right">
                <button v-show="id" type="button" class="btn btn-danger " @click="$router.push('/employee/asset/transfer')">{{trans('general.cancel')}}</button>
                <button v-if="!id" type="button" class="btn btn-danger " @click="$emit('cancel')">{{trans('general.cancel')}}</button>
                <button type="submit" class="btn btn-info waves-effect waves-light">{{trans('general.save')}}</button>
            </div>
        </form>
    </div>
</template>

<script>

    export default {
        components: {},
        props: ['id'],
        data(){
            return {
                assetTransferForm: new Form({
                    type: 'employee',
                    date: '',
                    return_due_date: '',
                    employee_id: '',
                    description: '',
                    details: [],
                    upload_token: ''
                }),
                asset_items: [],
                employees: [],
                selected_employee: null,
                module_id: '',
                clearAttachment: true
            }
        },
        mounted(){
            if(!this.id)
                this.addRow();

            if(this.id)
                this.get();
            else
                this.assetTransferForm.upload_token = this.$uuid.v4();

            this.getPreRequisite();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.update();
                else
                    this.store();
            }, 
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/employee/asset/transfer/pre-requisite')
                    .then(response => {
                        this.employees = response.employees;
                        this.asset_items = response.asset_items;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            addRow(){
                let new_index = this.assetTransferForm.details.push({
                    quantity: '',
                    asset_item_id: '',
                    description: '',
                    selected_asset_item: null
                })
            },
            getAssetItemName(index){
                return index+'_asset_item_id';
            },
            getDescriptionName(index){
                return index+'_description';
            },
            getQuantityName(index){
                return index+'_quantity';
            },
            get(){
                let loader = this.$loading.show();
                axios.get('/api/employee/asset/transfer/'+this.id)
                    .then(response => {
                        this.assetTransferForm.type = response.asset_transfer.type;
                        this.assetTransferForm.upload_token = response.asset_transfer.upload_token;
                        this.module_id = response.asset_transfer.id;
                        this.assetTransferForm.number = response.asset_transfer.number;
                        this.assetTransferForm.date = response.asset_transfer.date;
                        this.assetTransferForm.return_due_date = response.asset_transfer.return_due_date;
                        this.assetTransferForm.description = response.asset_transfer.description;
                        this.assetTransferForm.employee_id = response.asset_transfer.employee_id;
                        this.selected_employee = response.selected_employee;
                        response.asset_transfer.details.forEach(detail => {
                            this.assetTransferForm.details.push({
                                quantity: detail.quantity,
                                asset_item_id: detail.asset_item_id,
                                selected_asset_item: (detail.asset_item_id) ? {id: detail.asset_item_id, name: detail.item.name} : null,
                                description: detail.description
                            });
                        });
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            store(){
                let loader = this.$loading.show();
                this.assetTransferForm.date = helper.toDate(this.assetTransferForm.date);
                this.assetTransferForm.return_due_date = helper.toDate(this.assetTransferForm.return_due_date);
                this.assetTransferForm.post('/api/employee/asset/transfer')
                    .then(response => {
                        toastr.success(response.message);
                        this.selected_employee = null;
                        this.assetTransferForm.details = [];
                        this.clearAttachment = !this.clearAttachment;
                        this.assetTransferForm.upload_token = this.$uuid.v4();
                        this.addRow();
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            update(){
                let loader = this.$loading.show();
                this.assetTransferForm.date = helper.toDate(this.assetTransferForm.date);
                this.assetTransferForm.return_due_date = helper.toDate(this.assetTransferForm.return_due_date);
                this.assetTransferForm.patch('/api/employee/asset/transfer/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/employee/asset/transfer');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            onEmployeeSelect(selectedOption){
                this.assetTransferForm.employee_id = selectedOption.id;
            },
            onAssetItemSelect(selectedOption, id){
                let index = id.split("_")[0];
                let detail = this.assetTransferForm.details[index];
                detail.asset_item_id = selectedOption.id;
            },
            onAssetItemRemove(removedOption, id){
                let index = id.split("_")[0];
                let detail = this.assetTransferForm.details[index];
                detail.asset_item_id = '';
            }
        },
        computed:{
        }
    }
</script>