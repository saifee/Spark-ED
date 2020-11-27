<template>
  <v-row>
    <v-col cols="12">
      <v-toolbar class="elevation-0">
        <v-toolbar-title>{{ trans('inventory_sale.stock_pos') }}</v-toolbar-title>
        <v-spacer />
      </v-toolbar>
      <transition
        v-if="hasPermission('create-stock-sale')"
        name="fade"
      >
        <v-card
          v-if="showCreatePanel"
        >
          <v-card-text>
            <stock-sale-form
              @completed="getStockSales"
              @cancel="showCreatePanel = !showCreatePanel"
            />
          </v-card-text>
        </v-card>
      </transition>
    </v-col>
  </v-row>
</template>

<script>
    import stockSaleForm from './form'

    export default {
        components : { stockSaleForm },
        data() {
            return {
                showCreatePanel: false,
            };
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        },
        mounted(){
            if(!helper.hasPermission('list-stock-sale')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getStockSales();
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getStockSales(){
                this.showCreatePanel = true
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
        }
    }
</script>
