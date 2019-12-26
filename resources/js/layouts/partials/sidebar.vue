<template>
	<aside class="left-sidebar" v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <main-menu></main-menu>
            </nav>
        </div>
        <div class="sidebar-footer" v-if="getConfig('made') === 'saudi'">
            <router-link v-if="hasPermission('access-configuration')" to="/configuration" class="link" v-tooltip="trans('configuration.configuration')"><i class="fas fa-cogs"></i></router-link>
            <router-link to="/change/password" class="link" v-tooltip="trans('user.change_password')"><i class="fas fa-key"></i></router-link>
            <a href="#" class="link" v-tooltip="trans('auth.logout')" @click.prevent="logout"><i class="fas fa-power-off"></i></a>
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
