<template>
	<div>
        <form @submit.prevent="proceed" @keydown="assetItemForm.errors.clear($event.target.name)">
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_category')}}</label>
                        <v-select label="name" v-model="selected_asset_category" name="asset_category_id" id="asset_category_id" :options="asset_categories" :placeholder="trans('general.select_one')" @select="onAssetCategorySelect" @close="assetItemForm.errors.clear('asset_category_id')" @remove="assetItemForm.asset_category_id = ''">
                            <div class="multiselect__option" slot="afterList" v-if="!asset_categories.length">
                                {{trans('general.no_option_found')}}
                            </div>
                        </v-select>
                        <show-error :form-name="assetItemForm" prop-name="asset_category_id"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_name')}}</label>
                        <input class="form-control" type="text" v-model="assetItemForm.name" name="name" :placeholder="trans('employee_asset.asset_item_name')">
                        <show-error :form-name="assetItemForm" prop-name="name"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_serial_number')}}</label>
                        <input class="form-control" type="text" v-model="assetItemForm.serial_number" name="serial_number" :placeholder="trans('employee_asset.asset_item_serial_number')">
                        <show-error :form-name="assetItemForm" prop-name="serial_number"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_model_number')}}</label>
                        <input class="form-control" type="text" v-model="assetItemForm.model_number" name="model_number" :placeholder="trans('employee_asset.asset_item_model_number')">
                        <show-error :form-name="assetItemForm" prop-name="model_number"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_date')}}</label>
                        <datepicker v-model="assetItemForm.date" :bootstrapStyling="true" @selected="assetItemForm.errors.clear('date')" :placeholder="trans('employee_asset.date')" typeable></datepicker>
                        <show-error :form-name="assetItemForm" prop-name="date"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_price')}}</label>
                        <input class="form-control" type="text" v-model="assetItemForm.price" name="price" :placeholder="trans('employee_asset.asset_item_price')">
                        <show-error :form-name="assetItemForm" prop-name="price"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-12">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_item_description')}}</label>
                        <autosize-textarea v-model="assetItemForm.description" rows="2" name="description" :placeholder="trans('asset.asset_item_description')"></autosize-textarea>
                        <show-error :form-name="assetItemForm" prop-name="description"></show-error>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <router-link to="/configuration/employee/asset/item" class="btn btn-danger waves-effect waves-light " v-show="id">{{trans('general.cancel')}}</router-link>
                <button v-if="!id" type="button" class="btn btn-danger waves-effect waves-light " @click="$emit('cancel')">{{trans('general.cancel')}}</button>
                <button type="submit" class="btn btn-info waves-effect waves-light">
                    <span v-if="id">{{trans('general.update')}}</span>
                    <span v-else>{{trans('general.save')}}</span>
                </button>
            </div>
        </form>
    </div>
</template>


<script>

    export default {
        components:{},
        props: ['id'],
        data() {
            return {
                assetItemForm: new Form({
                    name : '',
                    serial_number: '',
                    model_number: '',
                    asset_category_id: '',
                    price : '',
                    date : '',
                    description : '',
                    image : '',
                }),
                asset_categories: [],
                selected_asset_category: null
            };
        },
        mounted() {
            this.getPreRequisite();

            if (this.id)
                this.getAssetItem();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.updateAssetItem();
                else
                    this.storeAssetItem();
            },
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/configuration/employee/asset/item/pre-requisite')
                    .then(response => {
                        this.asset_categories = response.asset_categories;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            storeAssetItem(){
                let loader = this.$loading.show();

                this.assetItemForm.post('/api/configuration/employee/asset/item')
                    .then(response => {
                        toastr.success(response.message);
                        this.selected_asset_category = null;
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getAssetItem(){
                let loader = this.$loading.show();
                axios.get('/api/configuration/employee/asset/item/'+this.id)
                    .then(response => {
                        this.assetItemForm.name = response.asset_item.name;
                        this.assetItemForm.serial_number = response.asset_item.serial_number;
                        this.assetItemForm.model_number = response.asset_item.model_number;
                        this.assetItemForm.date = response.asset_item.date;
                        this.assetItemForm.description = response.asset_item.description;
                        this.assetItemForm.asset_category_id = response.asset_item.asset_category_id;
                        this.assetItemForm.price = response.asset_item.price;
                        this.selected_asset_category = response.selected_asset_category;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        this.$router.push('/configuration/employee/asset/item');
                    });
            },
            updateAssetItem(){
                let loader = this.$loading.show();
                this.assetItemForm.patch('/api/configuration/employee/asset/item/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                        this.$router.push('/configuration/employee/asset/item');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            onAssetCategorySelect(selectedOption){
                this.assetItemForm.asset_category_id = selectedOption.id;
            }
        }
    }
</script>