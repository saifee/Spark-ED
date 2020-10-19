<template>
  <div class="card card-table">
    <amsl-data-table-ledger
      ref="dataTable"
      :search="true"
      :columns="columns"
      :table-headline="newHeadline"
      :date-search-field-name="'income_date'"
      :search-field="'payment_type'"
      :add-button="false"
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <td class="text-center">
          <template v-if="props.data.date!='balance'">
            {{ parseDate(props.data.date) }}
          </template>
        </td>
        <td class="text-center">
          {{ props.data.description }}
        </td>
        <td class="text-center">
          {{ props.data.payment_type }}{{ props.data.modelable?' - '+props.data.modelable.name:null }}
        </td>
        <td class="text-center">
          {{ props.data.ref }}
        </td>
        <td class="text-right">
          <template v-if="props.data.amount>=0 && props.data.type=='expense' && props.data.payment_type!='Prepaid Expense'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="props.data.amount<0 && props.data.type=='income'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="props.data.type=='prepaid expense' && props.data.payment_type=='Prepaid Expense'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="(props.data.type=='assetAr' || props.data.type=='incomeAr') && props.data.transaction_type =='Receive'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="(props.data.type=='liabilityAp' || props.data.type=='expenseAr') && props.data.transaction_type =='Receive'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="props.data.type=='paidVat'">
            {{ props.data.amount |currency('£') }}
          </template>
        </td>

        <td class="text-right">
          <template v-if="(props.data.amount<0 && props.data.type=='expense')|| (props.data.payment_type=='Prepaid Expense'&& props.data.type=='expense')">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='income'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="props.data.payment_type=='Adjust' && props.data.type !='expense'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="(props.data.type=='assetAr' || props.data.type=='incomeAr')&& props.data.transaction_type=='Payment'">
            {{ props.data.amount |currency('£') }}
          </template>
          <template v-if="(props.data.type=='liabilityAp' || props.data.type=='expenseAr') && props.data.transaction_type =='Payment'">
            {{ props.data.amount |currency('£') }}
          </template>

          <template v-if="props.data.type=='receiveVat'">
            {{ props.data.amount |currency('£') }}
          </template>
        </td>
        <td class="text-right">
          {{ props.data.total_amount |currency('£') }}
        </td>
      </template>
    </amsl-data-table-ledger>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/ledger?id='+this.$route.params.id+'&type='+this.$route.params.type,
                newHeadline:'Ledger of '+this.$route.params.name,
                columns: [
                    {name: 'Date'},
                    {name: 'Description'},
                    {name: 'Payment Type'},
                    {name: 'Ref'},
                    {name: 'Debit'},
                    {name: 'Credit'},
                    {name: 'Balance'},
                ],
            }
        },
        watch:{
            $route (to, from){
                this.url='/ledger?id='+this.$route.params.id+'&type='+this.$route.params.type
                this.newHeadline='Ledger of '+this.$route.params.name
            }
        },
        methods:{
            deleteMe(id) {
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/cash/' + id).then(response => {
                            var dataTable = this.$refs.dataTable
                            dataTable.getResult();
                            this.$swal({
                                type: response.data.message.type,
                                title: response.data.message.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        });
                    }
                });
            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },
        }

    }
</script>
