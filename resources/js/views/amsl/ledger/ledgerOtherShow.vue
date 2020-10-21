<template>
  <div class="card card-table">
    <amsl-data-table-other-ledger
      ref="dataTable"
      :search="true"
      :columns="columns"
      :table-headline="newHeadline"
      :date-search-field-name="'liability_date'"
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
          <template v-if="props.data.amount<0 && props.data.type=='employeeliabilityAp'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='expenseAr'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='incomeAr'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='aPexpenseAr'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.type=='assetAr' && props.data.transaction_type=='Payment'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.type=='liabilityAp' && props.data.transaction_type=='Payment'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.type=='fixed-asset' && (props.data.transaction_type=='Initial' || props.data.transaction_type=='Purchase')">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.type=='long-term-liabilities' && props.data.transaction_type=='Payment'">
            {{ props.data.amount |currency }}
          </template>
        </td>



        <td class="text-right">
          <template v-if="props.data.amount>=0 && props.data.type=='employeeliabilityAp'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount<0 && props.data.type=='expenseAr'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='assetAr' && props.data.transaction_type=='Receive'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.amount>=0 && props.data.type=='liabilityAp' && props.data.transaction_type=='Receive'">
            {{ props.data.amount |currency }}
          </template>
          <template v-if="props.data.type=='fixed-asset' && (props.data.transaction_type=='Sold'||props.data.transaction_type=='depreciation')">
            {{ props.data.amount |currency }}
          </template>

          <template v-if="props.data.type=='long-term-liabilities' && (props.data.transaction_type=='Initial' || props.data.transaction_type=='Receive')">
            {{ props.data.amount |currency }}
          </template>

          <template v-if="props.data.type=='liabilityAp_asset' && props.data.transaction_type=='Purchase'">
            {{ props.data.amount |currency }}
          </template>
        </td>

        <td class="text-right">
          {{ props.data.total_amount |currency }}
        </td>
        <td
          v-if="$route.params.type=='Employee'"
          class="text-right"
        >
          {{ props.data.total_due_mount |currency }}
        </td>
      </template>
    </amsl-data-table-other-ledger>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/amsl-api'+'/ledger?id='+this.$route.params.id+'&type='+this.$route.params.type,
                newHeadline:'Ledger of '+this.$route.params.name,
                columns: [
                    {name: 'Date'},
                    {name: 'Description'},
                    {name: 'Payment Type'},
                    {name: 'Ref'},
                    {name: 'Debit'},
                    {name: 'Credit'},
                    {name: 'Total Balance'},
                ],
            }
        },
        watch:{
            $route (to, from){
                this.url='/ledger?id='+this.$route.params.id+'&type='+this.$route.params.type
                this.newHeadline='Ledger of '+this.$route.params.name
            }

        },
        created(){
            if(this.$route.params.type=='Employee'){
                this.columns.push({name:'Due Balance'})
            }
        },
        methods:{
            deleteMe(id) {
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/cash/' + id).then(response => {
                            var dataTable = this.$refs.dataTable
                            dataTable.getResult();
                            toastr.success(response.message);
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
