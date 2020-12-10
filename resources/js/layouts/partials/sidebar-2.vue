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
    <v-list-item
      :style="`background-color:${getColor()}`"
      to="/"
      exact
      class="elevation-4"
    >
      <v-list-item-avatar tile height="48" width="48"><v-img :src="getIcon" /></v-list-item-avatar>
    </v-list-item>
    <main-menu />
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
        computed: {
            getIcon(){
                return helper.getIcon();
            },
        },
        watch: {
          '$store.state.navigationDrawer': function () {
            this.drawer = !this.drawer
          },
        },
        mounted() {
        },
        methods : {
            getColor(){
                let color = helper.getConfig('user_color_theme')
                if (color === 'green') {
                    return '#55ce63'
                }
                if (color === 'red') {
                    return '#fb3a3a'
                }
                if (color === 'purple') {
                    return '#7460ee'
                }
                if (color === 'blue') {
                    return '#02bec9'
                }
                if (color === 'megna') {
                    return '#01c0c8'
                }
                return '#02bec9' // default same as blue
            },
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
        }
    }
</script>
