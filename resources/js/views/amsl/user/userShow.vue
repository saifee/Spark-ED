<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'userCreate'"
      :table-headline="'User List'"
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
          {{ props.data.email }}
        </td>
        <td class="text-center">
          {{ props.data.role }}
        </td>
        <td class="text-center">
          <div class="adjustAction">
            <router-link :to="{name:'userEdit', params:{id:props.data.id}}">
              <i class="fa fa-edit" />
            </router-link>
            <template v-if="props.data.role!='admin'">
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
                url:'/user-info',
                columns: [
                    {name: 'Name'},
                    {name: 'Email'},
                    {name: 'Role'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/user-info/' + id).then(response => {
                            var dataTable = this.$refs.dataTable
                            dataTable.getResult();
                            /* this.$swal */({
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
