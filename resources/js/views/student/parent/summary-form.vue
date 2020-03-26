<template>
    <form @submit.prevent="proceed" @keydown="parentForm.errors.clear($event.target.name)">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="form-group">
                    <label for="">{{trans('student.first_guardian_name')}}</label>
                    <input class="form-control" type="text" v-model="parentForm.father_name" name="father_name" :placeholder="trans('finance.father_name')">
                    <show-error :form-name="parentForm" prop-name="father_name"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="form-group">
                    <label for="">{{trans('student.father_contact_number')}}</label>
                    <input class="form-control" type="text" v-model="parentForm.father_contact_number_1" name="father_contact_number_1" :placeholder="trans('finance.father_contact_number')">
                    <show-error :form-name="parentForm" prop-name="father_contact_number_1"></show-error>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="form-group">
                    <label for="">{{trans('student.second_guardian_name')}}</label>
                    <input class="form-control" type="text" v-model="parentForm.mother_name" name="mother_name" :placeholder="trans('finance.mother_name')">
                    <show-error :form-name="parentForm" prop-name="mother_name"></show-error>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <router-link to="/student/parent" class="btn btn-danger waves-effect waves-light " v-show="id">{{trans('general.cancel')}}</router-link>
            <button v-if="!id" type="button" class="btn btn-danger waves-effect waves-light " @click="$emit('cancel')">{{trans('general.cancel')}}</button>
            <button type="submit" class="btn btn-info waves-effect waves-light">
                <span v-if="id">{{trans('general.update')}}</span>
                <span v-else>{{trans('general.save')}}</span>
            </button>
        </div>
    </form>
</template>


<script>
    export default {
        components: {},
        data() {
            return {
                parentForm: new Form({
                    father_name : '',
                    mother_name : '',
                    father_contact_number_1 : ''
                })
            };
        },
        props: ['id'],
        mounted() {
            if(!helper.hasPermission('edit-student')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            if(this.id)
                this.get();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.update();
                else
                    this.store();
            },
            store(){
                let loader = this.$loading.show();
                this.parentForm.post('/api/student/parent')
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            get(){
                let loader = this.$loading.show();
                axios.get('/api/student/parent/'+this.id)
                    .then(response => {
                        this.parentForm.father_name = response.father_name;
                        this.parentForm.mother_name = response.mother_name;
                        this.parentForm.father_contact_number_1 = response.father_contact_number_1;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                        this.$router.push('/student/parent');
                    });
            },
            update(){
                let loader = this.$loading.show();
                this.parentForm.patch('/api/student/parent/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/student/parent');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config) {
                return helper.getConfig(config);
            }
        }
    }
</script>
