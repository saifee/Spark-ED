<template>
	<div v-if="student.id">
	    <form @submit.prevent="submit" @keydown="walletForm.errors.clear($event.target.name)">
	        <div class="row">
	            <div class="col-12 col-sm-6">
	                <div class="form-group">
		                    	<v-switch v-model="walletForm.enable_student_wallet" :label="trans('student_wallet.enable_student_wallet')" color="success" />
	                </div>
	                <template v-if="walletForm.enable_student_wallet">
		                <div class="form-group">
		                	<label for="">{{trans('student_wallet.student_wallet_limit')}}</label>
		                    <input class="form-control" type="text" v-model="walletForm.student_wallet_limit" name="student_wallet_limit" :placeholder="trans('student_wallet.student_wallet_limit')">
		                    <show-error :form-name="walletForm" prop-name="student_wallet_limit"></show-error>
		                </div>
		            </template>
	            </div>
	        </div>
            <div class="card-footer text-right">
	        	<button type="submit" class="btn btn-info">{{trans('general.save')}}</button>
            </div>
	    </form>
	</div>
</template>

<script>
	export default {
		props: ['student'],
		data(){
			return {
				walletForm: new Form({
					enable_student_wallet: false,
					student_wallet_limit: '',
				})
			}
		},
		mounted(){
			this.updateWalletForm(this.student);
		},
		methods: {
			submit(){
				let loader = this.$loading.show();
				this.walletForm.patch('/api/student/'+this.student.uuid+'/wallet/status')
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
			updateWalletForm(student){
				this.walletForm.enable_student_wallet = (student.options && student.options.enable_student_wallet == 1) ? true : false;
				this.walletForm.student_wallet_limit = (student.options && student.options.student_wallet_limit) ? student.options.student_wallet_limit : helper.getConfig('student_wallet_limit');
			}
		},
		watch: {
			student(student){
				this.updateWalletForm(student);
			}
		}
	}
</script>
