<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.class_story')}}
                        <span class="card-subtitle d-none d-sm-inline">{{trans('behaviour.class_story_description')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/student/behaviour')" v-tooltip="trans('behaviour.behaviour')"><i class="fas fa-gem"></i> <span class="d-none d-sm-inline">{{trans('behaviour.behaviour')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <v-container>
            <v-row>
                <v-col cols="5">
                    <v-card class="mb-4">
                        <v-card-text>
                            <v-textarea
single-line
                              label="What's happening in your classroom?"
                            >
                                <template v-slot:prepend>
                                    <v-avatar>
                                      <img
                                        src="https://cdn.vuetifyjs.com/images/john.jpg"
                                        alt="John"
                                      >
                                    </v-avatar>
                                </template>
                            </v-textarea>
                        </v-card-text>
                        <v-card-actions>
                          <v-btn
                            text
                            color="primary"
                          >
                          <v-icon left>camera_alt</v-icon>
                            Photo
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                          >
                          <v-icon left>attach_file</v-icon>
                            File
                          </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" depressed>Post</v-btn>
                        </v-card-actions>

                    </v-card>


                    <v-card class="mb-4">
                        <v-card-text>
      <v-list-item class="grow">
        <v-list-item-avatar color="grey darken-3">
          <v-img
            class="elevation-6"
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>Evan You</v-list-item-title>
        </v-list-item-content>

        <v-row
          align="center"
          justify="end"
        >
          <span class="subheading">Feb 12</span>
        </v-row>
      </v-list-item>
                I'm a thing. But, like most politicians, he promised more than he could deliver. You won't have time for sleeping, soldier, not with all the bed making you'll be doing. Then we'll go with that data file! Hey, you add a one and two zeros to that or we walk! You're going to do his laundry? I've got to find a way to escape.
                        </v-card-text>
                        <v-card-actions>
                          <v-btn
                            outlined
                            color="primary"
                          >
                          <v-icon left>favorite_border</v-icon>
                            Like
                          </v-btn>
                          <v-btn
                            outlined
                            color="primary"
                            @click="show = !show"
                          >
                          <v-icon left>chat_bubble_outline</v-icon>
                            Comment
                          </v-btn>
                            <v-spacer></v-spacer>
                          <v-btn
                            outlined
                            color="primary"
                          >
                          <v-icon left>more_horiz</v-icon>
                          </v-btn>
                        </v-card-actions>
    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>
    <v-list-item dense>
      <v-list-item-avatar color="grey"></v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>Kurt Wagner</v-list-item-title>
        <v-list-item-subtitle>This is awesome!</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>
        <v-divider></v-divider>
    <v-list-item dense>
      <v-list-item-avatar color="grey"></v-list-item-avatar>
      <v-list-item-content>
                            <v-text-field
                              label="Write a comment"
                              type="text"
                            outlined
                            dense
                            hide-details
                          ></v-text-field>

      </v-list-item-content>
    </v-list-item>

      </div>
    </v-expand-transition>

                    </v-card>


                    <v-card class="mb-4">
                        <v-card-text>
      <v-list-item class="grow">
        <v-list-item-avatar color="grey darken-3">
          <v-img
            class="elevation-6"
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>Evan You</v-list-item-title>
        </v-list-item-content>

        <v-row
          align="center"
          justify="end"
        >
          <span class="subheading">Feb 12</span>
        </v-row>
      </v-list-item>
    <v-img
      src="https://cdn.vuetifyjs.com/images/cards/mountain.jpg"
      height="194"
    ></v-img>
                        </v-card-text>
                        <v-card-actions>
                          <v-btn
                            outlined
                            color="primary"
                          >
                          <v-icon left>favorite_border</v-icon>
                            Like
                          </v-btn>
                          <v-btn
                            outlined
                            color="primary"
                          >
                          <v-icon left>chat_bubble_outline</v-icon>
                            Comment
                          </v-btn>
                            <v-spacer></v-spacer>
                          <v-btn
                            outlined
                            color="primary"
                          >
                          <v-icon left>more_horiz</v-icon>
                          </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: true,
                students: {
                    total: 0,
                    data: []
                },
            };
        },
        mounted(){
        },
        methods: {
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
