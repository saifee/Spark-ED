<template>
  <div>
    <div class="row">
      <div class="col-md-12">
        <div class="InputFormHeader">
          Company Account Details
        </div>
        <div
          class="tile"
          style="box-shadow: none"
        >
          <div class="tile-body">
            <form
              class="row"
              @submit.prevent="postCompany"
            >
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Company Name *</label>
                  <input
                    v-model="company.name"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="text"
                    name="name"

                    placeholder="Enter Company name"
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
                  <label class="control-label">Mobile</label>
                  <input
                    v-model="company.mobile"

                    :class="{'form-control': true, 'is-danger': false }"
                    type="text"
                    name="mobile"

                    placeholder="Enter mobile"
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
                  <label class="control-label">Address</label>
                  <input
                    v-model="company.address"
                    class="form-control"
                    type="text"
                    name="address"
                    placeholder="Address"
                  >
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">
                    Logo
                    <small class="text-danger">
                      (Show your logo better way please put this size)
                      <span class="badge badge-danger p-1">175px X 50px</span>
                    </small>
                  </label>
                  <input
                    id="logo"
                    type="file"
                    class="upload-logo text-white bg-dark form-control"
                    name="image_path"
                  >
                </div>
                <template v-if="company.image_path">
                  <div style="padding: 20px">
                    <img
                      :src="'/storage/'+company.image_path"
                      height="100px"
                      width="100px"
                    >
                  </div>
                </template>
              </div>

              <div class="col-md-12">
                <button
                  id="pressButton"
                  class="btn btn-primary"
                  type="submit"
                >
                  <i class="fa fa-fw fa-lg fa-check-circle" />
                  Submit
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
                company:{},
            }
        },
        created(){
            axios.get('/asml-api'+'/company/1/edit').then(res=>{
                this.company=res.data?res.data:{}
            })
        },
        methods: {
            postCompany(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        var data = new FormData()
                        var imagefile = document.querySelector('#logo')
                        data.append("file", imagefile.files[0])
                        data.append('company',JSON.stringify(this.company))

                        axios.post('/asml-api'+'/company',data,{
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                            .then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$validator.reset()
                            this.$router.push({name:'dashboardAmsl'})
                            window.location.reload();
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
                this.$router.push({name:'dashboardAmsl'})
            }

        }
    }
</script>

<style scoped>

</style>
