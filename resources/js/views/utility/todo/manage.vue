<template>
  <div>
    <div class="page-titles">
      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <h3 class="text-themecolor">
            {{ trans('utility.edit_todo') }}
          </h3>
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          <div class="action-buttons pull-right">
            <v-btn
              small
              color="primary"
              depressed
              @click="$router.push('/utility/todo')"
            >
              <i class="fas fa-list" /> <span class="d-none d-sm-inline">{{ trans('utility.todo') }}</span>
            </v-btn>
          </div>
        </v-col>
      </v-row>
    </div>
    <v-row class="row">
      <v-col
        cols="12"
        md="6"
      >
        <tasks-form :id="id" />
      </v-col>
      <v-col
        cols="12"
        md="6"
      >
        <team-form :id="id" />
      </v-col>
      <v-col
        cols="12"
        md="6"
      >
        <skills-form :id="id" />
      </v-col>
    </v-row>
  </div>
</template>

<script>
    import tasksForm from './tasks';
    import teamForm from './team';
    import skillsForm from './skills';

    export default {
        components : { tasksForm,teamForm,skillsForm, },
        data() {
            return {
                todo:null,
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('access-todo')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            if(!helper.featureAvailable('todo')){
                helper.featureNotAvailableMsg();
                this.$router.push('/dashboard');
            }

            if(this.id)
                this.getTodo()
        },
        methods: {
            getTodo(){
                let loader = this.$loading.show();
                axios.get(`/api/todo/${this.id}`)
                    .then(response => {
                        this.todo = response
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                        this.$router.push('/utility/todo');
                    });
            },
        }
    }
</script>
