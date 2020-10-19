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
        <td class="text-center">
          {{ props.data.account.name }}
        </td>
        <td class="text-center">
          {{ props.data.ref }}
        </td>
        <td class="text-center">
          {{ props.data.description }}
        </td>
        <td class="text-center">
          {{ props.data.payment_type }}{{ props.data.modelable?' - '+props.data.modelable.name:null }}
        </td>
        <td class="text-center">
          {{ props.data.amount|currency('£') }}
        </td>
        <td class="text-center">
          {{ props.data.tax_rate?props.data.tax_rate+'%':'0%' }}
        </td>
        <td class="text-center">
          {{ props.data.tax_amount|currency('£') }}
        </td>
        <td class="text-center">
          {{ props.data.after_tax_amount|currency('£') }}
        </td>

        <td class="text-center">
          <div class="adjustAction">
            <template v-if="$root.$data.user.role=='admin'">
              <router-link :to="{name:'expenseEdit', params:{id:props.data.id}}">
                <i class="fa fa-edit" />
              </router-link>

              <a
                href="#"
                @click.prevent="deleteMe(props.data.id)"
              >
                <i class="fa fa-trash" />
              </a>
            </template>
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
                this.$root.confirmationDelete().then(val => {
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
