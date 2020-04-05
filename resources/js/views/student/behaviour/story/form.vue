<template>
  <form
    @submit.prevent="proceed"
    @keydown="storyForm.errors.clear($event.target.name)"
  >
    <v-card class="mb-4">
      <v-card-text class="pb-0">
        <v-textarea
          v-model="storyForm.content"
          single-line
          solo
          flat
          no-resize
          auto-grow
          :rows="1"
          :label="trans('story.story_content_placeholder')"
        >
          <template v-slot:prepend>
            <v-avatar
              :size="40"
              color="grey lighten-1 white--text"
            >
              <i class="fas fa-user fa-fw" />
            </v-avatar>
          </template>
        </v-textarea>
        <v-divider class="my-0" />
      </v-card-text>
      <v-card-actions>
        <!-- 
        <v-btn
          text
          color="primary"
          small
        >
          <v-icon
            left
            small
          >
            camera_alt
          </v-icon>
          {{ trans('story.photo') }}
        </v-btn>
        <v-btn
          text
          color="primary"
          small
        >
          <v-icon
            left
            small
          >
            attach_file
          </v-icon>
          {{ trans('general.file') }}
        </v-btn>
         -->
        <v-spacer />
        <v-btn
          color="primary"
          depressed
          @click="proceed"
        >
          {{ trans('general.post') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </form>
</template>

<script>
    export default {
        data: () => ({
          storyForm: new Form({
              type : 'text',
              batch_id : '',
              content : '',
          }),
        }),
        mounted(){
          this.storyForm.batch_id = this.$route.params.batch_id
        },
        methods: {
            proceed(){
              this.store();
            },
            store(){
                if (this.storyForm.type === '') {
                  this.storyForm.type = 'text'
                }
                let loader = this.$loading.show();
                this.storyForm.post('/api/behaviour/stories')
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
        },
    }
</script>
