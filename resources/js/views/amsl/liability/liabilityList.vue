<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'liabilityCreate'"
      :table-headline="'Liability Transactions'"
      :search-field="'account.name'"
      :date-search="true"
      :date-search-field-name="'liability_date'"
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <td>
          {{ props.data.accountable.name }}
        </td>
        <td>
          {{ props.data.ref }}
        </td>
        <td>
          {{ props.data.description }}
        </td>
        <td>
          {{ props.data.payment_type }}{{ props.data.account_receivable?' - '+props.data.account_receivable.name:null }}
        </td>
        <td>
          {{ props.data.amount|currency }}
        </td>

        <td>
          <div class="adjustAction">
            <v-btn
              :to="{name:'liabilityEdit', params:{id:props.data.id}}"
              icon
            >
              <v-icon> edit </v-icon>
            </v-btn> <v-icon @click.prevent="deleteMe(props.data.id)">
              delete
            </v-icon>
          </div>
        </td>
      </template>
    </amsl-data-table>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                url:'/amsl-api'+'/liability',
                columns: [
                    {name: 'Account'},
                    {name: 'Ref'},
                    {name: 'Description'},
                    {name: 'Payment Type'},
                    {name: 'Amount'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/liability/' + id).then(response => {
                            var dataTable = this.$refs.dataTable
                            dataTable.getResult();
                            toastr.success(response.message);
                        });
                    }
                });
            }
        }

    }
</script>
