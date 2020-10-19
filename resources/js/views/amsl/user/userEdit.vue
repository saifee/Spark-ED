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
                    v-validate="'required|min:3'"
                    :options="['sub-admin','user']"
                    label="Account Type"
                    name="role"
                    data-vv-name="role"
                    :class="{ 'is-danger': errors.has('role') }"
                  />
                  <div
                    v-show="errors.has('role')"
                    class="help is-danger"
                  >
                    {{ errors.first('role') }}
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Name *</label>
                  <input
                    v-model="userInfo.name"
                    v-validate="'required'"
                    :class="{'form-control': true, 'is-danger': errors.has('name') }"
                    type="text"
                    name="name"
                    data-vv-name="name"
                    placeholder="Enter name"
                  >
                  <div
                    v-show="errors.has('name')"
                    class="help is-danger"
                  >
                    {{ errors.first('name') }}
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Email *</label>
                  <input
                    v-model="userInfo.email"
                    v-validate="'required'"
                    :class="{'form-control': true, 'is-danger': errors.has('email') }"
                    type="email"
                    name="email"
                    data-vv-name="email"
                    placeholder="Enter email"
                  >
                  <div
                    v-show="errors.has('email')"
                    class="help is-danger"
                  >
                    {{ errors.first('email') }}
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Password *</label>
                  <input
                    v-model="userInfo.password"
                    v-validate="'min:6'"
                    :class="{'form-control': true, 'is-danger': errors.has('password') }"
                    type="password"
                    name="password"
                    data-vv-name="password"
                    placeholder="Enter password"
                  >
                  <div
                    v-show="errors.has('password')"
                    class="help is-danger"
                  >
                    {{ errors.first('password') }}
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
                    v-validate="'required|min:6'"
                    :class="{'form-control': true, 'is-danger': errors.has('password') }"
                    type="password"
                    name="admin_password"
                    data-vv-name="admin_password"
                    placeholder="Enter admin password"
                  >
                  <div
                    v-show="errors.has('admin_password')"
                    class="help is-danger"
                  >
                    {{ errors.first('admin_password') }}
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
            axios.get('/user-info/'+this.$route.params.id+'/edit').then(res=>{
                this.userInfo=res.data
            })
        },
        methods: {
            postUser(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.patch('/user-info/'+this.$route.params.id,this.userInfo).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$validator.reset()
                            this.userInfo.admin_password=null
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
                this.$router.push({path:'/user-info'})
            }

        }
    }
</script>

<style scoped>

</style>
