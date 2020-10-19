<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'accountCreate'"
      :table-headline="'Account List'"
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
          {{ props.data.name }}
        </td>
        <td class="text-center">
          {{ props.data.account_type }}
        </td>
        <td class="text-center">
          {{ props.data.description }}
        </td>
        <td class="text-center">
          <div class="adjustAction">
            <template v-if="$root.$data.user.role=='admin'">
              <router-link :to="{name:'accountEdit', params:{id:props.data.id}}">
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
                url:'/account',
                columns: [
                    {name: 'Name'},
                    {name: 'Account Type'},
                    {name: 'Description'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/account/' + id).then(response => {
                            var dataTable = this.$refs.dataTable
                            dataTable.getResult();
                            toastr.success(response.message);/* this.$swal */({
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
