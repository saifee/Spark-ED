<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.class_story') }}
            <span class="card-subtitle d-none d-sm-inline">{{ trans('behaviour.class_story_description') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              v-tooltip="trans('behaviour.behaviour')"
              class="btn btn-info btn-sm"
              @click="$router.push('/student/behaviour')"
            >
              <i class="fas fa-gem" /> <span class="d-none d-sm-inline">{{ trans('behaviour.behaviour') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <v-container>
      <v-row>
        <v-col
          cols="5"
          offset="2"
        >
          <story-form
            class="mb-4"
            @completed="getStories"
          />

          <template v-for="(story, i) in stories.data">
            <story-template
              :key="`story${story.id}_${i}`"
              :story="story"
            />
          </template>
        </v-col>
        <v-col cols="3">
          <v-card class="mb-4">
            <v-card-title>{{ trans('story.upcoming_events') }}</v-card-title>
            <v-card-text>
              <span class="grey--text">{{ trans('story.no_upcoming_events') }}</span>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
    import storyForm from './form'
    import storyTemplate from './story'

    export default {
        components : { storyForm, storyTemplate },
        filters: {
        },
        data:() => ({
            filter: {
                sort_by: 'id',
                order: 'desc',
                page_length: helper.getConfig('page_length')
            },
            stories: {
                total: 0,
                data: []
            },
        }),
        computed:{
            authToken(){
                return helper.getAuthToken();
            }
        },
        mounted(){
            this.getStories();
        },
        methods: {
            getStories(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/behaviour/stories?page=' + page + url)
                    .then(response => {
                        this.stories = response;
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
