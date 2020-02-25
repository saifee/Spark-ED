<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.messages')}}
                        <span class="card-subtitle d-none d-sm-inline">{{trans('behaviour.messages_description')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/student/behaviour')" v-tooltip="trans('behaviour.behaviour')"><span class="d-none d-sm-inline"><i class="fas fa-gem"></i> {{trans('behaviour.behaviour')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
            <v-navigation-drawer
            absolute
              permanent
            >
                <v-list subheader>
                  <v-subheader>Recent chat</v-subheader>
                  <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    @click=""
                  >
                    <v-list-item-avatar>
                      <v-img :src="item.avatar"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-list-item-title v-text="item.title"></v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-icon>
                      <v-icon :color="item.active ? 'deep-purple accent-4' : 'grey'">chat_bubble</v-icon>
                    </v-list-item-icon>
                  </v-list-item>
                </v-list>
            </v-navigation-drawer>
                    <v-row no-gutters>
                          <v-col xs="12" offset-sm="3">
                    <module-info v-if="!students.total" module="behaviour" title="messages" description="messages_description" icon="list">
                    </module-info>
                            <v-text-field
                              label="Type a message"
                              v-model="message"
                              type="text"
                            outlined
                            append-outer-icon="send"
                            @click:append-outer="sendMessage"
                          ></v-text-field>
                      </v-col>
                    </v-row>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: '',
                  items: [
                    { active: true, title: 'Jason Oner', avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg' },
                    { active: true, title: 'Ranee Carlson', avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg' },
                    { title: 'Cindy Baker', avatar: 'https://cdn.vuetifyjs.com/images/lists/3.jpg' },
                    { title: 'Ali Connors', avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg' },
                  ],
                students: {
                    total: 0,
                    data: []
                },
            };
        },
        mounted(){
        },
        methods: {
            sendMessage(){
                return this.message = '';
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
        computed:{
            authToken(){
                return helper.getAuthToken();
            }
        },
        filters: {
        },
    }
</script>
