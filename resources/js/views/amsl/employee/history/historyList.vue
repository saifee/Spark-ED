<template>
  <div class="card card-table">
    <amsl-data-table
      ref="dataTable"
      :search="true"
      :columns="columns"
      :add-link="'employeeHistoryCreate'"
      :table-headline="'Employee History List'"
      :search-field="'employee.name'"
      :date-search="true"
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
          {{ props.data.employee.name }}
        </td>
        <td class="text-center">
          {{ props.data.start_date }}
        </td>
        <td class="text-center">
          {{ props.data.end_date }}
        </td>
        <td class="text-center">
          {{ $refs.dataTable.getHoursAndMinute(props.data.start_date,props.data.end_date) }}
        </td>
        <td class="text-center">
          <div class="adjustAction">
            <template v-if="$root.$data.user.role=='admin'">
              <router-link :to="{name:'employeeHistoryEdit', params:{id:props.data.id}}">
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
                url:'/employee/history',
                columns: [
                    {name: 'Name'},
                    {name: 'Start Time'},
                    {name: 'End Time'},
                    {name: 'Work Time'},
                    {name: 'Action'},
                ]
            }
        },

        methods:{

            deleteMe(id) {
                this.$root.confirmationDelete().then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/employee/history/' + id).then(response => {
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
