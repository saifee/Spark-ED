<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="tile">
          <h3 class="tile-title">
            Edit Employee
          </h3>
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postEmployee"
            >
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Name *</label>
                  <input
                    v-model="employee.name"
                    v-validate="'required|min:3'"
                    :class="{'form-control': true, 'is-danger': errors.has('name') }"
                    type="text"
                    name="name"
                    data-vv-name="name"
                    placeholder="Enter full name"
                  >
                  <div
                    v-show="errors.has('name')"
                    class="help is-danger"
                  >
                    {{ errors.first('name') }}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label"> Employee ID *</label>
                  <input
                    v-model="employee.id_card"
                    v-validate="'required'"
                    type="text"
                    name="id_card"
                    placeholder="ID No"
                    :class="{'form-control': true, 'is-danger': errors.has('id_card') }"
                    data-vv-name="id_card"
                  >
                  <div
                    v-show="errors.has('id_card')"
                    class="help is-danger"
                  >
                    {{ errors.first('id_card') }}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <input
                    v-model="employee.email"
                    class="form-control"
                    type="email"
                    name="email"
                    placeholder="Enter email address"
                  >
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Phone</label>
                  <input
                    v-model="employee.phone"
                    class="form-control"
                    type="number"
                    placeholder="Enter Phone"
                  >
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Address</label>
                  <input
                    v-model="employee.address"
                    class="form-control"
                    type="text"
                    name="address"
                    placeholder="Address"
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
                sError:{},
                employee:{}
            }
        },
        created(){
            axios.get('/employee/'+this.$route.params.id+'/edit').then(res=>{
                this.employee=res.data
            })
        },
        methods: {
            postEmployee(id){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.patch('/employee/'+this.$route.params.id,this.employee).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
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
                this.$router.push({path:'/employee'})
            }

        }
    }
</script>

<style scoped>

</style>
