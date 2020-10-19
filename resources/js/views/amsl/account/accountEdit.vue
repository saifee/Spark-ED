<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="tile">
          <h3 class="tile-title">
            Update Account
          </h3>
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="updateAccount"
            >
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Type *</label>
                  <v-select
                    v-model="account.account_type"
                    v-validate="'required|min:3'"
                    :options="['Fixed Asset','Current Asset','Current Asset-AR','Liabilities-AP','Long-term Liabilities','Short-term Liabilities','Expense','Income']"
                    label="name"
                    name="account_type"
                    data-vv-name="account_type"
                    :class="{ 'is-danger': errors.has('account_type') }"
                  />
                  <div
                    v-show="errors.has('account_type')"
                    class="help is-danger"
                  >
                    {{ errors.first('account_type') }}
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Name *</label>
                  <input
                    v-model="account.name"
                    v-validate="'required|min:3'"
                    :class="{'form-control': true, 'is-danger': errors.has('name') }"
                    type="text"
                    name="name"
                    data-vv-name="name"
                    placeholder="Enter Account name"
                  >
                  <div
                    v-show="errors.has('name')"
                    class="help is-danger"
                  >
                    {{ errors.first('name') }}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="account.description"
                    class="form-control"
                    type="text"
                    name="description"
                    placeholder="Description"
                  >
                </div>
              </div>

              <div class="col-md-12">
                <button
                  class="btn btn-primary"
                  type="submit"
                >
                  <i class="fa fa-fw fa-lg fa-check-circle" />
                  Update
                </button>
                <button
                  class="btn btn-secondary"
                  href="#"
                  @click.prevent="cancel"
                >
                  <i class="fa fa-fw fa-lg fa-times-circle" />
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                account:{}
            }
        },
        created(){
            axios.get('/account/'+this.$route.params.id+'/edit').then(res=>{
                this.account=res.data
            })
        },
        methods: {
            updateAccount(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.patch('/account/'+this.$route.params.id,this.account).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$root.getAccounts()
                        }).catch(error => {
                            let err
                            let errs = error.response.data.errors
                            for (err in errs) {
                                this.errors.add({
                                    'field': err,
                                    'msg': errs[err][0]
                                })
                            }
                        })
                    }
                })
            },
            cancel(){
                this.$router.push({path:'/account'})
            }
        }
    }
</script>

<style scoped>

</style>
