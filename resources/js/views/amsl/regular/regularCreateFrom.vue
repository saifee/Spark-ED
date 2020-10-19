<template>
  <div>
    <form
      action=""
      class="pb-4"
      @submit.prevent="postRegular"
    >
      <div class="row mx-auto">
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Date*</label>
            <date-picker
              v-model="regular.date"

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
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Name</label>
            <input
              v-model="regular.name"

              class="form-control"
              type="text"

              name="name"
              placeholder="Name"
            >
            <div
              v-show="false"
              class="help is-danger"
            >
              {{ 'error' }}
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">AGT</label>
            <input
              v-model="regular.agt"
              class="form-control"
              type="text"
              name="agt"
              placeholder="Agt"
            >
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">Ref</label>
            <input
              v-model="regular.ref"
              class="form-control"
              type="text"
              name="ref"
              placeholder="ref"
            >
          </div>
        </div>
        <!--<div class="col-md-2">-->
        <!--<div class="form-group">-->
        <!--<label class="control-label">M.R</label>-->
        <!--<input-->
        <!--class="form-control"-->
        <!--v-model="regular.mr"-->
        <!--type="text"-->
        <!--name="mr"-->
        <!--placeholder="MR">-->
        <!--</div>-->
        <!--</div>-->
      </div>
      <hr>
      <div class="col-md-12 row ">
        <div class="col-md-6 row  mx-auto  border-right">
          <div class="row pt-4">
            <div class="col-md-4 ">
              <div class="form-group">
                <label class="control-label">Cash</label>
                <input
                  v-model="regular.cash"
                  class="form-control"
                  type="number"
                  step="any"
                  name="cash"
                  placeholder="cash"
                >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group ">
                <label class="control-label">Bank</label>
                <input
                  v-model="regular.bank"
                  class="form-control"
                  type="number"
                  step="any"
                  name="bank"
                  placeholder="Bank"
                >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group ">
                <label class="control-label">Eviivo</label>
                <input
                  v-model="regular.eviivo"
                  class="form-control"
                  type="number"
                  step="any"
                  name="eviivo"
                  placeholder="Eviivo"
                >
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group ">
                <label class="control-label">Return</label>
                <input
                  v-model="regular.return"
                  class="form-control"
                  type="number"
                  step="any"
                  name="return"
                  placeholder="Return"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <br>
            <div class="col-md-12">
              <br>
              <h3 class="pl-2">
                Parking
              </h3>
            </div>
            <div class="col-md-4">
              <div class="form-group own-form-size">
                <label class="control-label">Cash</label>
                <input
                  v-model="regular.parking_cash"
                  class="form-control"
                  type="number"
                  step="any"
                  name="parking_cash"
                  placeholder="Cash"
                >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group own-form-size">
                <label class="control-label">Card</label>
                <input
                  v-model="regular.parking_card"
                  class="form-control"
                  type="number"
                  step="any"
                  name="parking_card"
                  placeholder="Card"
                >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group own-form-size">
                <label class="control-label">Other Sales</label>
                <input
                  v-model="regular.other_sales"
                  class="form-control"
                  type="number"
                  step="any"
                  name="other_sale"
                  placeholder="Other Sale"
                >
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group own-form-size">
                <label class="control-label">Advance Sales</label>
                <input
                  v-model="regular.advance_sales"
                  class="form-control"
                  type="number"
                  step="any"
                  name="other_sale"
                  placeholder="Advance Sale"
                >
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group own-form-size">
                <label class="control-label">Remark</label>
                <input
                  v-model="regular.remark"
                  class="form-control"
                  type="text"
                  name="remark"
                  placeholder="Remark"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 row ">
        <br>
        <button
          class="btn btn-primary mr-2"
          type="submit"
          :disabled="btnDisabled"
        >
          <i class="fa fa-fw fa-lg fa-check-circle" />
          Add
        </button>
        <button
          class="btn btn-secondary"
          @click.prevent="cancel"
        >
          <i class="fa fa-fw fa-lg fa-times-circle" />
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script>
    export default {
        data(){
            return{
                regular:{date: new Date()},
                options: {
                    format: 'DD/MM/YYYY HH:mm:ss',
                    useCurrent: true,
                },
                btnDisabled:false
            }
        },
        methods:{
            postRegular(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.btnDisabled = true
                        axios.post('/regular-form', this.regular).then(response => {
                            this.$swal({
                                type: response.data.type,
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$validator.reset()
                            this.regular = {date: new Date()}
                            this.$parent.callModal(false)
                            this.$router.push({path:'/regular'})
                            this.btnDisabled = false
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
                this.$parent.callModal(false)
                this.$router.push({path:'/regular'})

            }
        }
    }
</script>
<style scoped>
    .own-form-size{
        padding-left: 5px!important;
        padding-right: 5px!important;
    }
</style>
