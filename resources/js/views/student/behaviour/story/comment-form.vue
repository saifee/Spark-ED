<template>
  <form
    @submit.prevent="proceed"
    @keydown="storyCommentForm.errors.clear($event.target.name)"
  >
    <v-text-field
      v-model="storyCommentForm.content"
      :label="trans('story.write_a_comment')"
      type="text"
      outlined
      dense
      hide-details
    />
  </form>
</template>

<script>
    export default {
        props: ['storyId'],
        data: () => ({
          storyCommentForm: new Form({
              content : '',
          }),
        }),
        mounted(){
        },
        methods: {
            proceed(){
              this.store();
            },
            store(){
                let loader = this.$loading.show();
                this.storyCommentForm.post(`/api/behaviour/stories/${this.storyId}/comments`)
                    .then(() => {
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
