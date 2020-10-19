<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-button="false"
      :table-headline="'Ledger of Asset'"
      :search-field="'name'"
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <td class="text-center">
          <router-link :to="{name:'ledgerOtherShow', params:{id:props.data.id,type:'Fixed Asset',name:props.data.name}}">
            {{ props.data.name }}
          </router-link>
        </td>
      </template>
    </amsl-data-table>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/amsl-api'+'/ledger/asset-ledger',
                columns: [
                    {name: 'Name'},

                ]
            }
        },

        methods:{

        }

    }
</script>
