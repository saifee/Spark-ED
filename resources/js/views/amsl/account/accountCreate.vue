<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="InputFormHeader">
          <i class="fa fa-plus-circle" /> Add Account
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postAccount"
            >
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Type *</label>
                  <v-select
                    v-model="account.account_type"

                    :options="['Fixed Asset','Current Asset','Current Asset-AR','Liabilities-AP','Long-term Liabilities','Short-term Liabilities','Expense','Income']"
                    label="name"
                    name="account_type"

                    :class="{ 'is-danger': false }"
                  />
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Name *</label>
                  <input
                    v-model="account.name"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="text"
                    name="name"

                    placeholder="Enter Account name"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
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
                  id="pressButton"
                  class="btn btn-primary"
                  type="submit"
                >
                  <i class="fa fa-fw fa-lg fa-check-circle" />
                  Add
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
        methods: {
            postAccount(){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        axios.post('/amsl-api'+'/account',this.account).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$root.getAccounts()
                            // this.$validator.reset()
                            this.account={}
                        }).catch(error => {
                            let err
                            let errs = error.response.data.errors
                            for (err in errs) {
                                /* this.errors.add */({
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
