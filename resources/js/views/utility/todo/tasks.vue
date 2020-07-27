<template>
  <v-card>
    <v-toolbar flat>
      <v-toolbar-title>{{ trans('spark.utility_tasks') }}</v-toolbar-title>
      <v-spacer />
      <v-dialog
        v-model="dialogAddTodoTask"
        max-width="500"
      >
        <template v-slot:activator="{ on }">
          <v-btn
            text
            color="primary"
            v-on="on"
          >
            {{trans('general.add_new')}}
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
                    v-model="todoForm.task_id"
                    outlined
                    dense
                    :items="tasks"
                    item-text="name"
                    item-value="id"
                    :label="trans('spark.utility_task')"
                    color="primary"
                    :error="todoForm.errors.has('task_id')"
                    :persistent-hint="todoForm.errors.has('task_id')"
                    :hint="todoForm.errors.get('task_id')"
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
                {{trans('general.cancel')}}
              </v-btn>
              <v-btn
                type="submit"
                color="primary"
                dark
              >
                {{trans('general.save')}}
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
      :items="todoTasks"
      disable-pagination
      hide-default-footer
    >
      <template v-slot:item.action="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          {{trans('general.edit')}}
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          {{trans('general.delete')}}
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
                    { text: 'Name', value: 'task.name', },
                    { text: 'Percentage', value: 'percentage', },
                    { text: 'Actions', value: 'action', sortable: false, },
                ],
                loading: false,
                todoTasks: [],
                dialogAddTodoTask: false,
                tasks: [],
                todoForm: new Form({
                    task_id : '',
                    percentage : '',
                })
            };
        },
        computed: {
          formTitle () {
            return this.editedIndex === -1 ? i18n.spark.utility_add_todo_task : i18n.spark.utility_edit_todo_task
          }
        },
        watch: {
            dialogAddTodoTask (val) {
                val || this.closeDialog()
            }
        },
        mounted() {
            if(this.id) {
                this.getTodoTask();
            }
        },
        methods: {
            closeDialog () {
                this.dialogAddTodoTask = false
                setTimeout(() => {
                    this.resetForm()
                    this.editedIndex = -1
                }, 300)
            },
            resetForm() {
                this.addSpecificationType = new Form({
                    task_id : '',
                    percentage : '',
                })
            },
            proceed(todoTask){
                if(todoTask.id)
                    this.updateTodoTask();
                else
                    this.storeTodoTask();
            },
            storeTodoTask(){
                let loader = this.$loading.show();
                this.todoForm.post(`/api/todo/${this.id}/tasks`)
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
            getTodoTask(){
                this.loading = true
                axios.get(`/api/todo/${this.id}/tasks`)
                    .then(response => {
                        this.todoTasks = response
                        this.loading = false
                    })
                    .catch(error => {
                        this.loading = false
                        helper.showErrorMsg(error);
                        this.$router.push('/utility/todo');
                    });
            },
            editItem(){
            },
            deleteItem(){
            },
            updateTodoTask(todoTask){
                let loader = this.$loading.show();
                this.todoForm.patch(`/api/todo/${this.id}/tasks/${todoTask.id}`)
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
