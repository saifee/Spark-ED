<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
            		<h3 class="text-themecolor">{{trans('student.attendance')}}</h3>
            	</div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="attendanceForm.date_of_attendance" @click="$router.push('/student/attendance/absentee')" v-tooltip="trans('student.absentee')"><i class="fas fa-user-minus"></i> <span class="d-none d-sm-inline">{{trans('student.absentee')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        	<div class="card">
        		<div class="card-body p-4">
				    <form @submit.prevent="submit" @keydown="attendanceForm.errors.clear($event.target.name)">
				        <div class="row">
				            <div class="col-12 col-sm-3">
				                <div class="form-group">
				                    <label for="">{{trans('academic.batch')}}</label>
				                    <v-select :disabled="disable_filter" label="name" v-model="selected_batch" group-values="batches" group-label="course_group" :group-select="false" name="batch_id" id="batch_id" :options="batches" :placeholder="trans('academic.select_batch')" @select="onBatchSelect" @close="attendanceForm.errors.clear('batch_id')" @remove="onBatchRemove">
				                        <div class="multiselect__option" slot="afterList" v-if="!batches.length">
				                            {{trans('general.no_option_found')}}
				                        </div>
				                    </v-select>
				                    <show-error :form-name="attendanceForm" prop-name="batch_id"></show-error>
				                </div>
				            </div>
	                        <div class="col-12 col-sm-3" v-if="getConfig('made') === 'saudi'">
	                            <div class="form-group">
	                                <label for="">{{trans('student.attendance_method')}}</label>
	                                <select :disabled="disable_filter" v-model="attendanceForm.attendance_method" class="custom-select col-12" name="attendance_method">
	                                  <option value="" selected>{{trans('general.select_one')}}</option>
	                                  <option v-for="option in attendance_methods" v-bind:value="option.value">
	                                    {{ option.text }}
	                                  </option>
	                                </select>
	                                <show-error :form-name="attendanceForm" prop-name="attendance_method"></show-error>
	                            </div>
	                        </div>
	                        <div class="col-12 col-sm-3" v-if="attendanceForm.attendance_method == 'more_than_once'">
	                            <div class="form-group">
	                                <label for="">{{trans('student.attendance_session')}}</label>
	                                <select :disabled="disable_filter" v-model="attendanceForm.session" class="custom-select col-12" name="session">
	                                  <option value="" selected>{{trans('general.select_one')}}</option>
	                                  <option v-for="option in attendance_method_more_than_once_types" v-bind:value="option.value">
	                                    {{ option.text }}
	                                  </option>
	                                </select>
	                                <show-error :form-name="attendanceForm" prop-name="session"></show-error>
	                            </div>
	                        </div>
			                <div class="col-12 col-sm-3" v-if="attendanceForm.attendance_method == 'subject_wise'">
			                    <div class="form-group">
			                        <label for="">{{trans('academic.subject')}} </label>
	                                <select :disabled="disable_filter" v-model="attendanceForm.subject_id" class="custom-select col-12" name="subject_id">
	                                  <option value="" selected>{{trans('general.select_one')}}</option>
	                                  <option v-for="option in subjects" v-bind:value="option.id">
	                                    {{ option.name }}
	                                  </option>
	                                </select>
			                        <show-error :form-name="attendanceForm" prop-name="subject_id"></show-error>
			                    </div>
			                </div>
				            <div class="col-12 col-sm-3">
				                <div class="form-group">
				                    <label for="">{{trans('student.date_of_attendance')}}</label>
				                    <datepicker :disabled="disable_filter" v-model="attendanceForm.date_of_attendance" :bootstrapStyling="true" @selected="dateSelected" :disabledDates="disabled" :placeholder="trans('student.date_of_attendance')" typeable></datepicker>
				                    <show-error :form-name="attendanceForm" prop-name="date_of_attendance"></show-error>
				                </div>
				            </div>
				        </div>
			            <div class="text-right">
			                <button type="button" v-if="! disable_filter" @click="getStudent" class="btn btn-info waves-effect waves-light">{{trans('general.proceed')}}</button>
                            <button type="button" v-else @click="resetFilter" class="btn btn-danger m-r-10">{{trans('general.reset')}}</button>
			            </div>

				        <div class="row" v-if="attendanceForm.students.length">
				            <div class="col-12">
				            	<div class="table-responsive font-90pc p-2">
				            		<table class="table table-sm table-bordered attendance-table">
				            			<thead>
				            				<tr>
				            					<th>#</th>
				            					<th>{{trans('student.name')}}</th>
				            					<th :class="['date-cell', currentDate(index) ? 'current' : '']" v-for="index in days">{{index}}</th>
				            					<th></th>
				            				</tr>	
				            			</thead>
				            			<tbody>
				            				<tr v-for="(student,index) in attendanceForm.students">
				            					<td>{{index+1}}</td>
				            					<td style="font-size:120%;">{{student.name}} {{student.roll_number ? '('+student.roll_number+')' : ''}}</td>
				            					<td v-for="student_attendance in student.student_attendances" :class="[(isHoliday(student_attendance.day) || student_attendance.attendance == 'unavailable') ? 'disabled' : '']">
				            						<span class="marking-cell" v-if="student_attendance.attendance == 'unavailable'"></span>
				            						<span class="marking-cell" v-else-if="isHoliday(student_attendance.day)" v-tooltip="getHolidayName(student_attendance.day)"><i class="fas fa-hospital-symbol"></i></span>
				            						<span :class="['marking-cell', isEditable ? 'pointer' : '']" v-else-if="currentDate(student_attendance.day)" @click="toggleAttendance(student)">
				            							<i class="fas fa-check text-success" v-if="student.attendance == 'present'"></i>
				            							<i class="fas fa-history text-info" v-if="student.attendance == 'late'"></i>
				            							<i class="fas fa-coffee text-warning" v-if="student.attendance == 'half_day'"></i>
				            							<i class="fas fa-times text-danger" v-if="student.attendance == 'unmarked' || student.attendance == 'absent'"></i>
				            						</span>
				            						<span class="marking-cell" v-else>
				            							<i class="fas fa-check text-success" v-if="student_attendance.attendance == 'present'"></i>
				            							<i class="fas fa-history text-info" v-if="student_attendance.attendance == 'late'"></i>
				            							<i class="fas fa-coffee text-warning" v-if="student_attendance.attendance == 'half_day'"></i>
				            							<i class="fas fa-times text-danger" v-if="student_attendance.attendance == 'absent'"></i>
				            						</span>
				            					</td>
				            					<td><span class="marking-cell font-weight-bold">{{getTotalAttendanceOfStudent(student.id)}}</span></td>
				            				</tr>
				            			</tbody>
				            			<thead>
				            				<tr>
				            					<th></th>
				            					<th>{{trans('general.total')+': '+attendanceForm.students.length}}</th>
				            					<th class="date-cell" v-for="index in days">
				            						<span v-if="!isHoliday(index)">{{getTotalAttendanceOfDate(index)}}</span>
				            					</th>
				            					<th>
				            					</th>
				            				</tr>	
				            			</thead>
				            		</table>
				            	</div>
				            </div>
				        </div>
				        <div class="row mt-2" v-if="!attendanceForm.students.length && disable_filter">
							<div class="col-12">
								<p class="alert alert-danger">{{trans('general.no_data_found', {data: trans('student.student')})}}</p>
							</div>
				        </div>
				        <div class="form-group" v-if="attendanceForm.students.length && isEditable">
				        	<button type="submit" class="btn btn-success pull-right">{{trans('general.save')}}</button>
				        	<button type="button" class="btn btn-info pull-right m-r-10" v-if="isEditable && attendance && ! attendance.is_default" key="make-attendance-default" v-tooltip="trans('student.attendance_default_description')" v-confirm="{ok: confirmDefault()}">{{trans('student.attendance_default')}}</button>
				        	<button type="button" class="btn btn-danger pull-right m-r-10" v-if="isEditable && isDeletable" key="delete-attendance" v-confirm="{ok: confirmDelete()}">{{trans('general.delete')}}</button>
				        	<button type="button" @click="markAllPresent" class="btn btn-info btn-sm">{{trans('student.attendance_mark_all_present')}}</button>
				        	<button type="button" @click="markAllAbsent" class="btn btn-info btn-sm">{{trans('student.attendance_mark_all_absent')}}</button>
				        </div>
				    </form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		components: {},
		data(){
			return {
				attendanceForm: new Form({
					batch_id: '',
					attendance_method: '',
					subject_id: '',
					session: '',
					date_of_attendance: '',
					students: []
				},false),
                disable_filter: false,
				class_teachers: [],
				previous_date: '',
				holidays: [],
				holiday_lists: [],
				attendance: {},
				attendances: [],
				disabled: {
					dates:[]
				},
				days: 0,
				subjects: [],
				batches: [],
				selected_batch: null,
				selected_batch_detail: {},
				student_records: [],
				batch_with_subjects: [],
				attendance_methods: [],
				attendance_method_more_than_once_types: []
			}
		},
		mounted(){
			if(!helper.hasPermission('list-student-attendance')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
			}

			this.attendanceForm.date_of_attendance = moment().format('YYYY-MM-DD');
			this.previous_date = this.attendanceForm.date_of_attendance;
			this.getPreRequisite();
            helper.showDemoNotification(['student_attendance']);
		},
		methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
			hasPermission(permission){
				return helper.hasPermission(permission);
			},
            resetFilter(){
            	this.attendanceForm.students = [];
                this.disable_filter = false;
            },
			getPreRequisite(){
				let loader = this.$loading.show();
				axios.get('/api/student/attendance/pre-requisite')
					.then(response => {
						this.attendance_methods = response.attendance_methods;
						this.attendance_method_more_than_once_types = response.attendance_method_more_than_once_types;
						this.batches = response.batches;
						this.batch_with_subjects = response.batch_with_subjects;
						this.holidays = response.holidays;
						this.holiday_lists = response.holiday_lists;
		                response.holidays.forEach(date => {
		                    this.disabled.dates.push(new Date(date));
		                });
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			dateSelected(){
			},
			getStudent(){
				this.disable_filter = true;
				let loader = this.$loading.show();
				axios.post('/api/student/attendance/fetch', {
					batch_id: this.attendanceForm.batch_id, 
					date_of_attendance: helper.toDate(this.attendanceForm.date_of_attendance),
					subject_id: this.attendanceForm.subject_id,
					session: this.attendanceForm.session,
					attendance_method: this.attendanceForm.attendance_method
				})
					.then(response => {
						this.student_records = response.student_records;
						this.class_teachers = response.class_teachers;
						this.selected_batch_detail = response.batch;
						this.attendance = response.attendance;
						this.attendances = response.attendances;
						this.loadData();
						loader.hide();
					})
					.catch(error => {
						this.disable_filter = false;
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			loadData(){
				this.attendanceForm.students = [];
				this.student_records.forEach(student_record => {

					let student_attendances = [];
					let current_date_attendance = '';
					for(let i = 1; i <= this.days; i++) {
						let daily_attendance = '';
						let date = moment(this.attendanceForm.date_of_attendance).format('YYYY-MM')+'-'+helper.formatWithPadding(i,2);

						let student_attendance = this.attendances.find(o => o.date_of_attendance == date);
						if (typeof student_attendance == 'object') {
							if(student_attendance.attendance.data && student_attendance.attendance.data.findIndex(o => o.id == student_record.id) >= 0)
								daily_attendance = 'absent';
							else if(student_attendance.attendance.late && student_attendance.attendance.late.findIndex(o => o.id == student_record.id) >= 0)
								daily_attendance = 'late';
							else if(student_attendance.attendance.half_day && student_attendance.attendance.half_day.findIndex(o => o.id == student_record.id) >= 0)
								daily_attendance = 'half_day';
							else if (this.holidays.indexOf(date) >= 0)
								daily_attendance = 'holiday';
							else
								daily_attendance = 'present';
						} else {
							if(this.holidays.indexOf(date) >= 0)
								daily_attendance = 'holiday';
							else
								daily_attendance = 'unmarked';
						}

						if (date < student_record.date_of_entry || (student_record.date_of_exit && date > student_record.date_of_exit))
							daily_attendance = 'unavailable';

						if (date == moment(this.attendanceForm.date_of_attendance).format('YYYY-MM-DD'))
							current_date_attendance = daily_attendance;

						student_attendances.push({
							day: i,
							attendance: daily_attendance
						})
					}

					this.attendanceForm.students.push({
						id: student_record.id,
						name: this.getStudentName(student_record.student),
						date_of_birth: student_record.student.date_of_birth,
						contact_number: student_record.student.contact_number,
						admission_number: helper.getAdmissionNumber(student_record.admission),
						father_name: student_record.student.parent.first_guardian_name,
						roll_number: this.getRollNumber(student_record),
						student_attendances: student_attendances,
						attendance: current_date_attendance
					})
				});

				this.attendanceForm.students.sort(function(a, b) {
				  var nameA = a.name.toUpperCase();
				  var nameB = b.name.toUpperCase();
				  if (nameA < nameB) {
				    return -1;
				  }
				  if (nameA > nameB) {
				    return 1;
				  }
				  return 0;
				});
			},
            getStudentName(student){
                return helper.getStudentName(student);
            },
            getRollNumber(student_record){
            	return helper.getRollNumber(student_record);
            },
			onBatchSelect(selectedOption){
				let loader = this.$loading.show();
				this.attendanceForm.batch_id = selectedOption.id;
                let batch = this.batch_with_subjects.find(o => o.id == this.attendanceForm.batch_id);

                if (typeof batch == 'undefined') {
                	return;
                }

				this.attendanceForm.attendance_method = batch.options && batch.options.default_attendance_method ? batch.options.default_attendance_method : 'once';

				this.attendanceForm.subject_id = '';
                this.subjects = [];

                batch.subjects.forEach(subject => {
                	this.subjects.push({
                		id: subject.id,
                		name: subject.name+' ('+subject.code+')'
                	});
                });

				// this.getStudent();
				loader.hide();
			},
			onBatchRemove(removedOption){
				this.attendanceForm.batch_id = '';
				this.attendanceForm.students = [];
				this.student_records = [];
			},
			currentDate(date){
				if (date == moment(this.attendanceForm.date_of_attendance).format('D'))
					return true;

				return false;
			},
			isHoliday(date){
				let monthDate = moment(this.attendanceForm.date_of_attendance).format("YYYY-MM");
				date = helper.formatWithPadding(date, 2);
				date = monthDate+'-'+date;
				if(this.holidays.indexOf(date) >= 0)
					return true;

				return false;
			},
			getHolidayName(date){
				let monthDate = moment(this.attendanceForm.date_of_attendance).format("YYYY-MM");
				date = helper.formatWithPadding(date, 2);
				date = monthDate+'-'+date;
				let holiday = this.holiday_lists.find(o => o.date == date);
				return (typeof holiday != 'undefined') ? holiday.description : '';
			},
			toggleAttendance(student){
				if (! this.isEditable) {
					return;
				}
				
				let options = ['present','absent'];
				if (this.attendanceForm.attendance_method == 'once') {
				}
				let record = this.attendanceForm.students.find(o => o.id == student.id);
				let index = options.indexOf(record.attendance);
				index = ++index%options.length; 
				record.attendance = options[index];
			},
			markAllPresent(){
				this.attendanceForm.students.forEach(student => {
					if (student.attendance != 'unavailable') {
						student.attendance = 'present';
					}
				})
			},
			markAllAbsent(){
				this.attendanceForm.students.forEach(student => {
					if (student.attendance != 'unavailable') {
						student.attendance = 'absent';
					}
				})
			},
			submit(){
				let loader = this.$loading.show();
				this.attendanceForm.date_of_attendance = helper.toDate(this.attendanceForm.date_of_attendance);
				this.attendanceForm.post('/api/student/attendance')
					.then(response => {
						this.getStudent();
						toastr.success(response.message);
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			getTotalAttendanceOfDate(day){
				let monthDate = moment(this.attendanceForm.date_of_attendance).format("YYYY-MM");
				let date = helper.formatWithPadding(day, 2);
				date = monthDate+'-'+date;

				let total = 0;
				this.attendanceForm.students.forEach(student => {
					if (date != moment(this.attendanceForm.date_of_attendance).format("YYYY-MM-DD")) {
						let attendance = student.student_attendances.find(o => o.day == day);
						total += (typeof attendance != 'undefined' && (attendance.attendance == 'present' || attendance.attendance == 'late')) ? 1 : 0;
					} else {
						total += student.attendance == 'present' || student.attendance == 'late' ? 1 : 0;

					}
				})

				if (date > moment().format('YYYY-MM-DD') && !total)
					return;

				return total;
			},
			getTotalAttendanceOfStudent(id){
				let student = this.attendanceForm.students.find(o => o.id == id);

				let total = 0;
				student.student_attendances.forEach(attendance => {
					total += (typeof attendance != 'undefined' && (attendance.attendance == 'present' || attendance.attendance == 'late')) ? 1 : 0;
				})
				return total;
			},
            confirmDelete(){
                return dialog => this.deleteAttendance();
            },
            deleteAttendance(){
                let loader = this.$loading.show();
                axios.post('/api/student/attendance/delete', {
                		batch_id: this.attendanceForm.batch_id,
                		date_of_attendance: this.attendanceForm.date_of_attendance,
                		subject_id: this.attendanceForm.subject_id,
                		session: this.attendanceForm.session
                	})
                    .then(response => {
                        toastr.success(response.message);
                        this.getStudent();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            confirmDefault(){
                return dialog => this.defaultAttendance();
            },
            defaultAttendance(){
                let loader = this.$loading.show();
                axios.post('/api/student/attendance/default', {
                		batch_id: this.attendanceForm.batch_id,
                		date_of_attendance: this.attendanceForm.date_of_attendance,
                		subject_id: this.attendanceForm.subject_id,
                		session: this.attendanceForm.session
                	})
                    .then(response => {
                        toastr.success(response.message);
                        this.getStudent();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
		},
		computed: {
			isEditable(){

				if(helper.hasPermission('mark-student-attendance')) {} 
				else if (helper.hasPermission('mark-class-teacher-wise-student-attendance') && helper.amIClassTeacherOnDate(this.class_teachers, helper.toDate(this.attendanceForm.date_of_attendance))) {} 
				else {
					return false;
				}
				
				let date = moment(this.attendanceForm.date_of_attendance).format('YYYY-MM-DD');

				if (date == moment().format('YYYY-MM-DD'))
					return true;
				else if (date < moment().format('YYYY-MM-DD') && helper.getConfig('allow_to_modify_student_attendance') && helper.getDateDiff(moment().format('YYYY-MM-DD'), date) < helper.getConfig('days_allowed_to_modify_student_attendance'))
						return true;
				else if (date > moment().format('YYYY-MM-DD') && helper.getConfig('allow_to_mark_student_advance_attendance') && helper.getDateDiff(date,moment().format('YYYY-MM-DD')) < helper.getConfig('days_allowed_to_mark_student_advance_attendance'))
						return true;

				return false;
			},
			isDeletable(){
				let attendance = this.attendances.find(o => o.batch_id == this.attendanceForm.batch_id && o.date_of_attendance == helper.toDate(this.attendanceForm.date_of_attendance))

				if (typeof attendance == 'undefined')
					return false;

				return true;
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
        	'attendanceForm.date_of_attendance': function(val){
				this.days = moment(val, "YYYY-MM").daysInMonth();

				// if (moment(val).format('M') != moment(this.previous_date).format('M'))
				// 	this.getStudent();
				// else
				// 	this.loadData();

				this.previous_date = moment(val).format('YYYY-MM-DD');
        	}
        }
	}
</script>

<style>
	.disabled{
		background-color:#f1f2f3;
	}
	.current {
		font-weight: 500;
		font-size: 120%;
	}
	.attendance-table tr th.date-cell{
		text-align: center;
		min-width: 20px;
		max-width: 20px;
	}
	.attendance-table tr td span.marking-cell {
		display: block;
		width: 100%;
		height: 100%;
		text-align: center;
	}
</style>