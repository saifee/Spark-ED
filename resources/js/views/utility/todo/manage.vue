<template>
    <div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('access-todo')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            if(!helper.featureAvailable('todo')){
                helper.featureNotAvailableMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>
