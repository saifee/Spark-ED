<template>
  <v-card class="mb-4">
    <v-card-text class="pb-0">
      <v-list-item class="grow">
        <v-list-item-avatar color="grey lighten-1 white--text">
          <v-icon dark>person</v-icon>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title class="font-weight-bold">
            <span v-if="story.user.employee">{{ story.user.employee.name }}</span>
            <span v-if="story.user.student">{{ story.user.student.name }}</span>
            <span v-if="story.user.parent">{{ story.user.parent.name }}</span>
          </v-list-item-title>
          <v-list-item-subtitle>{{ story.batch.course.name }} {{ story.batch.name }}</v-list-item-subtitle>
        </v-list-item-content>

        <v-row
          align="center"
          justify="end"
        >
          <span class="subheading">{{ getStoryTime(story.created_at) }}</span>
        </v-row>
      </v-list-item>
      <v-row>
          <v-col cols="12">
            {{ story.content }}
          </v-col>
        <template v-if="story.type === 'photo'">
          <v-col cols="12">
            <v-img
              :src="`/storage/${story.attachment}`"
            />
          </v-col>
        </template>
        <template v-if="story.type === 'file'">
          <v-col cols="12">
            <a target="_blank" :href="`/storage/${story.attachment}`">view file</a>
          </v-col>
        </template>
        <v-col cols="12">
          <v-chip
            v-if="story.likes_count > 0"
            text-color="grey"
            class="mr-2"
            outlined
            x-small
          >
            <v-icon
              left
              x-small
            >
              favorite_border
            </v-icon>
            {{ story.likes_count }} {{ trans('story.like') }}
          </v-chip>
          <v-chip
            v-if="story.comments_count > 0"
            text-color="grey"
            class="mr-2"
            outlined
            x-small
            @click="show = !show"
          >
            <v-icon
              left
              x-small
            >
              chat_bubble_outline
            </v-icon>
            {{ story.comments_count }} {{ trans('story.comment') }}
          </v-chip>
        </v-col>
      </v-row>
      <v-divider class="my-0" />
    </v-card-text>
    <v-card-actions>
      <v-btn
        outlined
        :color="likeColor"
        small
        @click="toggleLike"
      >
        <v-icon
          left
          small
        >
          favorite_border
        </v-icon>
        <span v-if="!story.liked">{{ trans('story.like') }}</span>
        <span v-else>{{ trans('story.liked') }}</span>
      </v-btn>
      <v-btn
        outlined
        color="primary"
        small
        @click="show = !show"
      >
        <v-icon
          left
          small
        >
          chat_bubble_outline
        </v-icon>
        {{ trans('story.comment') }}
      </v-btn>
      <v-spacer />
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn
            outlined
            color="primary"
            small
            v-on="on"
          >
            <v-icon
              left
              small
            >
              more_horiz
            </v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item @click="">
            <v-list-item-title>{{ trans('general.edit') }}</v-list-item-title>
          </v-list-item>
          <v-list-item v-confirm="{ok: confirmDelete()}">
            <v-list-item-title>{{ trans('general.delete') }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-card-actions>
    <v-expand-transition>
      <div v-show="show">
        <template v-for="(comment, i) in comments">
          <v-divider
            :key="`commentDivider${comment.id}_${i}`"
            class="my-0"
          />
          <v-list-item
            :key="`commentListItem${comment.id}_${i}`"
            dense
          >
            <v-list-item-avatar color="grey lighten-1 white--text">
              <v-icon dark>person</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>
              <v-list-item-title>
                <span v-if="comment.user.employee">{{ comment.user.employee.name }}</span>
                <span v-if="comment.user.student">{{ comment.user.student.name }}</span>
                <span v-if="comment.user.parent">{{ comment.user.parent.name }}</span>
              </v-list-item-title>
              <v-list-item-subtitle>{{ comment.content }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </template>
        <v-divider class="my-0" />
        <v-list-item dense>
          <v-list-item-avatar color="grey lighten-1 white--text">
            <v-icon dark>person</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <comment-form
              :story-id="story.id"
              @completed="getStoryComments"
            />
          </v-list-item-content>
        </v-list-item>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script>
    import commentForm from './comment-form'

    export default {
        components : { commentForm, },
        props: ['story'],
        data: () => ({
          show: false,
          comments: [],
        }),
        computed: {
            likeColor(){
                return this.story.liked ? 'red' : 'primary'
            }
        },
        watch: {
          show(val) {
            val && this.getStoryComments()
          },
        },
        methods: {
          getStoryTime(createdAt) {
            return moment(createdAt).fromNow()
          },
          getStoryComments() {
              axios.get(`/api/behaviour/stories/${this.story.id}/comments`)
                  .then(response => {
                      this.comments = response
                      this.$set(this.story, 'comments_count', response.length);
                  })
                  .catch(error => {
                      helper.showErrorMsg(error);
                  });
          },
          toggleLike() {
              if (this.story.liked) {
                  axios.delete(`/api/behaviour/stories/${this.story.id}/likes/x`)
                      .then(response => {
                          this.$set(this.story, 'likes_count', +response);
                          this.$set(this.story, 'liked', false);
                      })
                      .catch(error => {
                          helper.showErrorMsg(error);
                      });
              } else {
                  axios.post(`/api/behaviour/stories/${this.story.id}/likes`)
                      .then(response => {
                          this.$set(this.story, 'likes_count', +response);
                          this.$set(this.story, 'liked', true);
                      })
                      .catch(error => {
                          helper.showErrorMsg(error);
                      });
              }
          },
          confirmDelete(){
              return dialog => this.deleteStory();
          },
          deleteStory() {
              let loader = this.$loading.show();
              axios.delete(`/api/behaviour/stories/${this.story.id}`)
                  .then(response => {
                        toastr.success(response.message);
                        this.$emit('deleted');
                        loader.hide();
                  })
                  .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                  });
          },
        },
    }
</script>
