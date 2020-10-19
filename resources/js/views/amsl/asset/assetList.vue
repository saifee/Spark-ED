<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'assetCreate'"
      :table-headline="'Asset Transactions'"
      :search-field="'account.name'"
      :date-search="true"
      :date-search-field-name="'asset_date'"
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
          {{ props.data.payment_type }}{{ props.data.account_payable?' - '+props.data.account_payable.name:null }}
        </td>
        <td class="text-center">
          {{ props.data.amount|currency('£') }}
        </td>

        <td>
          <div class="adjustAction">
            <template v-if="$root.$data.user.role=='admin'">
              <router-link :to="{name:'assetEdit', params:{id:props.data.id}}">
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
                url:'/asset',
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
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/asset/' + id).then(response => {
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
            }
        }

    }
</script>
