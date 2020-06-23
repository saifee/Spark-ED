<template>
    <v-navigation-drawer
      v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1"
      v-model="drawer"
      fixed
      app
      :clipped="getConfig('sidebar_clipped') == 1"
      :right="getConfig('user_direction') === 'rtl'"
      :mini-variant="getConfig('user_sidebar') === 'mini'"
      :expand-on-hover="getConfig('user_sidebar') === 'mini'"
    >
        <main-menu></main-menu>
          <template v-slot:append>
            <v-btn-toggle
              borderless
              class="d-flex"
            >
              <v-btn
                v-if="hasPermission('access-configuration')"
                to="/configuration"
                class="flex-grow-1"
              >
                <i class="fas fa-cogs" />
              </v-btn>
              <v-btn
                to="/change/password"
                class="flex-grow-1"
              >
                <i class="fas fa-key" />
              </v-btn>
              <v-btn
                class="flex-grow-1"
                @click.prevent="logout"
              >
                <i class="fas fa-power-off" />
              </v-btn>
            </v-btn-toggle>
          </template>
    </v-navigation-drawer>
</template>

<script>
    import mainMenu from './menu-2'

    export default {
        components: {mainMenu},
        data: () => ({
            drawer: null,
        }),
        mounted() {
        },
        watch: {
          '$store.state["drawer"].navigationDrawer': function () {
            this.drawer = !this.drawer
          },
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
