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
              v-tooltip="trans('behaviour.classroom')"
              class="btn btn-info btn-sm"
              @click="$router.push(`/student/behaviour/${$route.params.batch_id}`)"
            >
              <i class="fas fa-gem" /> <span class="d-none d-sm-inline">{{ trans('behaviour.classroom') }}</span>
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
              @deleted="getStories"
            />
          </template>

          <v-card v-if="stories.total == 0">
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <p class="text-center grey--text">
                    No stories yet.
                  </p>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <v-card
            v-if="filter.current_page && filter.current_page !== filter.last_page"
            v-intersect.quiet="onIntersect"
          >
            <v-card-text class="text-center">
              <v-icon class="grey--text">
                more_horiz
              </v-icon>
            </v-card-text>
          </v-card>
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
                batch_id: null,
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
            this.filter.batch_id = this.$route.params.batch_id
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
                        this.filter.current_page = response.current_page
                        this.filter.last_page = response.last_page
                        if (response.current_page > 1) {
                            this.stories.data.push.apply(this.stories.data, response.data)
                        } else {
                        this.stories = response;
                        }
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
            onIntersect (entries, observer, isIntersecting) {
                isIntersecting && this.getStories(this.filter.current_page+1)
            },
        },
    }
</script>
