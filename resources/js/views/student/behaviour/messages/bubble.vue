<template>
  <v-list-item
    :class="{ 'd-flex flex-row-reverse': message.receiver_id === parent.receiver_id }"
  >
    <v-menu offset-y>
      <template v-slot:activator="{ on }">
        <v-hover
          v-slot:default="{ hover }"
        >
          <v-chip
            :color="message.receiver_id === parent.receiver_id ? 'primary' : ''"
            dark
            style="height:auto;white-space: normal;"
            class="pa-4 mb-2"
            v-on="on"
          >
            {{ message.content }}
            <sub
              style="font-size: 0.5rem;"
            >{{ getTime(message.created_at) }}</sub>
            <v-icon
              v-if="hover"
              small
            >
              expand_more
            </v-icon>
          </v-chip>
        </v-hover>
      </template>
      <v-list>
        <v-list-item v-confirm="{ok: confirmDelete(message.id)}">
          <v-list-item-title>{{ trans('general.delete') }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-list-item>
</template>

<script>
    export default {
        props: ['message', 'parent'],
        data: () => ({
        }),
        methods: {
          getTime(timestamp){
              return moment(timestamp).format('LT')
          },
          confirmDelete(messageId){
              return dialog => this.deleteMessage(messageId);
          },
          deleteMessage(messageId) {
              let loader = this.$loading.show();
              axios.delete(`/api/behaviour/messages/${messageId}`)
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
