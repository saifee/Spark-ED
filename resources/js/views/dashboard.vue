<template>
    <div>
        <div class="page-titles">
            <div class="row grey lighten-3">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('general.home')}}</h3>
                </div>
                <div class="col-12 col-sm-6" v-if="getConfig('made') === 'saudi'">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm " @click="drawer = !drawer" v-tooltip="trans('user.user_preference')"><i class="fas fa-cog"></i></button>
                        <button class="btn btn-danger btn-sm" @click.prevent="logout"><i class="fas fa-power-off"></i> <span class="d-none d-sm-inline">{{trans('auth.logout')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <draggable
                v-if="getConfig('made') === 'saudi'"
                class="row grey lighten-3"
            >
                <v-col
                    cols="12"
                    lg="3"
                >
                    <v-sheet
                        color="white"
                        outlined
                        rounded
                        class="elevation-1"
                    >
                        <v-list-item class="grow" to="/student/card-view">
                            <v-list-item-avatar
                                color="green lighten-5"
                                size="95"
                            >
                                <v-icon
                                    x-large
                                    color="green"
                                >
                                    groups
                                </v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-subtitle>Students</v-list-item-subtitle>
                                <v-list-item-title v-if="topBoxDataX">
                                    {{ topBoxDataX.total_students }}
                                </v-list-item-title>
                                <v-skeleton-loader
                                    v-else
                                    type="heading"
                                />
                            </v-list-item-content>
                        </v-list-item>
                    </v-sheet>
                </v-col>
                <v-col
                    cols="12"
                    lg="3"
                >
                    <v-sheet
                        color="white"
                        outlined
                        rounded
                        class="elevation-1"
                    >
                        <v-list-item class="grow" :to="{name: 'employeeCardView'}">
                            <v-list-item-avatar
                                color="blue lighten-5"
                                size="95"
                            >
                                <v-icon
                                    x-large
                                    color="blue"
                                >
                                    people
                                </v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-subtitle>Employees</v-list-item-subtitle>
                                <v-list-item-title v-if="topBoxDataX">
                                    {{ topBoxDataX.total_employee }}
                                </v-list-item-title>
                                <v-skeleton-loader
                                    v-else
                                    type="heading"
                                />
                            </v-list-item-content>
                        </v-list-item>
                    </v-sheet>
                </v-col>
                <v-col
                    cols="12"
                    lg="3"
                >
                    <v-sheet
                        color="white"
                        outlined
                        rounded
                        class="elevation-1"
                    >
                        <v-list-item class="grow" to="/finance/report">
                            <v-list-item-avatar
                                color="orange lighten-5"
                                size="95"
                            >
                                <v-icon
                                    x-large
                                    color="orange"
                                >
                                    score
                                </v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-subtitle>Fees</v-list-item-subtitle>
                                <v-list-item-title v-if="topBoxDataX">
                                    {{ formatCurrency(topBoxDataX.fee_summary.footer.grand_total) }}
                                </v-list-item-title>
                                <v-skeleton-loader
                                    v-else
                                    type="heading"
                                />
                            </v-list-item-content>
                        </v-list-item>
                    </v-sheet>
                </v-col>
                <v-col
                    cols="12"
                    lg="3"
                >
                    <v-sheet
                        color="white"
                        outlined
                        rounded
                        class="elevation-1"
                    >
                        <v-list-item class="grow" :to="{name: 'employeePayroll'}">
                            <v-list-item-avatar
                                color="red lighten-5"
                                size="95"
                            >
                                <v-icon
                                    x-large
                                    color="red"
                                >
                                    monetization_on
                                </v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-subtitle>Salary</v-list-item-subtitle>
                                <v-list-item-title v-if="topBoxDataX && topBoxDataX.total_salary">
                                    {{ formatCurrency(topBoxDataX.total_salary) }}
                                </v-list-item-title>
                                <v-skeleton-loader
                                    v-else
                                    type="heading"
                                />
                            </v-list-item-content>
                        </v-list-item>
                    </v-sheet>
                </v-col>
                            <template v-if="getConfig('made') !== 'saudi'">
                                <template v-if="hasPermission('ambassador-view')">
                                  <v-col
                                      cols="12"
                                      lg="6"
                                  >
                                    <v-card class="mb-4">
                                        <v-card-text>
                                            <v-tabs>
                                                <v-tab>Comparison</v-tab>
                                                <v-tab>Planning</v-tab>
                                                <v-tab-item>
                                                    <v-simple-table>
                                                        <template v-slot:default>
                                                            <thead>
                                                                <tr>
                                                                    <th />
                                                                    <th
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_tab${i}`"
                                                                    >
                                                                        <b>{{ academic_session.name }}</b>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><b>Total Students</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? academic_sessions_detail[academic_session.id].total_students : '—' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Fee Total/Sum</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? formatCurrency(academic_sessions_detail[academic_session.id].fee_summary.footer.grand_total) : '—' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Fee Paid Sum</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? formatCurrency(academic_sessions_detail[academic_session.id].fee_summary.footer.grand_paid) : '—' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Remaining Fee</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? formatCurrency(academic_sessions_detail[academic_session.id].fee_summary.footer.grand_total - academic_sessions_detail[academic_session.id].fee_summary.footer.grand_paid) : '—' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Total Employee</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? academic_sessions_detail[academic_session.id].total_employee : '—' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><b>Total Salary</b></td>
                                                                    <td
                                                                        v-for="(academic_session, i) in academic_sessions"
                                                                        :key="`academic_session_content${i}`"
                                                                    >
                                                                        {{ academic_sessions_detail[academic_session.id] ? formatCurrency(academic_sessions_detail[academic_session.id].total_salary) : '—' }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </template>
                                                    </v-simple-table>
                                                </v-tab-item>
                                                <v-tab-item>
                                                    <notes :ambassador="true" />
                                                </v-tab-item>
                                            </v-tabs>
                                        </v-card-text>
                                    </v-card>
                                  </v-col>
                                </template>
                            </template>

                        <template v-if="getConfig('made') === 'saudi'">
                            <template v-if="hasAnyRole(['admin','manager','principal'])">
                        <v-col
                            cols="12"
                            lg="6"
                        >
                          <v-card to="/student/attendance">
                              <v-card-text>
                                <h4 class="card-title">{{trans('student.total_strength', {total: total_strength})}}
                                    <span class="pull-right">
                                        <button v-if="strength_chart_type == 'batch'" class="btn btn-sm btn-info" @click="strength_chart_type = 'course'">{{trans('academic.course_wise')}}</button>
                                        <button v-if="strength_chart_type == 'course'" class="btn btn-sm btn-info" @click="strength_chart_type = 'batch'">{{trans('academic.batch_wise')}}</button>
                                    </span>
                                </h4>
                                <bar-chart :chart="chart.strength"></bar-chart>
                              </v-card-text>
                          </v-card>
                        </v-col>
                            </template>
                <div class="col-12 col-md-6">
                    <v-card to="/calendar/event">
                        <v-card-text>
                            <calendar></calendar>
                        </v-card-text>
                    </v-card>
                </div>
                <template v-if="hasNotAnyRole(['student','parent'])">
                <div class="col-12 col-md-4">
                    <div class="card widget" v-if="hasNotAnyRole(['student','parent'])">
                        <div class="card-body">
                            <div class="row border-bottom">
                                <div class="col p-4 b-r">
                                    <h2 class="font-light">{{birthday_count}} <span class="float-right"><i class="fas text-themecolor fa-birthday-cake"></i></span></h2>
                                    <h5>{{trans('general.birthday')}}</h5></div>
                                <div class="col p-4 b-r">
                                    <h2 class="font-light">{{anniversary_count}} <span class="float-right"><i class="fas text-themecolor fa-heartbeat"></i></span></h2>
                                    <h5>{{trans('general.anniversary')}}</h5></div>
                                <div class="col p-4 mr-4">
                                    <h2 class="font-light">{{work_anniversary_count}} <span class="float-right"><i class="fas text-themecolor fa-gift"></i></span></h2>
                                    <h5>{{trans('general.work_anniversary')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </template>
                <template v-if="hasAnyRole(['admin','librarian'])">
                <div class="col-12 col-md-4">
                    <div class="card widget" v-if="hasAnyRole(['admin','librarian'])">
                        <div class="card-body">
                            <div class="row border-bottom">
                                <div class="col p-4 b-r">
                                    <h2 class="font-light">{{total_book_count}} <span class="float-right"><i class="fas text-themecolor fa-book"></i></span></h2>
                                    <h5>{{trans('library.total_books')}}</h5></div>
                                <div class="col p-4 b-r">
                                    <h2 class="font-light">{{pending_return_book_count}} <span class="float-right"><i class="fas text-themecolor fa-book-open"></i></span></h2>
                                    <h5>{{trans('library.pending_return')}}</h5></div>
                                <div class="col p-4 mr-4">
                                    <h2 class="font-light">{{overdue_return_book_count}} <span class="float-right"><i class="fas text-themecolor fa-swatchbook"></i></span></h2>
                                    <h5>{{trans('library.overdue_return')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </template>
                <div class="col-12 col-md-4">
                    <template v-if="hasPermission('access-todo')">
                    <v-card to="/utility/todo" :class="['card widget', hasAnyRole(['student','parent']) ? 'm-t-20' : '']">
                        <v-card-text>
                            <div class="row border-bottom">
                                <div class="col-12 p-4">
                                    <h4 class="card-title mb-3">{{trans('utility.todo')}}</h4>
                                    <todo-widget></todo-widget>
                                </div>
                            </div>
                        </v-card-text>
                    </v-card>
                    </template>
                </div>
                <div class="col-12 col-md-4">
                    <events-list v-if="events.length && hasPermission('list-event')" :events="events" class="frontend-widget" body-class="row-like-margin border-bottom p-4" view-more-link="/calendar/event"></events-list>
                </div>
                <div class="col-12 col-md-4">
                    <articles-list v-if="articles.length && hasPermission('list-article')" :articles="articles" class="frontend-widget" body-class="row-like-margin border-bottom p-4" view-more-link="/post/feed"></articles-list>
                </div>
                </template>
            </draggable>
        </div>
        <template v-if="getConfig('made') === 'saudi'">
            <v-navigation-drawer
                v-model="drawer"
                absolute
                temporary
                right
                width="400"
            >
                <v-card class="elevation-0">
                    <v-card-title>
                        {{ trans('user.user_preference') }}
                    </v-card-title>
                    <form
                        @submit.prevent="updatePreference"
                        @keydown="preferenceForm.errors.clear($event.target.name)"
                    >
                        <v-card-text>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{ trans('configuration.color_theme') }}</label>
                                    <select
                                        v-model="preferenceForm.color_theme"
                                        class="custom-select col-12"
                                    >
                                        <option
                                            v-for="option in color_themes"
                                            :value="option.value"
                                        >
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <show-error
                                        :form-name="preferenceForm"
                                        prop-name="color_theme"
                                    />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{ trans('configuration.direction') }}</label>
                                    <select
                                        v-model="preferenceForm.direction"
                                        class="custom-select col-12"
                                    >
                                        <option
                                            v-for="option in directions"
                                            :value="option.value"
                                        >
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <show-error
                                        :form-name="preferenceForm"
                                        prop-name="direction"
                                    />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{ trans('configuration.sidebar') }}</label>
                                    <select
                                        v-model="preferenceForm.sidebar"
                                        class="custom-select col-12"
                                    >
                                        <option
                                            v-for="option in sidebar"
                                            :value="option.value"
                                        >
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <show-error
                                        :form-name="preferenceForm"
                                        prop-name="sidebar"
                                    />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="">{{ trans('configuration.locale') }}</label>
                                    <select
                                        v-model="preferenceForm.locale"
                                        class="custom-select col-12"
                                    >
                                        <option
                                            v-for="option in locales"
                                            :value="option.value"
                                        >
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <show-error
                                        :form-name="preferenceForm"
                                        prop-name="sidebar"
                                    />
                                </div>
                            </div>
                        </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                color="red"
                                dark
                                @click="drawer = !drawer"
                            >
                                {{ trans('general.close') }}
                            </v-btn>
                            <v-btn
                                type="submit"
                                color="primary"
                                @click="drawer = !drawer"
                            >
                                {{ trans('general.save') }}
                            </v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-navigation-drawer>
        </template>
    </div>
</template>

<script>
    import Notes from '@views/resource/notes/index'
    import EventsList from '@js/widgets/events-list'
    import ArticlesList from '@js/widgets/articles-list'
    import barChart from './chart/bar-chart'
    import calendar from './calendar/calendar'
    import todoWidget from './utility/todo/widget'

    export default {
        components: {
            Notes,
            EventsList,
            ArticlesList,
            barChart,
            calendar,
            todoWidget
        },
        data() {
            return {
                drawer: false,
                academic_sessions: [],
                academic_sessions_detail: [],
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
        computed: {
          topBoxDataX() {
            return (Object.keys(this.academic_sessions_detail).length === 0 && this.academic_sessions_detail.constructor === Object) ? {} : this.academic_sessions_detail[helper.getDefaultAcademicSession().id]
          },
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
            formatCurrency(price){
                return helper.formatCurrency(price);
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
                        this.academic_sessions= response.ambassador_data.academic_sessions;
                        this.academic_sessions_detail= response.ambassador_data.academic_sessions_detail;
                        this.birthday_count= response.birthday_count;
                        this.anniversary_count= response.anniversary_count;
                        this.work_anniversary_count= response.work_anniversary_count;
                        if (helper.hasAnyRole(['admin','librarian'])) {
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