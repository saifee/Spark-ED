<template>
  <div>
    <div class="row">
      <div class="col-md-12 title">
        <div class="InputFormHeader">
          <i class="fa fa-user-plus" /> Update Employee History
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

                    :options="employees"
                    label="name"
                    name="account"

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
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Start Date&Time*</label>
                  <date-picker
                    v-model="history.start_date"

                    :config="options"
                  />
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
                history:{},
                employees:[],
                options: {
                    format: 'DD-MM-YYYY HH:mm',
                    useCurrent: true,
                },
            }
        },
        created(){
            axios.get('/amsl-api'+'/employee/history/'+this.$route.params.id+'/edit').then(res=>{
                this.employees=res.data.employees
                this.history=res.data.history
                this.history.start_date=res.data.history.start_date?this.parseDate(res.data.history.start_date):null
                this.history.end_date=res.data.history.end_date?this.parseDate(res.data.history.end_date):null
            })
        },
        methods: {
            postEmployee(id){
                Promise.resolve(true).then((result) => {
                    if (result) {
                        axios.patch('/amsl-api'+'/employee/history/'+this.$route.params.id,this.history).then(response => {
                            /* this.$swal */({
                                // type: response.data.type,
                                // title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
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
                this.$router.push({path:'/employee/history'})
            },
            parseDate(val){
                var spliceDate = val.slice(0,10)
                var spliceTime = val.slice(11,19)
                const [year, month, day] =spliceDate.split('-')
                const [hour, minute, sec] =spliceTime.split('-')
                return day+'-'+month+'-'+year+' '+hour+':'+minute
            },

        }
    }
</script>

<style scoped>

</style>
