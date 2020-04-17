<template>
	<div>
        <form @submit.prevent="proceed" @keydown="assetCategoryForm.errors.clear($event.target.name)">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="">{{trans('employee_asset.asset_category_name')}}</label>
                        <input class="form-control" type="text" v-model="assetCategoryForm.name" name="name" :placeholder="trans('employee_asset.asset_category_name')">
                        <show-error :form-name="assetCategoryForm" prop-name="name"></show-error>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <router-link to="/configuration/employee/asset/category" class="btn btn-danger waves-effect waves-light " v-show="id">{{trans('general.cancel')}}</router-link>
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
                assetCategoryForm: new Form({
                    name : '',
                    description : '',
                    saleable : '',
                })
            };
        },
        mounted() {
            if (this.id)
                this.getAssetCategory();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.updateAssetCategory();
                else
                    this.storeAssetCategory();
            },
            storeAssetCategory(){
                let loader = this.$loading.show();

                this.assetCategoryForm.post('/api/configuration/employee/asset/category')
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getAssetCategory(){
                let loader = this.$loading.show();
                axios.get('/api/configuration/employee/asset/category/'+this.id)
                    .then(response => {
                        this.assetCategoryForm.name = response.name;
                        this.assetCategoryForm.description = response.description;
                        this.assetCategoryForm.saleable = response.saleable;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        this.$router.push('/configuration/employee/asset/category');
                    });
            },
            updateAssetCategory(){
                let loader = this.$loading.show();
                this.assetCategoryForm.patch('/api/configuration/employee/asset/category/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                        this.$router.push('/configuration/employee/asset/category');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>