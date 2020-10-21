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

                    :class="{'form-control': true, 'is-danger': false }"
                    type="text"
                    name="name"

                    placeholder="Enter full name"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label"> Employee ID *</label>
                  <input
                    v-model="employee.id_card"

                    type="text"
                    name="id_card"
                    placeholder="ID No"
                    :class="{'form-control': true, 'is-danger': false }"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
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
            axios.get('/amsl-api'+'/employee/'+this.$route.params.id+'/edit').then(res=>{
                this.employee=res
            })
        },
        methods: {
            postEmployee(id){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        axios.patch('/amsl-api'+'/employee/'+this.$route.params.id,this.employee).then(response => {
                            toastr.success(response.message);
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
                this.$router.push({path:'/employee'})
            }

        }
    }
</script>

<style scoped>

</style>
