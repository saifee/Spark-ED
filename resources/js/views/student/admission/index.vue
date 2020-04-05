<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('student.admission')}} 
                        <span class="card-subtitle d-none d-sm-inline" v-if="students.total">{{trans('general.total_result_found',{count : students.total, from: students.from, to: students.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <template v-if="hasNotAnyRole(['student','parent'])">
                            <button class="btn btn-info btn-sm" v-if="students.total && hasPermission('list-registration')" @click="$router.push('/student/registration')" v-tooltip="trans('general.add_new')"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline">{{trans('student.add_new_student')}}</span></button>
                            <button class="btn btn-info btn-sm" v-if="!showFilterPanel" @click="showFilterPanel = !showFilterPanel"><i class="fas fa-filter"></i> <span class="d-none d-sm-inline">{{trans('general.filter')}}</span></button>
                            <button class="btn btn-info btn-sm" v-if="!showColumnPanel" @click="showColumnPanel = !showColumnPanel"><i class="fas fa-columns"></i> {{trans('general.column')}}</button>
                            <sort-by :order-by-options="orderByOptions" :sort-by="filter.sort_by" :order="filter.order" @updateSortBy="value => {filter.sort_by = value}"  @updateOrder="value => {filter.order = value}"></sort-by>
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                    <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                                </button>
                                <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                    <button class="dropdown-item custom-dropdown" @click="print"><i class="fas fa-print"></i> {{trans('general.print')}}</button>
                                    <button class="dropdown-item custom-dropdown" @click="pdf"><i class="fas fa-file-pdf"></i> {{trans('general.generate_pdf')}}</button>
                                    <a class="dropdown-item custom-dropdown" :href="exportExcel()"><i class="fas fa-file-excel"></i> {{trans('general.generate_excel')}}</a>
                                    <button class="dropdown-item custom-dropdown" @click="$router.go(-1)"><i class="fas fa-undo"></i> {{trans('general.back')}}</button>
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
            <transition name="fade">
                <div class="card card-form" v-if="showFilterPanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('general.filter')}}
                            <small><a href="#" @click.prevent="showFilterFullPanel = !showFilterFullPanel">{{trans('general.toggle')}}</a></small>
                        </h4>
                        <div class="row">
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.first_name')}}</label>
                                    <input class="form-control" name="first_name" v-model="filter.first_name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.last_name')}}</label>
                                    <input class="form-control" name="last_name" v-model="filter.last_name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.first_guardian_name')}}</label>
                                    <input class="form-control" name="father_name" v-model="filter.father_name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.second_guardian_name')}}</label>
                                    <input class="form-control" name="mother_name" v-model="filter.mother_name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('academic.batch')}}</label>
                                    <v-select label="name" track-by="id" group-values="batches" group-label="course_group" :group-select="false" v-model="selected_batches" name="batch_id" id="batch_id" :options="batches" :placeholder="trans('academic.select_batch')" @select="onBatchSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onBatchRemove" :selected="selected_batches">
                                        <div class="multiselect__option" slot="afterList" v-if="!batches.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                        <template v-if="showFilterFullPanel">
                            <div class="col-12 col-sm-6">
                                <date-range-picker :start-date.sync="filter.date_of_birth_start_date" :end-date.sync="filter.date_of_birth_end_date" :label="trans('student.date_of_birth_between')"></date-range-picker>
                            </div>
                            <div class="col-12 col-sm-6">
                                <date-range-picker :start-date.sync="filter.date_of_admission_start_date" :end-date.sync="filter.date_of_admission_end_date" :label="trans('student.date_of_admission_between')"></date-range-picker>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.student_group')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_student_groups" name="student_group_id" id="student_group_id" :options="student_groups" :placeholder="trans('student.select_student_group')" @select="onStudentGroupSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onStudentGroupRemove" :selected="selected_student_groups">
                                        <div class="multiselect__option" slot="afterList" v-if="!student_groups.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('student.gender')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_genders" name="gender" id="gender" :options="genders" @select="onGenderSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onGenderRemove" :selected="selected_genders">
                                        <div class="multiselect__option" slot="afterList" v-if="!genders.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('misc.blood_group')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_blood_groups" name="blood_group_id" id="blood_group_id" :options="blood_groups" :placeholder="trans('misc.select_blood_group')" @select="onBloodGroupSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onBloodGroupRemove" :selected="selected_blood_groups">
                                        <div class="multiselect__option" slot="afterList" v-if="!blood_groups.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('misc.religion')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_religions" name="religion_id" id="religion_id" :options="religions" :placeholder="trans('misc.select_religion')" @select="onReligionSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onReligionRemove" :selected="selected_religions">
                                        <div class="multiselect__option" slot="afterList" v-if="!religions.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('misc.caste')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_castes" name="caste_id" id="caste_id" :options="castes" :placeholder="trans('misc.select_caste')" @select="onCasteSelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onCasteRemove" :selected="selected_castes">
                                        <div class="multiselect__option" slot="afterList" v-if="!castes.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2">
                                <div class="form-group">
                                    <label for="">{{trans('misc.category')}}</label>
                                    <v-select label="name" track-by="id" v-model="selected_categories" name="category_id" id="category_id" :options="categories" :placeholder="trans('misc.select_category')" @select="onCategorySelect" :multiple="true" :close-on-select="false" :clear-on-select="false" :hide-selected="true" @remove="onCategoryRemove" :selected="selected_categories">
                                        <div class="multiselect__option" slot="afterList" v-if="!categories.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                </div>
                            </div>
                        </template>
                        </div>
                        <template v-if="showFilterFullPanel">
                        <div class="card-footer text-right">
                            <button type="button" @click="showFilterPanel = false" class="btn btn-danger">{{trans('general.cancel')}}</button>
                            <button type="button" class="btn btn-info waves-effect waves-light" @click="getStudents">{{trans('general.filter')}}</button>
                        </div>
                        </template>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="students.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="select-all" v-if="hasPermission('edit-student')">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" value="1" v-model="selectAll" @change="toggleSelectAll">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </th>
                                    <th v-if="isColumnVisible('admission_number')">{{trans('student.admission_number_short')}}</th>
                                    <th v-if="isColumnVisible('roll_number')">{{trans('student.roll_number')}}</th>
                                    <th>{{trans('student.first_name')}}</th>
                                    <th v-if="isColumnVisible('middle_name')">{{trans('student.middle_name')}}</th>
                                    <th>{{trans('student.last_name')}}</th>
                                    <th v-if="isColumnVisible('gender')">{{trans('student.gender')}}</th>
                                    <th v-if="isColumnVisible('father_name')">{{trans('student.first_guardian_name')}}</th>
                                    <th v-if="isColumnVisible('mother_name')">{{trans('student.second_guardian_name')}}</th>
                                    <th v-if="isColumnVisible('date_of_birth')">{{trans('student.date_of_birth')}}</th>
                                    <th v-if="isColumnVisible('date_of_admission')">{{trans('student.date_of_admission')}}</th>
                                    <th v-if="isColumnVisible('date_of_promotion')">{{trans('student.date_of_promotion')}}</th>
                                    <th v-if="isColumnVisible('contact_number')">{{trans('student.contact_number')}}</th>
                                    <th>{{trans('academic.course')}}</th>
                                    <th>{{trans('academic.batch')}}</th>
                                    <th v-if="isColumnVisible('nationality')">{{trans('student.nationality')}}</th>
                                    <th v-if="isColumnVisible('blood_group')">{{trans('misc.blood_group')}}</th>
                                    <th v-if="isColumnVisible('religion')">{{trans('misc.religion')}}</th>
                                    <th v-if="isColumnVisible('caste')">{{trans('misc.caste')}}</th>
                                    <th v-if="isColumnVisible('category')">{{trans('misc.category')}}</th>
                                    <th v-if="isColumnVisible('unique_identification_number')">{{trans('student.unique_identification_number')}}</th>
                                    <th v-if="isColumnVisible('father_contact_number_1')">{{trans('student.first_guardian_contact_number_1')}}</th>
                                    <th v-if="isColumnVisible('mother_contact_number_1')">{{trans('student.mother_contact_number_1')}}</th>
                                    <th v-if="isColumnVisible('emergency_contact_name')">{{trans('student.emergency_contact_name')}}</th>
                                    <th v-if="isColumnVisible('emergency_contact_number')">{{trans('student.emergency_contact_number')}}</th>
                                    <th v-if="isColumnVisible('present_address')">{{trans('student.present_address')}}</th>
                                    <th v-if="isColumnVisible('permanent_address')">{{trans('student.permanent_address')}}</th>
                                    <th class="table-option" v-if="hasRole('parent')"></th>
                                    <th class="table-option">{{trans('general.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in students.data">
                                    <td class="select-all" v-if="hasPermission('edit-student')">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" :value="student.student.id" v-model="studentGroupForm.ids">
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </td>
                                    <td v-if="isColumnVisible('admission_number')" v-text="getAdmissionNumber(student)"></td>
                                    <td v-if="isColumnVisible('roll_number')" v-text="getRollNumber(student)"></td>
                                    <td v-text="student.student.first_name"></td>
                                    <td v-if="isColumnVisible('middle_name')" v-text="student.student.middle_name"></td>
                                    <td>{{student.student.last_name || ''}}</td>
                                    <td v-if="isColumnVisible('gender')">{{trans('list.'+student.student.gender)}}</td>
                                    <td v-if="isColumnVisible('father_name')" v-text="student.student.parent ? student.student.parent.first_guardian_name : ''"></td>
                                    <td v-if="isColumnVisible('mother_name')" v-text="student.student.parent ? student.student.parent.second_guardian_name : ''"></td>
                                    <td v-if="isColumnVisible('date_of_birth')">{{student.student.date_of_birth | moment}}</td>
                                    <td v-if="isColumnVisible('date_of_admission')">{{getAdmissionDate(student) | moment}}</td>
                                    <td v-if="isColumnVisible('date_of_promotion')">{{getPromotionDate(student)}}</td>
                                    <td v-if="isColumnVisible('contact_number')" v-text="student.student.contact_number"></td>
                                    <td v-text="getCourse(student)"></td>
                                    <td v-text="getBatch(student)"></td>
                                    <td v-if="isColumnVisible('nationality')" v-text="student.student.nationality"></td>
                                    <td v-if="isColumnVisible('blood_group')" v-text="student.student.blood_group ? student.student.blood_group.name : ''"></td>
                                    <td v-if="isColumnVisible('religion')" v-text="student.student.religion ? student.student.religion.name : ''"></td>
                                    <td v-if="isColumnVisible('caste')" v-text="student.student.caste ? student.student.caste.name : ''"></td>
                                    <td v-if="isColumnVisible('category')" v-text="student.student.category ? student.student.category.name : ''"></td>
                                    <td v-if="isColumnVisible('unique_identification_number')" v-text="student.student.unique_identification_number"></td>
                                    <td v-if="isColumnVisible('father_contact_number_1')" v-text="student.student.parent.first_guardian_contact_number_1"></td>
                                    <td v-if="isColumnVisible('mother_contact_number_1')" v-text="student.student.parent.mother_contact_number_1"></td>
                                    <td v-if="isColumnVisible('emergency_contact_name')" v-text="student.student.emergency_contact_name"></td>
                                    <td v-if="isColumnVisible('emergency_contact_number')" v-text="student.student.emergency_contact_number"></td>
                                    <td v-if="isColumnVisible('present_address')">
                                        {{student.student.present_address_line_1}}
                                        <span v-if="student.student.present_address_line_2">, {{student.student.present_address_line_2}}</span>
                                        <span v-if="student.student.present_city"><br /> {{student.student.present_city}}</span>
                                        <span v-if="student.student.present_state">, {{student.student.present_state}}</span>
                                        <span v-if="student.student.present_zipcode">, {{student.student.present_zipcode}}</span>
                                        <span v-if="student.student.present_country"><br /> {{student.student.present_country}}</span>
                                    </td>
                                    <td v-if="isColumnVisible('permanent_address')">
                                        <template v-if="student.student.same_as_present_address">{{trans('student.student.same_as_present_address')}}</template>
                                        <template v-else>
                                            {{student.student.permanent_address_line_1}}
                                            <span v-if="student.student.permanent_address_line_2">, {{student.student.permanent_address_line_2}}</span>
                                            <span v-if="student.student.permanent_city"><br /> {{student.student.permanent_city}}</span>
                                            <span v-if="student.student.permanent_state">, {{student.student.permanent_state}}</span>
                                            <span v-if="student.student.permanent_zipcode">, {{student.student.permanent_zipcode}}</span>
                                            <span v-if="student.student.permanent_country"><br /> {{student.student.permanent_country}}</span>
                                        </template>
                                    </td>
                                    <td class="table-option" v-if="hasRole('parent')">
                                        <button class="btn btn-info btn-sm" v-tooltip="trans('behaviour.messages')" @click="$router.push('/student/behaviour/'+student.batch_id+'/messages')">
                                            <i class="fas fa-comment"></i> {{trans('behaviour.messages')}}
                                        </button>
                                    </td>
                                    <td class="table-option">
                                        <div class="btn-group" v-if="hasNotAnyRole(['student','parent'])">
                                            <button class="btn btn-info btn-sm" v-tooltip="trans('student.student.view_student_detail')" @click="$router.push('/student/'+student.student.uuid)">
                                                <i class="fas fa-arrow-circle-right"></i> {{trans('general.view')}}
                                            </button>
                                            <template v-if="hasPermission('list-student-fee')">
                                                <button type="button" class="btn btn-sm btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item custom-dropdown-menu" v-tooltip="trans('student.student.view_student_fee')" @click="$router.push('/student/'+student.student.uuid+'/fee/'+getStudentRecordId(student))">
                                                        <i class="fas fa-file"></i> {{trans('finance.view_fee_allocation')}}
                                                    </button>
                                                    <button class="dropdown-item custom-dropdown-menu" v-tooltip="trans('student_wallet.view_student_wallet')" @click="$router.push('/student/'+student.student.uuid+'/wallet/'+getStudentRecordId(student))">
                                                        <i class="fas fa-wallet"></i> {{trans('student_wallet.view_student_wallet')}}
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        <div v-else>
                                            <button class="btn btn-info btn-sm" v-tooltip="trans('student.student.view_student_fee')" @click="$router.push('/student/'+student.student.uuid+'/fee/'+getStudentRecordId(student))">
                                                <i class="fas fa-arrow-circle-right"></i> {{trans('finance.view_fee_allocation')}}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!students.total" module="student" title="admission_module_title" description="admission_module_description" icon="list">
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="students" @updateRecords="getStudents"></pagination-record>
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
    </div>
</template>

<script>
    export default {
        components : {},
        data() {
            return {
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
                orderByOptions: [
                    {
                        value: 'first_name',
                        translation: i18n.student.first_name
                    },
                    {
                        value: 'last_name',
                        translation: i18n.student.last_name
                    },
                    {
                        value: 'father_name',
                        translation: i18n.student.first_guardian_name
                    },
                    {
                        value: 'mother_name',
                        translation: i18n.student.second_guardian_name
                    },
                    {
                        value: 'date_of_birth',
                        translation: i18n.student.date_of_birth
                    }
                ],
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
                        text: i18n.student.first_guardian_name,
                        value: 'father_name'
                    },
                    {
                        text: i18n.student.second_guardian_name,
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
                        text: i18n.student.first_guardian_contact_number_1,
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
                showFilterPanel: true,
                showFilterFullPanel: false,
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
            hasRole(role){
                return helper.hasRole(role);
            },
            getConfig(config){
                return helper.getConfig(config);
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
            print(){
                let loader = this.$loading.show();
                if (this.filter.columns.length > 6) {
                    toastr.error(i18n.student.print_max_column);
                    loader.hide();
                    return;
                }

                axios.post('/api/student/print',{filter: this.filter})
                    .then(response => {
                        let print = window.open("/print");
                        print.document.write(response);
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            pdf(){
                let loader = this.$loading.show();
                if (this.filter.columns.length > 6) {
                    toastr.error(i18n.student.print_max_column);
                    loader.hide();
                    return;
                }
                
                axios.post('/api/student/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            exportExcel(){
                this.filter.date_of_birth_start_date = helper.toDate(this.filter.date_of_birth_start_date);
                this.filter.date_of_birth_end_date = helper.toDate(this.filter.date_of_birth_end_date);
                this.filter.date_of_admission_start_date = helper.toDate(this.filter.date_of_admission_start_date);
                this.filter.date_of_admission_end_date = helper.toDate(this.filter.date_of_admission_end_date);

                let url = helper.getFilterURL(this.filter);
                return '/api/student?action=excel' + url + '&token=' + this.authToken;
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
            getAdmissionDate(student){
                return student.admission.date_of_admission;
            },
            getPromotionDate(student){
                let student_record = student.student;

                if (student.admission.date_of_admission == student_record.date_of_entry)
                    return '-';
                else
                    return helper.formatDate(student_record.date_of_entry);
            },
            getAdmissionNumber(student){
                return helper.getAdmissionNumber(student.admission);
            },
            getRollNumber(student){
                let student_record = student.student;

                return helper.getRollNumber(student_record);
            },
            getCourse(student){
                return student.batch.course.name;
            },
            getBatch(student){
                return student.batch.name;
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