<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('general.home')}}</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <!-- <button class="btn btn-info btn-sm right-sidebar-toggle " v-tooltip="trans('user.user_preference')"><i class="fas fa-cog"></i></button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
            </div>
        </div>
        <div class="right-sidebar">
            <div class="slimscrollright">
                <div class="rpanel-title"> 
                    {{trans('user.user_preference')}} 
                    <button class="btn btn-danger btn-sm right-sidebar-toggle pull-right "><i class="fas fa-times"></i></button>
                </div>
                <div class="r-panel-body">
                    <form @submit.prevent="updatePreference" @keydown="preferenceForm.errors.clear($event.target.name)">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.color_theme')}}</label>
                                    <select v-model="preferenceForm.color_theme" class="custom-select col-12">
                                      <option v-for="option in color_themes" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="color_theme"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.direction')}}</label>
                                    <select v-model="preferenceForm.direction" class="custom-select col-12">
                                      <option v-for="option in directions" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="direction"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.sidebar')}}</label>
                                    <select v-model="preferenceForm.sidebar" class="custom-select col-12">
                                      <option v-for="option in sidebar" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="sidebar"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{trans('configuration.locale')}}</label>
                                    <select v-model="preferenceForm.locale" class="custom-select col-12">
                                      <option v-for="option in locales" v-bind:value="option.value">
                                        {{ option.text }}
                                      </option>
                                    </select>
                                    <show-error :form-name="preferenceForm" prop-name="sidebar"></show-error>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info waves-effect waves-light pull-right m-t-10">{{trans('general.save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import NoticeHighlight from '@components/notice-highlight'
    import EventsList from '@js/widgets/events-list'
    import ArticlesList from '@js/widgets/articles-list'
    import barChart from './chart/bar-chart'
    import calendar from './calendar/calendar'
    import todoWidget from './utility/todo/widget'

    export default {
        components: {
            NoticeHighlight,
            EventsList,
            ArticlesList,
            barChart,
            calendar,
            todoWidget
        },
        data() {
            return {
                color_themes: [],
                directions: [],
                sidebar: [],
                locales: [],
                preferenceForm: new Form({
                    color_theme: helper.getConfig('user_color_theme') || helper.getConfig('color_theme'),
                    direction: helper.getConfig('user_direction') || helper.getConfig('direction'),
                    locale: helper.getConfig('user_locale') || helper.getConfig('locale'),
                    sidebar: helper.getConfig('user_sidebar') || helper.getConfig('sidebar')
                },false),
                user_preference: {
                    color_theme: helper.getConfig('user_color_theme') || helper.getConfig('color_theme'),
                    direction: helper.getConfig('user_direction') || helper.getConfig('direction'),
                    locale: helper.getConfig('user_locale') || helper.getConfig('locale'),
                    sidebar: helper.getConfig('user_sidebar') || helper.getConfig('sidebar')
                },
                showModal: false,
                chart: {
                    strength: {
                        labels: [],
                        datasets: []
                    }
                },
                total_strength: 0,
                strength_chart_type: 'course',
                birthday_count: 0,
                anniversary_count: 0,
                work_anniversary_count: 0,
                total_book_count: 0,
                pending_return_book_count: 0,
                overdue_return_book_count: 0,
                articles: [],
                events: [],
                showTourVideo: false
            }
        },
        mounted(){
            if(this.$route.query.reload)
                window.location = window.location.pathname;

            helper.showDemoNotification(['dashboard_academic','dashboard_student','dashboard_student_attendance','dashboard_employee','dashboard_finance','dashboard_transport','dashboard_frontend','dashboard_post','dashboard_calendar','dashboard_thanks']);

            this.getData();

            if (this.hasAnyRole(['admin','manager','principal'])) {
                this.getStudentStrengthChartData();
            }

            this.showTourVideo = this.$cookie.get('hide_tour_video') ? false : true;

            this.getUserPreferencePreRequisite();
        },
        methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
            hasRole(role){
                return helper.hasRole(role);
            },
            hasAnyRole(roles){
                return helper.hasAnyRole(roles);
            },
            hasNotAnyRole(roles){
                return helper.hasNotAnyRole(roles);
            },
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            showArticle(article){
                this.showArticleUuid = article.uuid;
                this.showArticleModal = true;
            },
            logout(){
                helper.logout().then(() => {
                    this.$router.push('/login')
                })
            },
            getData(){
                let loader = this.$loading.show();
                axios.get('/api/dashboard')
                    .then(response => {
                        this.birthday_count= response.birthday_count;
                        this.anniversary_count= response.anniversary_count;
                        this.work_anniversary_count= response.work_anniversary_count;
                        if (helper.hasRole('librarian')) {
                            this.total_book_count = response.total_book_count;
                            this.pending_return_book_count = response.pending_return_book_count;
                            this.overdue_return_book_count = response.overdue_return_book_count;
                        }
                        this.articles = response.articles;
                        this.events = response.events;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            getStudentStrengthChartData(){
                let loader = this.$loading.show();
                axios.post('/api/dashboard/student/strength/chart', {strength_chart_type: this.strength_chart_type})
                    .then(response => {
                        this.chart.strength = response.strength;
                        this.total_strength = response.total;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            getUserPreferencePreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/user/preference/pre-requisite')
                    .then(response => {
                        this.color_themes = response.color_themes;
                        this.directions = response.directions;
                        this.sidebar = response.sidebar;
                        this.locales = response.locales;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
                },
            updatePreference(){
                let loader = this.$loading.show();
                this.preferenceForm.post('/api/user/preference')
                    .then(response => {
                        this.$store.dispatch('setConfig',{loaded: false});
                        toastr.success(response.message);

                        $('#theme').attr('href','/css/colors/'+this.preferenceForm.color_theme+'.css');

                        loader.hide();

                        if(this.user_preference.direction != this.preferenceForm.direction || this.user_preference.sidebar != this.preferenceForm.sidebar || this.user_preference.locale != this.preferenceForm.locale)
                            location.reload();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            hideTourVideo(){
                this.$cookie.set('hide_tour_video',helper.randomString(20), {expires: '30m'});
                this.showTourVideo = false;
            }
        },
        computed: {
        },
        filters: {
          momentDateTime(date) {
            return helper.formatDateTime(date);
          },
          moment(date) {
            return helper.formatDate(date);
          },
          momentTime(time) {
            return helper.formatTime(time);
          }
        },
        watch: {
            strength_chart_type(val){
                this.getStudentStrengthChartData();
            }
        }
    }
</script>
<style lang="scss">
    .shw-rside{
        width: 500px;
    }

    .product-intro {
        h2 {
            margin-top: 10px;
            margin-bottom: 20px;
            color: #202020;
            font-size: 36px;
            font-weight: 500;
            line-height: 1.12;
            letter-spacing: -1px;
            font-style: normal;

            .special {
                color: #e40b5b;
            }
        }
        h3 {
            color: #202020;
            font-size: 24px;
            margin-top: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            line-height: 1.3;
            letter-spacing: 0px;
            font-style: normal;
        }
        p {
            color: #202020;
            font-size: 18px;
            margin-top: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            line-height: 1.5;
            letter-spacing: 0px;
            font-style: normal;
        }
    }
</style>