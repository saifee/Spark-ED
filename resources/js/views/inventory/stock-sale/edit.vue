<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('inventory_sale.edit_stock_sale')}}</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/inventory/stock/sale')"><i class="fas fa-list"></i> <span class="d-none d-sm-inline">{{trans('inventory_sale.stock_sale')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card card-form">
                <div class="card-body p-t-20">
                    <stock-sale-form :id="id"></stock-sale-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import stockSaleForm from './form';

    export default {
        components : { stockSaleForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('edit-stock-sale')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>
