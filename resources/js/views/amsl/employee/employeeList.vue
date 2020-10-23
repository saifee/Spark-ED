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
        <td>
          {{ props.data.name }}
        </td>
        <td>
          {{ props.data.id_card }}
        </td>
        <td>
          {{ props.data.phone }}
        </td>
        <td>
          {{ props.data.email }}
        </td>
        <td>
          {{ props.data.address }}
        </td>
        <td>
          <div class="adjustAction">
            <v-btn
              :to="{name:'accountEdit', params:{id:props.data.id}}"
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
                /* this.$root.confirmationDelete() */ Promise.resolve(true).then(val => {
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
