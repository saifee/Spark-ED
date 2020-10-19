<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'employeeCreate'"
      :table-headline="'Employee List'"
      :search-field="'name'"
      :date-search-field-name="'start_date'"
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
          {{ props.data.id_card }}
        </td>
        <td class="text-center">
          {{ props.data.phone }}
        </td>
        <td class="text-center">
          {{ props.data.email }}
        </td>
        <td class="text-center">
          {{ props.data.address }}
        </td>
        <td class="text-center">
          <div class="adjustAction">
            <template v-if="$root.$data.user.role=='admin'">
              <router-link :to="{name:'employeeEditAmsl', params:{id:props.data.id}}">
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
                url:'/amsl-api'+'/employee',
                columns: [
                    {name: 'Name'},
                    {name: 'Id No'},
                    {name: 'Phone'},
                    {name: 'Email'},
                    {name: 'Address'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/employee/' + id).then(response => {
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
