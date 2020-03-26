<template>
    <div>
        <div class="page-title">
            <div class="fix-width fix-width-mobile">
                <h2>{{ trans('student.online_registration') }}</h2>
            </div>
        </div>

        <div class="fix-width fix-width-mobile p-t-80">
            <div class="page-body" v-html="getConfig('online_registration_header')"></div>
        </div>

        <div class="fix-width fix-width-mobile p-t-80">
            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submit" @keydown="registrationForm.errors.clear($event.target.name)">
                        <h2>Course Information</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">{{trans('academic.course')}}</label>
                                    <v-select label="name" v-model="selected_course" group-values="courses" group-label="course_group" :group-select="false" name="course_id" id="course_id" :options="courses" :placeholder="trans('academic.select_course')" @select="onCourseSelect" @close="registrationForm.errors.clear('course_id')" @remove="registrationForm.course_id = ''">
                                        <div class="multiselect__option" slot="afterList" v-if="!courses.length">
                                            {{trans('general.no_option_found')}}
                                        </div>
                                    </v-select>
                                    <span class="help-block" v-if="registrationForm.course_id && enable_registration_fee && registration_fee >= 0">{{trans('student.registration_fee')}} {{formatCurrency(registration_fee)}}</span>
                                    <show-error :form-name="registrationForm" prop-name="course_id"></show-error>
                                </div>
                            </div>
                        </div>

                        <h2>Candidate Information</h2>
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.first_name')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.first_name" name="first_name" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="first_name"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.middle_name')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.middle_name" name="middle_name" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="middle_name"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.last_name')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.last_name" name="last_name" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="last_name"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.gender')}}</label>
                                    <div class="radio radio-info p-l-0">
                                        <div class="form-check form-check-inline " v-for="gender in genders">
                                          <input class="form-check-input" type="radio" :value="gender.id" :id="gender.id" v-model="registrationForm.gender" :checked="registrationForm.gender == gender.id" name="gender" @click="registrationForm.errors.clear('gender')">
                                          <label class="form-check-label" :for="gender.id">{{trans('list.'+gender.id)}}</label>
                                        </div>
                                    </div>
                                    <show-error :form-name="registrationForm" prop-name="gender"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.date_of_birth')}}</label>
                                    <datepicker v-model="registrationForm.date_of_birth" :bootstrapStyling="true" @selected="registrationForm.errors.clear('date_of_birth')" :placeholder="trans('student.date_of_birth')" typeable></datepicker>
                                    <show-error :form-name="registrationForm" prop-name="date_of_birth"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.contact_number')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.contact_number" name="contact_number" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="contact_number"></show-error>
                                </div>
                            </div>
                        </div>
    
                        <h2>Parent Information</h2>
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.first_guardian_name')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.father_name" name="father_name" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="father_name"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.second_guardian_name')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.mother_name" name="mother_name" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="mother_name"></show-error>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.father_email')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.father_email" name="father_email" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="father_email"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.father_contact_number')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.father_contact_number_1" name="father_contact_number_1" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="father_contact_number_1"></show-error>
                                </div>
                            </div>
                        </div>

                        <h2>Contact Information</h2>
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.address_line_1')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.address_line_1" name="address_line_1" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="address_line_1"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.address_line_2')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.address_line_2" name="address_line_2" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="address_line_2"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.city')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.city" name="city" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="city"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.state')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.state" name="state" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="state"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.zipcode')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.zipcode" name="zipcode" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="zipcode"></show-error>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="">{{trans('student.country')}}</label>
                                    <input class="form-control" type="text" v-model="registrationForm.country" name="country" :placeholder="trans('student.contact_name')">
                                    <show-error :form-name="registrationForm" prop-name="country"></show-error>
                                </div>
                            </div>
                        </div>
                        <custom-field :fields="custom_fields" :customValues="custom_values" :clear="clearCustomField" :formErrors="customFieldFormErrors" @updateCustomValues="updateCustomValues"></custom-field>

                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-lg waves-effect waves-light m-t-10">{{trans('general.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
        },
        data(){
            return {
                courses: [],
                genders: [],
                course_details: [],
                registrationForm: new Form({
                    course_id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    email: '',
                    date_of_birth: '',
                    father_name: '',
                    mother_name: '',
                    contact_number: '',
                    father_contact_number_1: '',
                    father_email: '',
                    gender: '',
                    address_line_1: '',
                    address_line_2: '',
                    city: '',
                    state: '',
                    zipcode: '',
                    country: '',
                    custom_values: [],
                }),
                selected_course: null,
                custom_fields: [],
                custom_values: [],
                clearCustomField: false,
                customFieldFormErrors: {}
            }
        },
        mounted(){
            if (! this.getConfig('online_registration')) {
                this.$router.push('/dashboard');
            }

            let loader = this.$loading.show();
            axios.get('/api/frontend/online-registration/pre-requisite')
                .then(response => {
                    this.genders = response.genders;
                    this.courses = response.courses.courses;
                    this.course_details = response.courses.course_details;
                    this.custom_fields = response.custom_fields;
                    loader.hide();
                })
                .catch(error => {
                    loader.hide();
                    helper.showErrorMsg(error);
                })
        },
        methods: {
            getConfig(config) {
                return helper.getConfig(config)
            },
            updateCustomValues(value) {
                this.registrationForm.custom_values = value;
            },
            submit(){
                let loader = this.$loading.show();
                this.registrationForm.date_of_birth = helper.toDate(this.registrationForm.date_of_birth);
                this.registrationForm.post('/api/frontend/online-registration')
                    .then(response => {
                        toastr.success(response.message);
                        this.clearCustomField = true;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        this.customFieldFormErrors = error;
                        helper.showErrorMsg(error);
                    });
            },
            onCourseSelect(selectedOption){
                this.registrationForm.course_id = selectedOption.id;
                let course = this.course_details.find(o => o.course_id == selectedOption.id);
                this.enable_registration_fee = (course != 'undefined') ? course.enable_registration_fee : 0;
                this.registration_fee = (this.enable_registration_fee) ? course.registration_fee : 0
            },
            formatCurrency(amount){
                return helper.formatCurrency(amount);
            },
        },
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
    }
</script>

<style lang="scss">
    .contact-info-box {
        .comma:before {
            content: ", "
        }    
    }
</style>