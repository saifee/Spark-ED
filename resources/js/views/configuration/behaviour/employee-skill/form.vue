<template>
  <form
    @submit.prevent="proceed"
    @keydown="employeeSkillForm.errors.clear($event.target.name)"
  >
    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.employee_skill_name') }}</label>
          <input
            v-model="employeeSkillForm.name"
            class="form-control"
            type="text"
            name="name"
            :placeholder="trans('behaviour.employee_skill_name')"
          >
          <show-error
            :form-name="employeeSkillForm"
            prop-name="name"
          />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.employee_skill_points') }}</label>
          <input
            v-model="employeeSkillForm.points"
            class="form-control"
            type="number"
            name="points"
            :placeholder="trans('behaviour.employee_skill_points')"
          >
          <show-error
            :form-name="employeeSkillForm"
            prop-name="points"
          />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.employee_skill_positive') }}</label>
          <v-switch
            v-model="employeeSkillForm.positive"
            :true-value="1"
            :false-value="0"
            :label="trans('behaviour.employee_skill_positive')"
            color="success"
          />
          <show-error
            :form-name="employeeSkillForm"
            prop-name="positive"
          />
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <router-link
        v-show="id"
        to="/configuration/behaviour/skill/employee"
        class="btn btn-danger waves-effect waves-light "
      >
        {{ trans('general.cancel') }}
      </router-link>
      <button
        v-if="!id"
        type="button"
        class="btn btn-danger waves-effect waves-light "
        @click="$emit('cancel')"
      >
        {{ trans('general.cancel') }}
      </button>
      <button
        type="submit"
        class="btn btn-info waves-effect waves-light"
      >
        <span v-if="id">{{ trans('general.update') }}</span>
        <span v-else>{{ trans('general.save') }}</span>
      </button>
    </div>
  </form>
</template>


<script>
    export default {
        props: ['id'],
        data() {
            return {
                employeeSkillForm: new Form({
                    positive : 1,
                    name : '',
                    points : 1,
                    skill_icon_id : '',
                })
            };
        },
        mounted() {
            if(this.id)
                this.get();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.update();
                else
                    this.store();
            },
            store(){
                let loader = this.$loading.show();
                this.employeeSkillForm.post('/api/behaviour/employee-skill')
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
            get(){
                let loader = this.$loading.show();
                axios.get('/api/behaviour/employee-skill/'+this.id)
                    .then(response => {
                        this.employeeSkillForm.name = response.name;
                        this.employeeSkillForm.points = response.points;
                        this.employeeSkillForm.positive = response.positive;
                        this.employeeSkillForm.skill_icon_id = response.skill_icon_id;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                        this.$router.push('/configuration/behaviour/skill/employee');
                    });
            },
            update(){
                let loader = this.$loading.show();
                this.employeeSkillForm.patch('/api/behaviour/employee-skill/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/configuration/behaviour/skill/employee');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
