<template>
    <div class="row my-5">
        <div class="col-3 text-center" v-for="skill in skills">
          <v-badge
            bordered
            overlap
            :content="skill.points"
            :color="skill.points > 0 ? 'success' : skill.total == 0 ? 'warning' : 'error'"
          >
            <v-avatar
                v-if="skill.skill_icon"
                @click="proceed(skill)"
                :size="avatarSize"
                color="grey"
            >
                <v-icon dark>{{ skill.skill_icon.name }}</v-icon>
            </v-avatar>
            <v-skeleton-loader
              v-else
              type="avatar"
            ></v-skeleton-loader>
          </v-badge>
          <div v-if="skill.name">{{skill.name}}</div>
          <div v-else>
              <v-skeleton-loader
                type="text"
              ></v-skeleton-loader>
          </div>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                avatarSize: 70,
                skills: [{},{},{},{},{},{}],
            };
        },
        props: ['batchId', 'studentRecordId'],
        mounted() {
            this.getPreRequisite();
        },
        methods: {
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/student/behaviour/pre-requisite')
                    .then(response => {
                        this.skills = response.filter(skill => skill.batch_id == this.batchId);
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            proceed(skill){
                let loader = this.$loading.show();
                axios.post('/api/student/behaviour',{
                    student_record_id: this.studentRecordId,
                    skill_id: skill.id,
                    skill_points: skill.points,
                })
                    .then(response => {
                        loader.hide();
                        skill.points > 0 ? toastr.success(response.message) : skill.points == 0 ? toastr.warning(response.message) : toastr.error(response.message);
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
