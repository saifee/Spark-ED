<template>
  <div class="card card-table">
    <amsl-data-table-day-book
      ref="dataTable"
      :search="false"
      :add-button="false"
      :columns="columns"
      :table-headline="'Day Book - '+this.$route.params.type "
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <td class="text-center">
          {{ parseDate(props.data.date) }}
        </td>
        <td class="text-center">
          {{ props.data.description }}
        </td>
        <td class="text-center">
          {{ props.data.ref }}
        </td>
        <td class="text-right">
          <template v-if="props.data.type=='income'">
            {{ props.data.amount|currency('£') }}
          </template>
          <template v-if="props.data.type=='asset' && (props.data.transaction_type=='Sold'|| props.data.transaction_type=='Receive'|| props.data.transaction_type=='Initial')">
            {{ props.data.amount|currency('£') }}
          </template>

          <template v-if="props.data.type=='liability' && (props.data.transaction_type=='Receive' || props.data.transaction_type=='Initial')">
            {{ props.data.amount|currency('£') }}
          </template>

          <template v-if="props.data.type=='equity' && props.data.transaction_type=='Receive'">
            {{ props.data.amount|currency('£') }}
          </template>
        </td>
        <td class="text-right">
          <template v-if="props.data.type=='expense'">
            {{ props.data.amount|currency('£') }}
          </template>
          <template v-if="props.data.type=='asset' && (props.data.transaction_type=='Purchase' || props.data.transaction_type=='Payment')">
            {{ props.data.amount|currency('£') }}
          </template>
          <template v-if="props.data.type=='liability' && props.data.transaction_type=='Payment'">
            {{ props.data.amount|currency('£') }}
          </template>
          <template v-if="props.data.type=='equity' && props.data.transaction_type=='Payment'">
            {{ props.data.amount|currency('£') }}
          </template>
        </td>
        <td class="text-right">
          {{ props.data.total_amount|currency('£') }}
        </td>
      </template>
    </amsl-data-table-day-book>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/day-book?type='+this.$route.params.type,
                columns: [
                    {name: 'Date'},
                    {name: 'Description'},
                    {name: 'Ref'},
                    {name: 'Receipts'},
                    {name: 'Expenses'},
                    {name: 'Balance'},
                ],
            }
        },
        watch:{
            $route (to, from){
                this.url='/day-book?type='+this.$route.params.type
            }
        },
        methods:{
            parseDate(val){
                var spliceDate = val.slice(0,10)
                const [year, month, day] =spliceDate.split('-')
                return day+'/'+month+'/'+year
            },
        }

    }
</script>
