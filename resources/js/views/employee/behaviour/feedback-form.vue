<template>
  <div class="row my-5">
    <div
      v-for="employee_skill in employee_skills"
      class="col-3 text-center"
    >
      <v-badge
        bordered
        overlap
        :content="employee_skill.points"
        :color="employee_skill.points > 0 ? 'success' : employee_skill.total == 0 ? 'warning' : 'error'"
      >
        <v-avatar
          v-if="employee_skill.skill_icon"
          :size="avatarSize"
          color="grey"
          @click="proceed(employee_skill)"
        >
          <v-icon dark>
            {{ employee_skill.skill_icon.name }}
          </v-icon>
        </v-avatar>
        <v-skeleton-loader
          v-else
          type="avatar"
        />
      </v-badge>
      <div v-if="employee_skill.name">
        {{ employee_skill.name }}
      </div>
      <div v-else>
        <v-skeleton-loader
          type="text"
        />
      </div>
    </div>
  </div>
</template>


<script>
    export default {
        props: ['employeeTermId'],
        data() {
            return {
                avatarSize: 70,
                employee_skills: [{},{},{},{},{},{}],
            };
        },
        mounted() {
            this.getPreRequisite();
        },
        methods: {
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/employee/behaviour/pre-requisite')
                    .then(response => {
                        this.employee_skills = response
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            proceed(employee_skill){
                let loader = this.$loading.show();
                axios.post('/api/employee/behaviour',{
                    employee_term_id: this.employeeTermId,
                    employee_skill_id: employee_skill.id,
                    employee_skill_points: employee_skill.points,
                })
                    .then(response => {
                        loader.hide();
                        employee_skill.points > 0 ? toastr.success(response.message) : employee_skill.points == 0 ? toastr.warning(response.message) : toastr.error(response.message);
                        this.$emit('completed');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
        }
    }
</script>
