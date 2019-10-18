<template>
	<div v-if="student.id">
	    <form @submit.prevent="submit" @keydown="terminationForm.errors.clear($event.target.name)" v-if="eligibleForTermination">
	        <div class="row">
	            <div class="col-12 col-sm-4">
	                <div class="form-group">
	                    <label for="">{{trans('student.date_of_termination')}}</label>
	                    <datepicker v-model="terminationForm.date_of_termination" :bootstrapStyling="true" @selected="terminationForm.errors.clear('date_of_termination')" :placeholder="trans('student.date_of_termination')"></datepicker>
	                    <show-error :form-name="terminationForm" prop-name="date_of_termination"></show-error>
	                </div>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-12 col-sm-12">
	                <div class="form-group">
	                    <label for="">{{trans('student.termination_remarks')}}</label>
	                    <autosize-textarea v-model="terminationForm.termination_remarks" rows="5" name="termination_remarks" :placeholder="trans('student.termination_remarks')"></autosize-textarea>
	                    <show-error :form-name="terminationForm" prop-name="termination_remarks"></show-error>
	                </div>
	            </div>
	        </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-info waves-effect waves-light">{{trans('general.save')}}</button>
            </div>
	    </form>	
	    <div class="table-responsive" v-if="hasTerminationRecord">
            <table class="table table-sm">
            	<thead>
            		<tr>
            			<th>{{trans('student.admission_number_short')}}</th>
            			<th>{{trans('student.date_of_admission')}}</th>
            			<th>{{trans('student.date_of_promotion')}}</th>
            			<th>{{trans('student.date_of_termination')}}</th>
            			<th>{{trans('student.termination_remarks')}}</th>
            		</tr>
            	</thead>
            	<tbody>
            		<tr v-for="student_record in student.student_records" v-if="student_record.date_of_exit">
            			<td>{{getAdmissionNumber(student_record.admission)}}</td>
            			<td>{{student_record.date_of_entry | moment}}</td>
            			<td>
            				<span>{{student_record.admission.date_of_admission | moment}}</span>
            			</td>
            			<td>
            				<span v-if="student_record.date_of_exit">{{student_record.date_of_exit | moment}}</span>
            				<span v-else>-</span>
            			</td>
            			<td>
            				<span v-if="student_record.date_of_exit">{{student_record.exit_remarks}}</span>
            				<span v-else>-</span>
            			</td>
            		</tr>	
            	</tbody>
            </table>
        </div>
	</div>
</template>

<script>
	export default {
		props: ['student'],
		components: {},
		data() {
			return {
				terminationForm: new Form({
					date_of_termination: '',
					termination_remarks: ''
				})
			}
		},
		mounted(){

		},
		methods: {
			submit(){
				let loader = this.$loading.show();
				let student_record = this.student.student_records[0];
                this.terminationForm.date_of_termination = helper.toDate(this.terminationForm.date_of_termination);
				this.terminationForm.post('/api/student/'+this.student.uuid+'/terminate/'+student_record.id)
					.then(response => {
						toastr.success(response.message);
						this.$emit('completed');
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			getAdmissionNumber(admission){
				return helper.getAdmissionNumber(admission);
			}
		},
		computed: {
			eligibleForTermination(){
                let student_record = this.student.student_records.length ? this.student.student_records[0] : null;
                return student_record.date_of_exit ? false : true;
			},
			hasTerminationRecord(){
				return this.student.student_records.some(function(element) {
					return element.date_of_exit;
				})
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
        }
	}
</script>