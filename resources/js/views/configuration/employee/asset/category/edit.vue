<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.edit_asset_category')}}</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/configuration/employee/asset/category')"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.asset_category')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-form">
                <div class="card-body p-t-20">
                    <asset-category-form :id="id"></asset-category-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import assetCategoryForm from './form';

    export default {
        components : { assetCategoryForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('edit-asset-category')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>
