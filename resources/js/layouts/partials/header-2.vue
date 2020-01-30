<template>
    <v-app-bar
      :color="getColor()"
      app
      dark
      fixed
      :clipped-left="getConfig('sidebar_clipped') == 1"
      :clipped-right="getConfig('sidebar_clipped') == 1"
    >
      <router-link  to="/">
        <v-avatar tile>
            <v-img :src="getIcon" />
        </v-avatar>
      </router-link>
      <v-toolbar-title>{{getConfig('institute_name')}}</v-toolbar-title>
      <v-spacer />
      <v-toolbar-items>
      <v-menu
      offset-y
        v-if="getAcademicSessions.length && hasPermission('change-academic-session')"
        left
        bottom
      >
        <template v-slot:activator="{ on }">
          <v-btn text v-on="on">
            {{getDefaultAcademicSession ? getDefaultAcademicSession.name : trans('academic_session.choose_session')}} <i class="fa fa-chevron-down"></i>
          </v-btn>
        </template>

        <v-list dense>
          <v-list-item v-for="(academic_session, i) in getAcademicSessions" @click="setDefaultAcademicSession(academic_session)" :key="i">
                <v-list-item-content>
                    <v-list-item-title>{{academic_session.name}}</v-list-item-title>
                </v-list-item-content>
                <v-list-item-icon v-if="getDefaultAcademicSession && academic_session.id == getDefaultAcademicSession.id">
                  <i class="fas fa-check"></i>
                </v-list-item-icon>
          </v-list-item>
          <v-list-item to="/academic/session">
                <v-list-item-content>
                    <v-list-item-title>{{trans('academic.add_new_session')}}</v-list-item-title>
                </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn icon v-if="getConfig('todo') && hasPermission('access-todo') && getConfig('made') === 'saudi'" to="/utility/todo">
        <i class="far fa-check-circle"></i>
      </v-btn>
      <v-btn icon v-if="hasPermission('access-configuration') && getConfig('made') === 'saudi'" to="/configuration">
        <i class="fas fa-cogs"></i>
      </v-btn>
      <v-menu
      offset-y
        left
        bottom
      >
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <i class="fas fa-user"></i>
          </v-btn>
        </template>

        <v-list dense>
          <v-subheader>{{trans('general.greeting')+', '+getAuthUser('email')}}</v-subheader>
          <v-list-item to="/change/password">
              <v-list-item-action><i class="fas fa-key"></i></v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>{{trans('user.change_password')}}</v-list-item-title>
                </v-list-item-content>
          </v-list-item>
          <v-list-item @click.prevent="logout">
              <v-list-item-action><i class="fas fa-power-off"></i></v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>{{trans('auth.logout')}}</v-list-item-title>
                </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
  </v-toolbar-items>
    </v-app-bar>
</template>

<script>
    import globalSearch from './global-search'
    import mainMenu from './menu'

    export default {
        components: {globalSearch, mainMenu},
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
        },
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
        }
    }
</script>
