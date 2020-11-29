<template>
  <v-app-bar
    :color="getColor()"
    app
    dark
    fixed
    :clipped-left="getConfig('sidebar_clipped') == 1"
    :clipped-right="getConfig('sidebar_clipped') == 1"
  >
    <v-app-bar-nav-icon
      v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1"
      @click.stop="$store.commit('toggleNavigationDrawer')"
    />
    <v-spacer />
    <router-link to="/">
      <v-avatar
        tile
        class="mr-3"
      >
        <v-img :src="getIcon" />
      </v-avatar>
    </router-link>
    <v-toolbar-title v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1">
      {{ getConfig('institute_name') }}
    </v-toolbar-title>
    <main-menu v-if="getConfig('replace_sidebar_menu_with_top_menu') == 1" />
    <v-spacer />
    <v-toolbar-items>
      <v-menu
        v-if="getAcademicSessions.length && hasPermission('change-academic-session')"
        offset-y
        left
        bottom
      >
        <template v-slot:activator="{ on }">
          <v-btn
            text
            v-on="on"
          >
            {{ getDefaultAcademicSession ? getDefaultAcademicSession.name : trans('academic_session.choose_session') }} <i class="fa fa-chevron-down" />
          </v-btn>
        </template>

        <v-list dense>
          <v-list-item
            v-for="(academic_session, i) in getAcademicSessions"
            :key="i"
            @click="setDefaultAcademicSession(academic_session)"
          >
            <v-list-item-content>
              <v-list-item-title>{{ academic_session.name }}</v-list-item-title>
            </v-list-item-content>
            <v-list-item-icon v-if="getDefaultAcademicSession && academic_session.id == getDefaultAcademicSession.id">
              <i class="fas fa-check" />
            </v-list-item-icon>
          </v-list-item>
          <v-list-item to="/academic/session">
            <v-list-item-content>
              <v-list-item-title>{{ trans('academic.add_new_session') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn
        v-if="getConfig('todo') && hasPermission('access-todo') && getConfig('made') === 'saudi'"
        icon
        to="/utility/todo"
      >
        <i class="far fa-check-circle" />
      </v-btn>
      <v-btn
        v-if="hasPermission('access-configuration') && getConfig('made') === 'saudi'"
        icon
        to="/configuration"
      >
        <i class="fas fa-cogs" />
      </v-btn>
      <v-menu
        offset-y
        left
        bottom
      >
        <template v-slot:activator="{ on }">
          <v-btn
            icon
            v-on="on"
          >
            <i class="fas fa-user" />
          </v-btn>
        </template>

        <v-list dense>
          <v-subheader>{{ trans('general.greeting')+', '+getAuthUser('email') }}</v-subheader>
          <v-list-item to="/change/password">
            <v-list-item-action><i class="fas fa-key" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('user.change_password') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item @click.prevent="logout">
            <v-list-item-action><i class="fas fa-power-off" /></v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ trans('auth.logout') }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-toolbar-items>
  </v-app-bar>
</template>

<script>
    import globalSearch from './global-search'
    import mainMenu from './menu-2-horizontal'

    export default {
        components: {globalSearch, mainMenu},
        computed: {
            getIcon(){
                return helper.getIcon();
            },
            getAcademicSessions(){
                return helper.getAcademicSessions();
            },
            getDefaultAcademicSession(){
                return helper.getDefaultAcademicSession();
            }
        },
        mounted() {
        },
        methods : {
            logout(){
                helper.logout().then(() => {
                    this.$router.push('/login')
                })
            },
            getAuthUser(name){
                return helper.getAuthUser(name);
            },
            getConfig(name){
                return helper.getConfig(name);
            },
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
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            hasRole(role){
                return helper.hasRole(role);
            },
            setDefaultAcademicSession(academic_session){
                axios.post('/api/academic/session/'+academic_session.id+'/user/default')
                    .then(response => {
                        this.$store.dispatch('setDefaultAcademicSession',academic_session);
                        location.reload();
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
