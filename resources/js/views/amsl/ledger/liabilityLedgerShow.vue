<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-button="false"
      :table-headline="'Ledger of Liability'"
      :search-field="'name'"
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <v-list-item :to="{name:'ledgerOtherShow', params:{id:props.data.id,type:'Long-term Liabilities',name:props.data.name}}">
          <v-list-item-action>
            <v-icon>play_arrow</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ props.data.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
    </amsl-data-table>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/amsl-api'+'/ledger/liability-ledger',
                columns: [
                    {name: 'Name'},

                ]
            }
        },

        methods:{

        }

    }
</script>
