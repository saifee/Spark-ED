<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('employee_asset.edit_asset_transfer')}}</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/employee/asset/transfer')"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('employee_asset.asset_transfer')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-form">
                <div class="card-body p-t-20">
                    <asset-transfer-form :id="id"></asset-transfer-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import assetTransferForm from './form';

    export default {
        components : { assetTransferForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('edit-asset-transfer')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>