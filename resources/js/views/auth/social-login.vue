<template>
    <div>
        <button type="button" class="btn btn-info btn-block text-uppercase waves-effect waves-light" @click="authenticate('google')" style="background-color:#D44638;border:0;border-radius:0;">Continue with Google</button>
        <button type="button" class="btn btn-info btn-block text-uppercase waves-effect waves-light mb-4" @click="authenticate('live')" style="background-color:#0065b3;border:0;border-radius:0;">Continue with Microsoft</button>
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
