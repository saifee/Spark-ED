<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.behaviour')}}
                        <span class="card-subtitle d-none d-sm-inline" v-if="students.total">{{trans('general.total_result_found',{count : students.total, from: students.from, to: students.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/student/behaviour/story')" v-tooltip="trans('behaviour.class_story')"><i class="fas fa-quote-right"></i> <span class="d-none d-sm-inline">{{trans('behaviour.class_story')}}</span></button>
                        <button class="btn btn-info btn-sm" @click="$router.push('/student/behaviour/messages')" v-tooltip="trans('behaviour.messages')"><i class="fas fa-comment"></i> <span class="d-none d-sm-inline">{{trans('behaviour.messages')}}</span></button>
                        <template v-if="hasNotAnyRole(['student','parent'])">
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                    <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                                </button>
                                <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                    <button class="dropdown-item custom-dropdown"><i class="fas fa-print"></i> {{trans('behaviour.view_reports')}}</button>
                                    <button class="dropdown-item custom-dropdown"><i class="fas fa-undo"></i> {{trans('behaviour.reset_bubbles')}}</button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item custom-dropdown" v-for="option in avatarSizeOptions" @click="avatarSize = option">
                                        {{option}} <span v-if="option == avatarSize" class="pull-right"><i class="fas fa-check"></i></span>
                                    </button>
                                </div>
                            </div>
                            <help-button @clicked="help_topic = 'admission'"></help-button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <transition name="fade">
                <div class="card card-form" v-if="showColumnPanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('general.column')}}</h4>
                        <div class="row">
                            <div class="col-12 col-sm-2" v-for="column in columns">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" :value="column.value" v-model="filter.columns">
                                    <span class="custom-control-label">{{column.text}}</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" @click="showColumnPanel = false" class="btn btn-danger">{{trans('general.cancel')}}</button>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="row mt-5" v-if="students.total">
                        <div class="col-2 text-center" v-for="student in students.data">
                          <v-badge
                            overlap
                            :content="student.student_behaviour_point ? student.student_behaviour_point.total : '0'"
                            :color="student.student_behaviour_point && student.student_behaviour_point.total > 0 ? 'success' : !student.student_behaviour_point || student.student_behaviour_point.total == 0 ? 'warning' : 'error'"
                          >
                            <v-avatar
                                @click="giveFeedbackToStudent(student)"
                                :size="avatarSize"
                            >
                                <v-img :src="getImage(student.student)"></v-img>
                            </v-avatar>
                          </v-badge>
                          <div>{{getStudentName(student.student)}}</div>
                        </div>
                    </div>
                    <module-info v-if="!students.total" module="behaviour" title="behaviour_module_title" description="behaviour_module_description" icon="list">
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="students" @updateRecords="getStudents" v-if="students.total > 10"></pagination-record>
                </div>
                <div class="m-t-10 card-body border-top p-4" v-if="studentGroupForm.ids.length && hasPermission('edit-student')">
                    <h4 class="card-title"></h4>
                    <form @submit.prevent="submit" @keydown="studentGroupForm.errors.clear($event.target.name)">
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="">{{trans('student.student_group')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_group" name="group_id" id="group_id" :options="student_groups" :placeholder="trans('student.select_student_group')" @select="onGroupSelect" @remove="studentGroupForm.student_group_id = ''">
                                        <div class="multiselect__option" slot="afterList" v-if="!student_groups.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                    <show-error :form-name="studentGroupForm" prop-name="group_id"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <div class="radio radio-success m-t-20">
                                        <input type="radio" value="attach" id="type_attach" v-model="studentGroupForm.action" :checked="studentGroupForm.action == 'attach'" name="action" @click="studentGroupForm.errors.clear('action')">
                                        <label for="type_attach">{{trans('general.add')}}</label>
                                    </div>
                                    <div class="radio radio-success">
                                        <input type="radio" value="detach" id="type_detach" v-model="studentGroupForm.action" :checked="studentGroupForm.action == 'detach'" name="action" @click="studentGroupForm.errors.clear('action')">
                                        <label for="type_detach">{{trans('general.remove')}}</label>
                                    </div>
                                    <show-error :form-name="studentGroupForm" prop-name="action"></show-error>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info waves-effect waves-light" key="group-action" v-confirm="{ok: confirmGroupAction()}">{{trans('general.save')}}</button>
                    </form>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
        <transition name="modal" v-if="showFeedbackModal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container modal-lg">
                        <div class="modal-header">
                            <slot name="header">
                                {{trans('behaviour.give_feedback_to_student')}}
                                <span class="float-right pointer" @click="showFeedbackModal = false">&times;</span>
                            </slot>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <feedback-form :batch-id="batchId" :student-record-id="studentRecordId" @completed="giveFeedbackToStudentComplete" @cancel="showFeedbackModal = false"></feedback-form>
                                <div class="clearfix"></div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import feedbackForm from './feedback-form'
    export default {
        components: {feedbackForm,},
        data() {
            return {
                batchId: null,
                studentRecordId: null,
                avatarSize: 70,
                avatarSizeOptions: [20, 30, 40, 50, 60, 70, 80, 90, 100],
                showFeedbackModal: false,
                students: {
                    total: 0,
                    data: []
                },
                selectAll: false,
                studentGroupForm: new Form({
                    ids: [],
                    student_group_id: '',
                    action: 'attach'
                }),
                student_groups: [],
                selected_group: null,
                filter: {
                    sort_by : 'first_name',
                    order: 'asc',
                    batch_id: [],
                    blood_group_id: [],
                    religion_id: [],
                    caste_id: [],
                    gender: [],
                    category_id: [],
                    student_group_id: [],
                    first_name: '',
                    last_name: '',
                    father_name: '',
                    mother_name: '',
                    date_of_admission_start_date: '',
                    date_of_birth_end_date: '',
                    date_of_admission_end_date: '',
                    date_of_birth_start_date: '',
                    columns: [
                        'father_name',
                        'date_of_admission',
                        'admission_number',
                        'contact_number'
                    ],
                    page_length: helper.getConfig('page_length')
                },
                columns: [
                    {
                        text: i18n.student.admission_number,
                        value: 'admission_number'
                    },
                    {
                        text: i18n.student.roll_number,
                        value: 'roll_number'
                    },
                    {
                        text: i18n.student.middle_name,
                        value: 'middle_name'
                    },
                    {
                        text: i18n.student.father_name,
                        value: 'father_name'
                    },
                    {
                        text: i18n.student.mother_name,
                        value: 'mother_name'
                    },
                    {
                        text: i18n.student.date_of_birth,
                        value: 'date_of_birth'
                    },
                    {
                        text: i18n.student.date_of_admission,
                        value: 'date_of_admission'
                    },
                    {
                        text: i18n.student.date_of_promotion,
                        value: 'date_of_promotion'
                    },
                    {
                        text: i18n.student.contact_number,
                        value: 'contact_number'
                    },
                    {
                        text: i18n.student.gender,
                        value: 'gender'
                    },
                    {
                        text: i18n.student.nationality,
                        value: 'nationality'
                    },
                    {
                        text: i18n.misc.blood_group,
                        value: 'blood_group'
                    },
                    {
                        text: i18n.misc.religion,
                        value: 'religion'
                    },
                    {
                        text: i18n.misc.caste,
                        value: 'caste'
                    },
                    {
                        text: i18n.misc.category,
                        value: 'category'
                    },
                    {
                        text: i18n.student.unique_identification_number,
                        value: 'unique_identification_number'
                    },
                    {
                        text: i18n.student.father_contact_number_1,
                        value: 'father_contact_number_1'
                    },
                    {
                        text: i18n.student.mother_contact_number_1,
                        value: 'mother_contact_number_1'
                    },
                    {
                        text: i18n.student.emergency_contact_name,
                        value: 'emergency_contact_name'
                    },
                    {
                        text: i18n.student.emergency_contact_number,
                        value: 'emergency_contact_number'
                    },
                    {
                        text: i18n.student.present_address,
                        value: 'present_address'
                    },
                    {
                        text: i18n.student.permanent_address,
                        value: 'permanent_address'
                    }
                ],
                batches: [],
                selected_batches: null,
                blood_groups: [],
                selected_blood_groups: null,
                castes: [],
                genders: [],
                selected_genders: null,
                selected_castes: null,
                religions: [],
                selected_religions: null,
                categories: [],
                selected_categories: null,
                showColumnPanel: false,
                selected_student_groups: null,
                help_topic: ''
            };
        },
        mounted(){
            if(!helper.hasPermission('list-student') && !helper.hasPermission('list-class-teacher-wise-student')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getStudents();
            helper.showDemoNotification(['student_admission']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            hasNotAnyRole(roles){
                return helper.hasNotAnyRole(roles);
            },
            getConfig(config){
                return helper.getConfig(config);
            },
            giveFeedbackToStudent(student){
                this.batchId = student.batch_id
                this.studentRecordId = student.id
                this.showFeedbackModal = true
            },
            giveFeedbackToStudentComplete(student){
                this.getStudents()
                this.showFeedbackModal = false
            },
            getStudents(page){
                let loader = this.$loading.show();
                this.filter.date_of_birth_start_date = helper.toDate(this.filter.date_of_birth_start_date);
                this.filter.date_of_birth_end_date = helper.toDate(this.filter.date_of_birth_end_date);
                this.filter.date_of_admission_start_date = helper.toDate(this.filter.date_of_admission_start_date);
                this.filter.date_of_admission_end_date = helper.toDate(this.filter.date_of_admission_end_date);

                if (typeof page !== 'number') {
                    page = 1;
                }
                this.selectAll = false;
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/student?page=' + page + url)
                    .then(response => {
                        this.students = response.student_records;
                        this.batches = response.filters.batches;
                        this.blood_groups = response.filters.blood_groups;
                        this.religions = response.filters.religions;
                        this.castes = response.filters.castes;
                        this.genders = response.filters.genders;
                        this.categories = response.filters.categories;
                        this.student_groups = response.filters.student_groups;
                        let ids = [];
                        this.students.data.forEach(student => {
                            ids.push(student.id);
                        })
                        this.selectAll = ids.every(elem => this.studentGroupForm.ids.indexOf(elem) > -1) ? 1 : 0;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            toggleSelectAll(){
                if(this.selectAll) {
                    this.students.data.forEach(student => {
                        if (this.studentGroupForm.ids.indexOf(student.id) < 0)
                            this.studentGroupForm.ids.push(student.id);
                    });
                } else {
                    this.students.data.forEach(student => {
                        let index = this.studentGroupForm.ids.indexOf(student.id);
                        if (index >= 0) {
                            this.studentGroupForm.ids.splice(index, 1);
                        }
                    });
                }
            },
            getStudentName(student){
                return helper.getStudentName(student);
            },
            getImage(student){
                if (!student.student_photo) {
                    return student.gender == 'female' ? '/images/female.png' : '/images/male.png';
                } else {
                    return '/'+student.student_photo;
                }
            },
            onBatchSelect(selectedOption){
                this.filter.batch_id.push(selectedOption.id);
            },
            onBatchRemove(removedOption){
                this.filter.batch_id.splice(this.filter.batch_id.indexOf(removedOption.id), 1);
            },
            onBloodGroupSelect(selectedOption){
                this.filter.blood_group_id.push(selectedOption.id);
            },
            onBloodGroupRemove(removedOption){
                this.filter.blood_group_id.splice(this.filter.blood_group_id.indexOf(removedOption.id), 1);
            },
            onReligionSelect(selectedOption){
                this.filter.religion_id.push(selectedOption.id);
            },
            onReligionRemove(removedOption){
                this.filter.religion_id.splice(this.filter.religion_id.indexOf(removedOption.id), 1);
            },
            onCasteSelect(selectedOption){
                this.filter.caste_id.push(selectedOption.id);
            },
            onCasteRemove(removedOption){
                this.filter.caste_id.splice(this.filter.caste_id.indexOf(removedOption.id), 1);
            },
            onGenderSelect(selectedOption){
                this.filter.gender.push(selectedOption.id);
            },
            onGenderRemove(removedOption){
                this.filter.gender.splice(this.filter.gender.indexOf(removedOption.id), 1);
            },
            onCategorySelect(selectedOption){
                this.filter.category_id.push(selectedOption.id);
            },
            onCategoryRemove(removedOption){
                this.filter.category_id.splice(this.filter.category_id.indexOf(removedOption.id), 1);
            },
            isColumnVisible(column){
                return (this.filter.columns.indexOf(column) > -1) ? true : false;
            },
            getStudentRecordId(student){
                let student_record = student.student;
                return student_record.id;
            },
            onStudentGroupSelect(selectedOption){
                this.filter.student_group_id.push(selectedOption.id);
            },
            onStudentGroupRemove(removedOption){
                this.filter.student_group_id.splice(this.filter.student_group_id.indexOf(removedOption.id), 1);
            },
            onGroupSelect(selectedOption){
                this.studentGroupForm.student_group_id = selectedOption.id;
            },
            confirmGroupAction(){
                return dialog => this.groupAction();
            },
            groupAction(){
                let loader = this.$loading.show();
                this.studentGroupForm.post('/api/student/action/group')
                    .then(response => {
                        toastr.success(response.message);
                        this.getStudents();
                        this.studentGroupForm.action = 'attach';
                        this.selected_group = null;
                        this.studentGroupForm.ids = [];
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            }
        },
        computed:{
            authToken(){
                return helper.getAuthToken();
            }
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getStudents();
            },
            'filter.order': function(val){
                this.getStudents();
            },
            'filter.page_length': function(val){
                this.getStudents();
            }
        }
    }
</script>
