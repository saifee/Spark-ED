<template>
  <v-card>
    <v-toolbar flat>
      <v-toolbar-title>{{ trans('spark.utility_skills_required') }}</v-toolbar-title>
      <v-spacer />
      <v-dialog
        v-model="dialogAddTodoEmployeeSkill"
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
                    v-model="todoForm.employee_skill_id"
                    outlined
                    dense
                    :items="employee_skills"
                    item-text="name"
                    item-value="id"
                    :label="trans('spark.utility_employee_skill')"
                    color="primary"
                    :error="todoForm.errors.has('employee_skill_id')"
                    :persistent-hint="todoForm.errors.has('employee_skill_id')"
                    :hint="todoForm.errors.get('employee_skill_id')"
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
                    :label="trans('spark.utility_percentage')"
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
      :items="todoSkills"
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
                    { text: 'Name', value: 'employee_skill.name', },
                    { text: 'Percentage', value: 'percentage', },
                    { text: 'Actions', value: 'action', sortable: false, },
                ],
                loading: false,
                todoSkills: [],
                dialogAddTodoEmployeeSkill: false,
                dialogAddTodoEmployeeSkill: false,
                employee_skills: [],
                todoForm: new Form({
                    employee_skill_id : '',
                    percentage : '',
                })
            };
        },
        computed: {
          formTitle () {
            return this.editedIndex === -1 ? i18n.spark.utility_add_todo_employee_skill : i18n.spark.utility_edit_todo_employee_skill
          }
        },
        mounted() {
            if(this.id) {
                this.getTodoEmployeeSkill();
                this.getEmployeeSkills();
            }
        },
        methods: {
            closeDialog(){
                //
            },
            proceed(todoEmployeeSkill){
                if(todoEmployeeSkill.id)
                    this.updateTodoEmployeeSkill();
                else
                    this.storeTodoEmployeeSkill();
            },
            storeTodoEmployeeSkill(){
                let loader = this.$loading.show();
                this.todoForm.post(`/api/todo/${this.id}/skills`)
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
            getTodoEmployeeSkill(){
                this.loading = true
                axios.get(`/api/todo/${this.id}/skills`)
                    .then(response => {
                        this.todoSkills = response
                        this.loading = false
                    })
                    .catch(error => {
                        this.loading = false
                        helper.showErrorMsg(error);
                        this.$router.push('/utility/todo');
                    });
            },
            getEmployeeSkills(){
                axios.get(`/api/behaviour/employee-skill`)
                    .then(response => {
                        this.employee_skills = response.data
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                        this.$router.push('/utility/todo');
                    });
            },
            editItem(){
            },
            deleteItem(){
            },
            updateTodoEmployeeSkill(todoEmployeeSkill){
                let loader = this.$loading.show();
                this.todoForm.patch(`/api/todo/${this.id}/skills/${todoEmployeeSkill.id}`)
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
