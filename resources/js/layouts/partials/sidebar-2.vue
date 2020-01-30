<template>
    <v-navigation-drawer
      fixed
      app
      :clipped="getConfig('sidebar_clipped') == 1"
      :right="getConfig('user_direction') === 'rtl'"
      :mini-variant="getConfig('user_sidebar') === 'mini'"
      :expand-on-hover="getConfig('user_sidebar') === 'mini'"
    >
        <main-menu></main-menu>
          <template v-slot:append>
            <v-btn-toggle borderless>
                <v-btn v-if="hasPermission('access-configuration')" to="/configuration"><i class="fas fa-cogs"></i></v-btn>
                <v-btn to="/change/password"><i class="fas fa-key"></i></v-btn>
                <v-btn @click.prevent="logout"><i class="fas fa-power-off"></i></v-btn>
            </v-btn-toggle>
          </template>
    </v-navigation-drawer>
</template>

<script>
    import mainMenu from './menu-2'

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
