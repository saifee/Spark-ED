<template>
  <form
    @submit.prevent="proceed"
    @keydown="messageForm.errors.clear($event.target.name)"
  >
    <v-text-field
      v-model="messageForm.content"
      :label="trans('messages.type_a_message')"
      type="text"
      hide-details
      outlined
      append-outer-icon="send"
      @click:append-outer="proceed"
    />
  </form>
</template>

<script>
    export default {
        props: ['receiverId', 'studentRecordId'],
        data: () => ({
          messageForm: new Form({
              receiver_id : -1,
              student_record_id : -1,
              content : '',
          }),
        }),
        methods: {
            proceed(){
              this.store();
            },
            store(){
                this.messageForm.receiver_id = this.receiverId
                this.messageForm.student_record_id = this.studentRecordId
                let loader = this.$loading.show();
                this.messageForm.post('/api/behaviour/messages')
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
