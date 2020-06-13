<template>
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header white-sm" v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1">
                <router-link class="navbar-brand" to="/">
                    <img :src="getIcon" />
                </router-link>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mt-md-0 ">
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="fas fa-bars"></i></a> </li>
                    <template v-if="getConfig('replace_sidebar_menu_with_top_menu') != 1">
                    <li class="nav-item" v-tooltip.right="trans('general.toggle_sidebar')"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle fas fa-arrow-circle-left"></i></a> </li>
                    </template>
                    <template v-else>
                    <li class="flattened"><main-menu v-if="getConfig('replace_sidebar_menu_with_top_menu') == 1" /></li>
                    </template>

                    <li class="nav-item d-none d-sm-inline" v-if="getConfig('maintenance_mode')"><span class="mt-4 badge badge-danger m-b-10">{{trans('configuration.under_maintenance')}}</span></li>
                    <li class="nav-item d-none d-sm-inline" v-if="!getConfig('mode')"><span class="mt-4 badge badge-danger m-b-10">{{trans('configuration.test_mode')}}</span></li>

                    <li class="nav-item" style="color: #fff; font-size: 20px; font-weight: 100; padding: 18px 10px; letter-spacing: 2px; text-transform: uppercase;">{{getConfig('institute_name')}}</li>
                </ul>
                <ul class="navbar-nav flex-filler"></ul>
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item hidden-sm-down d-none d-md-inline" v-if="getConfig('made') === 'saudi'">
                        <global-search></global-search>
                    </li>
                    <li class="nav-item dropdown" v-if="getAcademicSessions.length && hasPermission('change-academic-session')">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip.bottom="trans('academic_session.academic_session')">{{getDefaultAcademicSession ? getDefaultAcademicSession.name : trans('academic_session.choose_session')}} <i class="fa fa-chevron-down"></i> </a>
                        <div :class="['dropdown-menu', getConfig('direction') != 'rtl' ? 'dropdown-menu-right' : '']">
                            <ul class="dropdown-user" style="padding-bottom:10px;">
                                <li v-for="academic_session in getAcademicSessions" @click="setDefaultAcademicSession(academic_session)" style="padding:10px 20px 0 20px;cursor:pointer;">
                                    {{academic_session.name}}
                                    <span class="pull-right" v-if="getDefaultAcademicSession && academic_session.id == getDefaultAcademicSession.id"><i class="fas fa-check"></i></span>
                                </li>
                                <li v-if="hasPermission('create-academic-session')" @click="$router.push('/academic/session')" style="padding:10px 20px 0 20px;cursor:pointer;">{{trans('academic.add_new_session')}}</li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown" v-if="getAcademicSessions.length && !hasPermission('change-academic-session')">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#">{{getDefaultAcademicSession ? getDefaultAcademicSession.name : ''}}</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline" v-tooltip.bottom="trans('todo.todo')" v-if="getConfig('todo') && hasPermission('access-todo') && getConfig('made') === 'saudi'"">
                        <router-link class="nav-link" to="/utility/todo"><i class="far fa-check-circle"></i></router-link>
                    </li>
                    <li class="nav-item" v-tooltip.bottom="trans('configuration.configuration')" v-if="hasPermission('access-configuration') && getConfig('made') === 'saudi'"">
                        <router-link class="nav-link" to="/configuration"><i class="fas fa-cogs"></i></router-link>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div :class="['dropdown-menu', getConfig('user_direction') != 'rtl' ? 'dropdown-menu-right' : '']">
                            <ul class="dropdown-user">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <h6>{{trans('general.greeting')+', '+getAuthUser('name')+' ('+getAuthUserRoles+')'}}</h6>
                                        </div>
                                    </div>
                                </li>
                                <li><router-link to="/change/password"><i class="fas fa-key"></i> {{trans('user.change_password')}}</router-link></li>
                                <template v-if="getConfig('pb')">
                                    <li v-if="hasRole('admin') && getConfig('made') === 'saudi'"><router-link to="/product/about"><i class="fas fa-user-tie"></i> {{trans('general.about')}}</router-link></li>
                                    <li v-if="hasRole('admin') && getConfig('made') === 'saudi'"><router-link to="/product/support"><i class="fas fa-life-ring"></i> {{trans('general.support')}}</router-link></li>
                                    <li v-if="hasRole('admin') && getConfig('made') === 'saudi'"><router-link to="/product/update"><i class="fas fa-download"></i> {{trans('general.update')}}</router-link></li>
                                </template>
                                <li role="separator" class="divider"></li>
                                <li><a href="#" @click.prevent="logout"><i class="fas fa-power-off"></i> {{trans('auth.logout')}}</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
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

<style>
.flattened ul#sidebarnav {
    height: auto;
    padding: 0;
    list-style: none;
}
.flattened > ul#sidebarnav > li {
    display: inline;
}
.flattened ul#sidebarnav li a {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    font-size: 17px;
    line-height: 50px;
    color: #fffc !important;
}
.flattened ul#sidebarnav li a .hide-menu {
    display: none;
}
.flattened ul#sidebarnav li.active {
    position: relative;
}
.flattened ul#sidebarnav li > .collapse {
    position: absolute;
    list-style: none;
    background: #ccc;
    padding:0;
}
</style>
