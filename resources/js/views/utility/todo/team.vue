<template>
  <v-card>
    <v-toolbar flat>
      <v-toolbar-title>{{ trans('todo.engage_others') }}</v-toolbar-title>
      <v-spacer />
      <v-dialog
        v-model="dialogAddTodoEmployeeEmployee"
        max-width="500"
      >
        <template v-slot:activator="{ on }">
          <v-btn
            text
            color="primary"
            v-on="on"
          >
            Add New
          </v-btn>
        </template>
        <form
          @submit.prevent="proceed"
          @keydown="todoForm.errors.clear($event.target.name)"
        >
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-row>
                <v-col
                  cols="12"
                  md="6"
                >
                  <v-autocomplete
                    v-model="todoForm.employee_employee_id"
                    outlined
                    dense
                    :items="employee_employees"
                    item-text="name"
                    item-value="id"
                    :label="trans('todo.employee_employee')"
                    color="primary"
                    :error="todoForm.errors.has('employee_employee_id')"
                    :persistent-hint="todoForm.errors.has('employee_employee_id')"
                    :hint="todoForm.errors.get('employee_employee_id')"
                  />
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  <v-text-field
                    v-model="todoForm.percentage"
                    outlined
                    dense
                    :label="trans('todo.percentage')"
                    color="primary"
                    :error="todoForm.errors.has('percentage')"
                    :persistent-hint="todoForm.errors.has('percentage')"
                    :hint="todoForm.errors.get('percentage')"
                  />
                </v-col>
              </v-row>
            </v-card-text>

            <v-card-actions>
              <v-spacer />
              <v-btn
                type="button"
                text
                @click.native="closeDialog"
              >
                Cancel
              </v-btn>
              <v-btn
                type="submit"
                color="primary"
                dark
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-dialog>
    </v-toolbar>

    <v-divider class="ma-0" />

    <v-data-table
      :headers="headers"
      :loading="loading"
      :items="todoEmployees"
      disable-pagination
      hide-default-footer
    >
      <template v-slot:item.action="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          edit
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          delete
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>


<script>
    export default {
        components: {},
        props: ['id'],
        data() {
            return {
                headers: [
                    { text: 'Name', value: 'employee_employee.name', },
                    { text: 'Percentage', value: 'percentage', },
                    { text: 'Actions', value: 'action', sortable: false, },
                ],
                loading: false,
                todoEmployees: [],
                dialogAddTodoEmployeeEmployee: false,
                dialogAddTodoEmployeeEmployee: false,
                employee_employees: [],
                todoForm: new Form({
                    employee_employee_id : '',
                    percentage : '',
                })
            };
        },
        computed: {
          formTitle () {
            return this.editedIndex === -1 ? i18n.todo.add_todo_employee_employee : i18n.todo.edit_todo_employee_employee
          }
        },
        mounted() {
            if(this.id) {
                this.getTodoEmployeeEmployee();
                this.getEmployeeEmployees();
            }
        },
        methods: {
            closeDialog(){
                //
            },
            proceed(todoEmployeeEmployee){
                if(todoEmployeeEmployee.id)
                    this.updateTodoEmployeeEmployee();
                else
                    this.storeTodoEmployeeEmployee();
            },
            storeTodoEmployeeEmployee(){
                let loader = this.$loading.show();
                this.todoForm.post(`/api/todo/${this.id}/employees`)
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getTodoEmployeeEmployee(){
                this.loading = true
                axios.get(`/api/todo/${this.id}/employees`)
                    .then(response => {
                        this.todoEmployees = response
                        this.loading = false
                    })
                    .catch(error => {
                        this.loading = false
                        helper.showErrorMsg(error);
                        this.$router.push('/utility/todo');
                    });
            },
            getEmployeeEmployees(){
                // axios.get(`/api/behaviour/employee-employee`)
                //     .then(response => {
                //         this.employee_employees = response.data
                //     })
                //     .catch(error => {
                //         helper.showErrorMsg(error);
                //         this.$router.push('/utility/todo');
                //     });
            },
            editItem(){
            },
            deleteItem(){
            },
            updateTodoEmployeeEmployee(todoEmployeeEmployee){
                let loader = this.$loading.show();
                this.todoForm.patch(`/api/todo/${this.id}/employees/${todoEmployeeEmployee.id}`)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/utility/todo');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
