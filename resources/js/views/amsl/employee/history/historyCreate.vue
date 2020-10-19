<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-user-plus" /> Add Employee History
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postEmployee"
            >
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Employee *</label>
                  <v-select
                    v-model="history.employee"
                    v-validate="'required|min:3'"
                    :options="employees"
                    label="name"
                    name="account"
                    data-vv-name="account"
                    :class="{ 'is-danger': errors.has('account') }"
                  />
                  <div
                    v-show="errors.has('account')"
                    class="help is-danger"
                  >
                    {{ errors.first('account') }}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Start Date&Time*</label>
                  <date-picker
                    v-model="history.start_date"
                    v-validate="'required|min:3'"
                    :config="options"
                    data-vv-name="start_date"
                  />
                  <div
                    v-show="errors.has('start_date')"
                    class="help is-danger"
                  >
                    {{ errors.first('start_date') }}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">End Date&Time</label>
                  <date-picker
                    v-model="history.end_date"
                    :config="options"
                  />
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Description</label>
                  <input
                    v-model="history.description"
                    class="form-control"
                    type="text"
                    name="address"
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
                sError:{},
                history:{},
                employees:[],
                options: {
                    format: 'DD-MM-YYYY HH:mm',
                    useCurrent: true,
                },

            }
        },
        created(){
          axios.get('/employee/history/create').then(res=>{
                this.employees=res.data
          })
        },
        methods: {
            postEmployee(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post('/employee/history',this.history).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$validator.reset()
                            this.history={}
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
                this.$router.push({path:'/employee/history'})
            }

        }
    }
</script>

<style scoped>

</style>
