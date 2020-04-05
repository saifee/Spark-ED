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
              v-tooltip="trans('behaviour.behaviour')"
              class="btn btn-info btn-sm"
              @click="$router.push('/student/behaviour/x')"
            >
              <span class="d-none d-sm-inline"><i class="fas fa-gem" /> {{ trans('behaviour.behaviour') }}</span>
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
                <template v-for="(msg, i) in parent.messages">
                  <v-list-item
                    :key="`message${i}`"
                    :class="{ 'd-flex flex-row-reverse': msg.me }"
                  >
                    <v-menu offset-y>
                      <template v-slot:activator="{ on }">
                        <v-hover
                          v-slot:default="{ hover }"
                        >
                          <v-chip
                            :color="msg.me ? 'primary' : ''"
                            dark
                            style="height:auto;white-space: normal;"
                            class="pa-4 mb-2"
                            v-on="on"
                          >
                            {{ msg.content }}
                            <sub
                              style="font-size: 0.5rem;"
                            >{{ msg.timestamp }}</sub>
                            <v-icon
                              v-if="hover"
                              small
                            >
                              expand_more
                            </v-icon>
                          </v-chip>
                        </v-hover>
                      </template>
                      <v-list>
                        <v-list-item v-confirm="{ok: confirmDelete()}">
                          <v-list-item-title>{{ trans('general.delete') }}</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </v-list-item>
                </template>
                <module-info
                  v-if="!parent.messages.length"
                  module="messages"
                  description="no_messages_yet"
                  icon="list"
                />
                <v-text-field
                  v-model="message"
                  :label="trans('messages.type_a_message')"
                  type="text"
                  no-details
                  outlined
                  append-outer-icon="send"
                  @keyup.enter="sendMessage"
                  @click:append-outer="sendMessage"
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
    export default {
        data() {
            return {
                loading: false,
                activeChat: null,
                message: '',
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
        mounted(){
            this.getStudents()
        },
        methods: {
            sendMessage(){
                this.parent.messages.push({
                    content: this.message,
                    timestamp: moment().format('LT'),
                    me: true,
                })
                this.message = ''
            },
            getStudents(page){
                this.loading = true
                if (typeof page !== 'number') {
                    page = 1;
                }
                axios.get('/api/student?page=' + page)
                    .then(response => {
                        let students = response.student_records
                        let parents = []
                        students.data.forEach(student => {
                            parents.push({
                                student_record_id: student.id,
                                student_name: student.student.name,
                                student_parent_id: student.student.parent.id,
                                title: student.student.parent.first_guardian_name,
                                relation: student.student.parent.first_guardian_relation,
                                active: false,
                                messages: [],
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
          confirmDelete(){
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
