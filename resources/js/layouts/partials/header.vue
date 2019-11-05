<template>
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header white-sm">
                <router-link class="navbar-brand" to="/">
                    <img :src="getIcon" />
                </router-link>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mt-md-0 ">
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="fas fa-bars"></i></a> </li>
                    <li class="nav-item" v-tooltip.right="trans('general.toggle_sidebar')"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle fas"></i></a> </li>

                    <li class="nav-item d-none d-sm-inline" v-if="getConfig('maintenance_mode')"><span class="mt-4 badge badge-danger m-b-10">{{trans('configuration.under_maintenance')}}</span></li>
                    <li class="nav-item d-none d-sm-inline" v-if="!getConfig('mode')"><span class="mt-4 badge badge-danger m-b-10">{{trans('configuration.test_mode')}}</span></li>

                </ul>
                <ul class="navbar-nav flex-filler"></ul>
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div :class="['dropdown-menu', getConfig('user_direction') != 'rtl' ? 'dropdown-menu-right' : '']">
                            <ul class="dropdown-user">
                                <li>
                                    <div class="dw-user-box">
                                        <div class="u-text">
                                            <h6>{{trans('general.greeting')+', '+getAuthUser('email')}}</h6>
                                        </div>
                                    </div>
                                </li>
                                <li><router-link to="/change/password"><i class="fas fa-key"></i> {{trans('user.change_password')}}</router-link></li>
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
    export default {
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
        },
        computed: {
            getIcon(){
                return helper.getIcon();
            },
        }
    }
</script>
