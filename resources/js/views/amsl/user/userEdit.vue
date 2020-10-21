<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="InputFormHeader">
          <i class="fa fa-plus-circle" /> Update User
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postUser"
            >
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Account Type *</label>
                  <v-select
                    v-model="userInfo.role"

                    :options="['sub-admin','user']"
                    label="Account Type"
                    name="role"

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
                  <label class="control-label">Name *</label>
                  <input
                    v-model="userInfo.name"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="text"
                    name="name"

                    placeholder="Enter name"
                  >
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
                  <label class="control-label">Email *</label>
                  <input
                    v-model="userInfo.email"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="email"
                    name="email"

                    placeholder="Enter email"
                  >
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
                  <label class="control-label">Password *</label>
                  <input
                    v-model="userInfo.password"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="password"
                    name="password"

                    placeholder="Enter password"
                  >
                  <div
                    v-show="false"
                    class="help is-danger"
                  >
                    {{ 'error' }}
                  </div>
                </div>
              </div>
              <span
                class="ml-5"
                style="color:red"
              >N:B: Need Admin Password to update data</span>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Admin Current Password *</label>
                  <input
                    v-model="userInfo.admin_password"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="password"
                    name="admin_password"

                    placeholder="Enter admin password"
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
                <button
                  id="pressButton"
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
                userInfo:{},

            }
        },
        created(){
            axios.get('/amsl-api'+'/user-info/'+this.$route.params.id+'/edit').then(res=>{
                this.userInfo=res
            })
        },
        methods: {
            postUser(){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        axios.patch('/amsl-api'+'/user-info/'+this.$route.params.id,this.userInfo).then(response => {
                            toastr.success(response.message);
                            // this.$validator.reset()
                            this.userInfo.admin_password=null
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
                this.$router.push({path:'/user-info'})
            }

        }
    }
</script>

<style scoped>

</style>
