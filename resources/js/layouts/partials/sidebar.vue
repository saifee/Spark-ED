<template>
	<aside class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <main-menu></main-menu>
            </nav>
        </div>
    </aside>
</template>

<script>
    import mainMenu from './menu'

    export default {
        components: {mainMenu},
        mounted() {
        },
        methods : {
            logout(){
                helper.logout().then(() => {
                    this.$router.push('/login');
                })
            },
            getAuthUser(name){
                return helper.getAuthUser(name);
            },
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getConfig(config){
                return helper.getConfig(config);
            }
        },
        computed: {
        }
    }
</script>
