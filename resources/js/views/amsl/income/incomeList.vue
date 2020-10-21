<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'incomeCreate'"
      :table-headline="'Sales & Income List'"
      :search-field="'account.name'"
      :date-search="true"
      :date-search-field-name="'income_date'"
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
          {{ props.data.payment_type }}{{ props.data.account_receivable?' - '+props.data.account_receivable.name:null }}
        </td>
        <td class="text-center">
          {{ props.data.amount|currency }}
        </td>
        <td class="text-center">
          {{ props.data.tax_rate?props.data.tax_rate+'%':'0%' }}
        </td>
        <td class="text-center">
          {{ props.data.tax_amount|currency }}
        </td>
        <td class="text-center">
          {{ props.data.after_tax_amount|currency }}
        </td>

        <td>
          <div class="adjustAction">
            <v-btn
              :to="{name:'incomeEdit', params:{id:props.data.id}}"
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
                url:'/amsl-api'+'/income',
                columns: [
                    {name: 'Account'},
                    {name: 'Ref'},
                    {name: 'Description'},
                    {name: 'Payment Type'},
                    {name: 'Amount'},
                    {name: 'VAT Rate'},
                    {name: 'VAT Aamount'},
                    {name: 'Total Amount'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/income/' + id).then(response => {
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
