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
          v-model="photo"
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
          v-model="file"
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
          photo: null,
          file: null,
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
                let data = new FormData()
                data.append('type', this.storyForm.type)
                data.append('batch_id', this.storyForm.batch_id)
                data.append('content', this.storyForm.content)
                if (this.photo) {
                  data.append('type', 'photo')
                  data.append('photo', this.photo)
                }
                if (this.file) {
                  data.append('type', 'file')
                  data.append('file', this.file)
                }
                let loader = this.$loading.show();
                let headers = {
                  'Content-Type': 'multipart/form-data'
                }
                this.axios.post('/api/behaviour/stories', data, { headers })
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
