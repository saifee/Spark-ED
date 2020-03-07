<template>
	<div>
        <form @submit.prevent="updateParent" @keydown="editParentForm.errors.clear($event.target.name)">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="">{{trans('student.edit_parent')}}</label>
                        <v-select label="name" v-model="selected_student_parent" name="student_parent_id" id="student_parent_id" :options="student_parents" :placeholder="trans('student.select_parent')" @select="onStudentParentSelect" @close="editParentForm.errors.clear('student_parent_id')" @remove="editParentForm.student_parent_id = ''">
                            <div class="multiselect__option" slot="afterList" v-if="!student_parents.length">
                                {{trans('general.no_option_found')}}
                            </div>
                        </v-select>
                        <show-error :form-name="editParentForm" prop-name="student_parent_id"></show-error>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                	<button type="submit" class="btn btn-info waves-effect waves-light pull-right">{{trans('general.save')}}</button>
                </div>
            </div>
        </form>

        <hr />

	    <form @submit.prevent="submit" @keydown="parentForm.errors.clear($event.target.name)">
	        <div class="row">
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_name')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_name" name="father_name" :placeholder="trans('student.father_name')">
	                    <show-error :form-name="parentForm" prop-name="father_name"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_name')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_name" name="mother_name" :placeholder="trans('student.mother_name')">
	                    <show-error :form-name="parentForm" prop-name="mother_name"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_date_of_birth')}}</label>
	                    <datepicker v-model="parentForm.father_date_of_birth" :bootstrapStyling="true" @selected="parentForm.errors.clear('father_date_of_birth')" :placeholder="trans('student.father_date_of_birth')" typeable></datepicker>
	                    <show-error :form-name="parentForm" prop-name="father_date_of_birth"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_date_of_birth')}}</label>
	                    <datepicker v-model="parentForm.mother_date_of_birth" :bootstrapStyling="true" @selected="parentForm.errors.clear('mother_date_of_birth')" :placeholder="trans('student.mother_date_of_birth')" typeable></datepicker>
	                    <show-error :form-name="parentForm" prop-name="mother_date_of_birth"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_qualification')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_qualification" name="father_qualification" :placeholder="trans('student.father_qualification')">
	                    <show-error :form-name="parentForm" prop-name="father_qualification"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_qualification')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_qualification" name="mother_qualification" :placeholder="trans('student.mother_qualification')">
	                    <show-error :form-name="parentForm" prop-name="mother_qualification"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_occupation')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_occupation" name="father_occupation" :placeholder="trans('student.father_occupation')">
	                    <show-error :form-name="parentForm" prop-name="father_occupation"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_occupation')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_occupation" name="mother_occupation" :placeholder="trans('student.mother_occupation')">
	                    <show-error :form-name="parentForm" prop-name="mother_occupation"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_annual_income')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_annual_income" name="father_annual_income" :placeholder="trans('student.father_annual_income')">
	                    <show-error :form-name="parentForm" prop-name="father_annual_income"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_annual_income')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_annual_income" name="mother_annual_income" :placeholder="trans('student.mother_annual_income')">
	                    <show-error :form-name="parentForm" prop-name="mother_annual_income"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_email')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_email" name="father_email" :placeholder="trans('student.father_email')">
	                    <show-error :form-name="parentForm" prop-name="father_email"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_email')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_email" name="mother_email" :placeholder="trans('student.mother_email')">
	                    <show-error :form-name="parentForm" prop-name="mother_email"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_contact_number_1')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_contact_number_1" name="father_contact_number_1" :placeholder="trans('student.father_contact_number_1')">
	                    <show-error :form-name="parentForm" prop-name="father_contact_number_1"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_contact_number_1')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_contact_number_1" name="mother_contact_number_1" :placeholder="trans('student.mother_contact_number_1')">
	                    <show-error :form-name="parentForm" prop-name="mother_contact_number_1"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_contact_number_2')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_contact_number_2" name="father_contact_number_2" :placeholder="trans('student.father_contact_number_2')">
	                    <show-error :form-name="parentForm" prop-name="father_contact_number_2"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_contact_number_2')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_contact_number_2" name="mother_contact_number_2" :placeholder="trans('student.mother_contact_number_2')">
	                    <show-error :form-name="parentForm" prop-name="mother_contact_number_2"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.father_unique_identification_number')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.father_unique_identification_number" name="father_unique_identification_number" :placeholder="trans('student.father_unique_identification_number')">
	                    <show-error :form-name="parentForm" prop-name="father_unique_identification_number"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
	                    <label for="">{{trans('student.mother_unique_identification_number')}}</label>
	                    <input class="form-control" type="text" v-model="parentForm.mother_unique_identification_number" name="mother_unique_identification_number" :placeholder="trans('student.mother_unique_identification_number')">
	                    <show-error :form-name="parentForm" prop-name="mother_unique_identification_number"></show-error>
	                </div>
	            </div>
	            <div class="col-12 col-sm-6">
	            	<upload-image id="father_photo" :upload-path="`/student/father/photo/${student.uuid}`" :remove-path="`/student/father/photo/remove/${student.uuid}`" :image-source="father_photo" @uploaded="updateFather" @removed="updateFather"></upload-image>
	            </div>
	            <div class="col-12 col-sm-6">
	            	<upload-image id="mother_photo" :upload-path="`/student/mother/photo/${student.uuid}`" :remove-path="`/student/mother/photo/remove/${student.uuid}`" :image-source="mother_photo" @uploaded="updateMother" @removed="updateMother"></upload-image>
	            </div>
	        </div>

	        <custom-field :fields="custom_fields" :customValues="custom_values" :clear="clearCustomField" :formErrors="customFieldFormErrors" @updateCustomValues="updateCustomValues"></custom-field>
            <div class="card-footer text-right">
        		<button type="submit" class="btn btn-info waves-effect waves-light">{{trans('general.save')}}</button>
            </div>
	   	</form>
	</div>
</template>

<script>
	export default {
		components: {},
		props: ['student'],
		data() {
			return {
				parentForm: new Form({
					father_name: null,
					mother_name: null,
		          	father_email: null,
		          	mother_email: null,
		          	father_date_of_birth: null,
		          	mother_date_of_birth: null,
		          	father_qualification: null,
		          	mother_qualification: null,
		          	father_occupation: null,
		          	mother_occupation: null,
		          	father_annual_income: null,
		          	mother_annual_income: null,
		          	father_contact_number_1: null,
		          	mother_contact_number_1: null,
		          	father_contact_number_2: null,
		          	mother_contact_number_2: null,
		          	father_unique_identification_number: null,
		          	mother_unique_identification_number: null,
					type: 'parent',
                    custom_values: []
				},false),
				father_photo: '',
				mother_photo: '',
				student_parents: [],
				editParentForm: new Form({
					student_parent_id: ''
				}),
                selected_student_parent: null,
                custom_fields: [],
                custom_values: [],
                clearCustomField: false,
                customFieldFormErrors: {}
			}
		},
		mounted(){
            if(!helper.hasPermission('edit-student')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getParents();
		},
		methods: {
            updateCustomValues(value) {
                this.parentForm.custom_values = value;
            },
            onStudentParentSelect(selectedOption){
                this.editParentForm.student_parent_id = selectedOption.id;
            },
			getParents(){
				let loader = this.$loading.show();
				axios.get('/api/student/pre-requisite?form_type=student_parent')
					.then(response => {
						this.custom_fields = response.custom_fields;
						this.student_parents = response.student_parents;
						this.get(this.student);
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			get(student){
				let parent = student.parent;
				if (parent) {
		          	this.parentForm.father_name = parent.father_name;
		          	this.parentForm.mother_name = parent.mother_name;
		          	this.parentForm.father_date_of_birth = parent.father_date_of_birth;
		          	this.parentForm.mother_date_of_birth = parent.mother_date_of_birth;
		          	this.parentForm.father_qualification = parent.father_qualification;
		          	this.parentForm.mother_qualification = parent.mother_qualification;
		          	this.parentForm.father_occupation = parent.father_occupation;
		          	this.parentForm.mother_occupation = parent.mother_occupation;
		          	this.parentForm.father_annual_income = parent.father_annual_income;
		          	this.parentForm.mother_annual_income = parent.mother_annual_income;
		          	this.parentForm.father_email = parent.father_email;
		          	this.parentForm.mother_email = parent.mother_email;
		          	this.parentForm.father_contact_number_1 = parent.father_contact_number_1;
		          	this.parentForm.mother_contact_number_1 = parent.mother_contact_number_1;
		          	this.parentForm.father_contact_number_2 = parent.father_contact_number_2;
		          	this.parentForm.mother_contact_number_2 = parent.mother_contact_number_2;
		          	this.parentForm.father_unique_identification_number = parent.father_unique_identification_number;
		          	this.parentForm.mother_unique_identification_number = parent.mother_unique_identification_number;
		          	this.father_photo = parent.father_photo;
		          	this.mother_photo = parent.mother_photo;
		          	this.custom_values = parent.options.hasOwnProperty('custom_values') ? parent.options.custom_values : [];
				}
	          },
			submit(){
				let loader = this.$loading.show();
                this.parentForm.father_date_of_birth = helper.toDate(this.parentForm.father_date_of_birth);
                this.parentForm.mother_date_of_birth = helper.toDate(this.parentForm.mother_date_of_birth);
				this.parentForm.patch('/api/student/'+this.student.uuid)
					.then(response => {
						this.$emit('complete');
						toastr.success(response.message);
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						this.customFieldFormErrors = error;
						helper.showErrorMsg(error);
					})
			},
			updateParent(){
				let loader = this.$loading.show();
				this.editParentForm.post('/api/student/'+this.student.uuid+'/parent')
					.then(response => {
						this.$emit('completed');
						toastr.success(response.message);
						loader.hide();
					})
					.catch(error => {
						loader.hide();
						helper.showErrorMsg(error);
					})
			},
			updateFather(){

			},
			updateMother(){
				
			}
		},
		watch: { 
      		student(student) {
      			this.get(student);
	        }
	    }
	}
</script>