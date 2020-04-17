<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.edit_asset_item')}}</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/configuration/employee/asset/item')"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.asset_item')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-form">
                <div class="card-body p-t-20">
                    <asset-item-form :id="id"></asset-item-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import assetItemForm from './form';

    export default {
        components : { assetItemForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('edit-asset-item')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>
