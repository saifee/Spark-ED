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
              <v-icon dark>person</v-icon>
            </v-avatar>
          </template>
        </v-textarea>
        <v-divider class="my-0" />
      </v-card-text>
      <v-card-actions>
        <v-file-input
          text
          color="primary"
          small
          prepend-icon=""
          prepend-inner-icon="camera_alt"
          :placeholder="trans('story.photo')"
          solo
          dense
          flat
          hide-details
        />
        <v-file-input
          text
          color="primary"
          small
          prepend-icon=""
          prepend-inner-icon="attach_file"
          :placeholder="trans('general.file')"
          solo
          dense
          flat
          hide-details
        />
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
