<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'expenseCreate'"
      :table-headline="'Expense List'"
      :search-field="'account.name'"
      :date-search="true"
      :date-search-field-name="'expense_date'"
      :ajax="{
        url: url
      }"
    >
      <template
        slot="items"
        slot-scope="props"
      >
        <td>
          {{ props.data.account.name }}
        </td>
        <td>
          {{ props.data.ref }}
        </td>
        <td>
          {{ props.data.description }}
        </td>
        <td>
          {{ props.data.payment_type }}{{ props.data.modelable?' - '+props.data.modelable.name:null }}
        </td>
        <td>
          {{ props.data.amount|currency }}
        </td>
        <td>
          {{ props.data.tax_rate?props.data.tax_rate+'%':'0%' }}
        </td>
        <td>
          {{ props.data.tax_amount|currency }}
        </td>
        <td>
          {{ props.data.after_tax_amount|currency }}
        </td>

        <td>
          <div class="adjustAction">
            <v-btn
              :to="{name:'expenseEdit', params:{id:props.data.id}}"
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
                url:'/amsl-api'+'/expense',
                columns: [
                    {name: 'Account'},
                    {name: 'Ref'},
                    {name: 'Description'},
                    {name: 'Payment Type'},
                    {name: 'Amount'},
                    {name: 'VAT Rate'},
                    {name: 'VAT Amount'},
                    {name: 'Total Amount'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/expense/' + id).then(response => {
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
