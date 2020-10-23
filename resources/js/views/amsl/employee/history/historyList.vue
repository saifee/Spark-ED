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
        <td>
          {{ props.data.employee.name }}
        </td>
        <td>
          {{ props.data.start_date }}
        </td>
        <td>
          {{ props.data.end_date }}
        </td>
        <td>
          {{ $refs.dataTable.getHoursAndMinute(props.data.start_date,props.data.end_date) }}
        </td>
        <td>
          <div class="adjustAction">
            <v-btn
              :to="{name:'employeeHistoryEdit', params:{id:props.data.id}}"
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
                url:'/amsl-api'+'/employee/history',
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
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
                    if (val) {
                        axios.delete('/amsl-api'+'/employee/history/' + id).then(response => {
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
