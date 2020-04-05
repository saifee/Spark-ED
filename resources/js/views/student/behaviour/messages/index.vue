<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.messages') }}
            <span class="card-subtitle d-none d-sm-inline">{{ trans('behaviour.messages_description') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              v-tooltip="trans('behaviour.classroom')"
              class="btn btn-info btn-sm"
              @click="$router.push(`/student/behaviour/${$route.params.batch_id}`)"
            >
              <span class="d-none d-sm-inline"><i class="fas fa-gem" /> {{ trans('behaviour.classroom') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <v-container
      class="fill-height pa-0"
      fluid
    >
      <v-row class="no-gutters">
        <v-col
          cols="3"
          class="flex-grow-1 flex-shrink-0"
          style="border-right: 1px solid #0000001f;"
        >
          <v-responsive
            class="overflow-y-auto fill-height"
            height="500"
          >
            <v-list subheader>
              <v-skeleton-loader
                v-if="loading"
                ref="skeleton"
                type="list-item-avatar-two-line"
              />
              <v-list-item-group v-model="activeChat">
                <template v-for="(item, index) in parents">
                  <v-list-item
                    :key="`parent${index}`"
                    :value="item.student_parent_id"
                  >
                    <v-list-item-avatar color="grey lighten-1 white--text">
                      <i class="fas fa-user fa-fw" />
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-list-item-title v-text="item.title" />
                      <v-list-item-subtitle v-text="`${item.student_name} ${item.relation}`" />
                    </v-list-item-content>

                    <v-list-item-icon>
                      <v-icon :color="item.active ? 'deep-purple accent-4' : 'grey'">
                        chat_bubble
                      </v-icon>
                    </v-list-item-icon>
                  </v-list-item>
                  <v-divider
                    :key="`chatDivider${index}`"
                    class="my-0"
                  />
                </template>
              </v-list-item-group>
            </v-list>
          </v-responsive>
        </v-col>
        <v-col
          cols="3"
          style="max-width: 100%;"
          class="flex-grow-1 flex-shrink-0"
        >
          <module-info
            v-if="!activeChat"
            module="behaviour"
            title="messages"
            description="messages_description"
            icon="list"
          />
          <v-responsive
            v-if="activeChat"
            class="overflow-y-auto fill-height"
            height="500"
          >
            <v-card
              flat
              class="fill-height"
            >
              <v-card-title>
                {{ parent.title }}
              </v-card-title>
              <v-card-subtitle>{{ parent.student_name }} {{ parent.relation }}</v-card-subtitle>
              <v-divider class="my-0" />
              <v-card-text class="flex-grow-1 fill-height">
                <template v-for="(msg, i) in parent.messages.data">
                  <message-bubble
                    :key="`message${i}`"
                    :message="msg"
                    :parent="parent"
                    @deleted="getMessages"
                  />
                </template>
                <module-info
                  v-if="parent.messages.total == 0"
                  module="messages"
                  :description="parent.receiver_id ? 'no_messages_yet' : 'parent_account_not_activated'"
                  icon="list"
                />
                <message-form
                  v-if="parent.receiver_id"
                  :receiver-id="parent.receiver_id"
                  :student-record-id="parent.student_record_id"
                  @completed="getMessages"
                />
              </v-card-text>
            </v-card>
          </v-responsive>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
    import messageForm from './form'
    import messageBubble from './bubble'

    export default {
        components : { messageForm, messageBubble, },
        data() {
            return {
                loading: false,
                filter: {
                  batch_id: [],
                },
                activeChat: null,
                parents: [],
            };
        },
        computed:{
            parent(){
                return this.parents.find(p => p.student_parent_id == this.activeChat)
            },
            authToken(){
                return helper.getAuthToken();
            },
        },
        watch:{
            activeChat(val){
              val && this.getMessages()
            },
        },
        mounted(){
            this.filter.batch_id = this.$route.params.batch_id
            this.getStudents()
        },
        methods: {
            getStudents(page){
                this.loading = true
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter)
                axios.get('/api/student?page=' + page + url)
                    .then(response => {
                        let students = response.student_records
                        let parents = []
                        students.data.forEach(student => {
                            parents.push({
                                receiver_id: helper.hasRole('parent') ? student.batch.class_teachers[0].employee.user_id : student.student.parent.user_id,
                                student_record_id: student.id,
                                student_name: student.student.name,
                                student_parent_id: student.student.parent.id,
                                title: student.student.parent.first_guardian_name,
                                relation: student.student.parent.first_guardian_relation,
                                active: false,
                                messages: {
                                  total: 0,
                                  data: [],
                                },
                            });
                        })
                        this.parents = parents
                        this.loading = false
                    })
                    .catch(error => {
                        this.loading = false
                        helper.showErrorMsg(error);
                    });
            },
            getMessages(page){
                if (!this.activeChat) {
                  return
                }
                if (!this.parent.receiver_id) {
                  return
                }
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL({
                  student_record_id: this.parent.student_record_id,
                })
                axios.get(`/api/behaviour/messages?page=${page}${url}`)
                    .then(response => {
                        this.parent.messages = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            hasNotAnyRole(roles){
                return helper.hasNotAnyRole(roles);
            },
            getConfig(config){
                return helper.getConfig(config);
            },
        },
    }
</script>
