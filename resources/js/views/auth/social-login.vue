<template>
  <div class="mb-8">
    <v-btn 
      block
      dark
      depressed
      class="mb-1"
      color="#D44638"
      @click="authenticate('google')"
    >
      Continue with Google
    </v-btn>
    <v-btn 
      block
      dark
      depressed
      class="mb-1"
      color="#0065b3"
      @click="authenticate('live')"
    >
      Continue with Microsoft
    </v-btn>
  </div>
</template>

<script>
    export default {
        methods: {
            authenticate(provider){
                this.$auth.authenticate(provider).then(function () {
                    // Execute application logic after successful social authentication
                })
            },
        }
    }
</script>
