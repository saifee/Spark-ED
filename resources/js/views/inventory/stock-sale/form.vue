<template>
  <div>
    <form
      @submit.prevent="proceed"
      @keydown="stockSaleForm.errors.clear($event.target.name)"
    >
      <div class="row">
        <div class="col-12 col-lg-5">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_date') }}</label>
                <datepicker
                  v-model="stockSaleForm.date"
                  :bootstrap-styling="true"
                  :placeholder="trans('inventory_sale.stock_sale_date')"
                  typeable
                  @selected="stockSaleForm.errors.clear('date')"
                />
                <show-error
                  :form-name="stockSaleForm"
                  prop-name="date"
                />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ trans('student.student') }}</label>
                <v-select
                  id="student_id"
                  v-model="selected_student"
                  label="name"
                  name="student_id"
                  :options="students"
                  :close-on-select="false"
                  :placeholder="trans('student.select_student')"
                  @select="onStudentSelect"
                  @close="stockSaleForm.errors.clear('student_id')"
@remove="stockSaleForm.student_id = ''"
                >
                  <div
                    v-if="!students.length"
                    slot="afterList"
                    class="multiselect__option"
                  >
                    {{ trans('general.no_option_found') }}
                  </div>
                </v-select>
                <show-error
                  :form-name="stockSaleForm"
                  prop-name="student_id"
                />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ trans('inventory.add_new_stock_item') }}</label>
                <v-select
                  v-model="addStockItem"
                  label="name"
                  :options="stock_items"
                  :close-on-select="false"
                  :placeholder="trans('inventory.select_stock_item')"
                  @input="addRow"
                >
                  <div
                    v-if="!stock_items.length"
                    slot="afterList"
                    class="multiselect__option"
                  >
                    {{ trans('general.no_option_found') }}
                  </div>
                </v-select>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_description') }}</label>
                <autosize-textarea
                  v-model="stockSaleForm.description"
                  rows="1"
                  name="description"
                  :placeholder="trans('inventory_sale.stock_sale_description')"
                />
                <show-error
                  :form-name="stockSaleForm"
                  prop-name="description"
                />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_payment_method') }}</label>
                <div class="radio radio-info p-l-0">
                  <div
                    v-for="(payment_method, i) in available_payment_methods"
                    :key="`payment_method${i}`"
                    class="form-check form-check-inline "
                  >
                    <input
                      :id="`payment_method${i}`"
                      v-model="stockSaleForm.payment_method"
                      class="form-check-input"
                      type="radio"
                      :value="payment_method"
                      :checked="stockSaleForm.payment_method == payment_method"
                      name="payment_method"
                      @click="stockSaleForm.errors.clear('payment_method')"
                    >
                    <label
                      class="form-check-label"
                      :for="`payment_method${i}`"
                    > {{ trans('inventory_sale.'+payment_method) }}</label>
                  </div>
                </div>
              </div>
            </div>
            <div
              v-if="stockSaleForm.payment_method === 'cash'"
              class="col-12"
            >
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_paid_amount') }}</label>
                <input
                  v-model="stockSaleForm.cash_paid"
                  class="form-control"
                  type="text"
                  name="cash_paid"
                  :placeholder="trans('inventory_sale.stock_sale_paid_amount')"
                >
              </div>
            </div>
            <div
              v-if="stockSaleForm.payment_method === 'cash'"
              class="col-12"
            >
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_discount') }}</label>
                <input
                  v-model="stockSaleForm.discount"
                  class="form-control"
                  type="text"
                  name="discount"
                  :placeholder="trans('inventory_sale.stock_sale_discount')"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-7">
          <div
            v-if="stockSaleForm.details.length === 0"
            class="row"
          >
            <div class="col-12">
              <p class="text-center font-italic shadow-none p-3 mb-5 bg-light rounded">
                no selected items
              </p>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <label for="">
                  {{ trans('inventory.stock_item') }}
                </label>
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_quantity') }}</label>
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_price') }}</label>
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <label for="">
                  {{ trans('inventory_sale.stock_sale_total') }}
                </label>
              </div>
            </div>
          </div>
          <div
            v-for="(detail, index) in stockSaleForm.details"
            :key="`stockSaleFormDetail${index}`"
            class="row"
          >
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <input
                  class="form-control"
                  type="text"
                  :value="getStockItemTitle(detail.stock_item_id)"
                  readonly
                >
                <button
                  :key="`${index}_delete_detail`"
                  v-confirm="{ok: confirmDelete(index)}"
                  v-tooltip="trans('general.delete')"
                  type="button"
                  class="btn btn-xs btn-danger"
                >
                  <i class="fas fa-times" />
                </button>
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <input
                  v-model="detail.quantity"
                  class="form-control"
                  type="text"
                  :name="getQuantityName(index)"
                  :placeholder="trans('inventory_sale.stock_sale_quantity')"
                >
                <show-error
                  :form-name="stockSaleForm"
                  :prop-name="getQuantityName(index)"
                />
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <input
                  v-model="detail.price"
                  class="form-control"
                  type="text"
                  :name="getPriceName(index)"
                  :placeholder="trans('inventory_sale.stock_sale_price')"
                >
                <show-error
                  :form-name="stockSaleForm"
                  :prop-name="getPriceName(index)"
                />
              </div>
            </div>
            <div class="col-12 col-sm-3">
              <div class="form-group">
                <input
                  class="form-control"
                  type="text"
                  :value="detail.price * detail.quantity"
                >
              </div>
            </div>
          </div>
          <div
            v-if="stockSaleForm.details.length > 0"
            class="table-responsive"
          >
            <table class="table table-sm custom-show-table">
              <tbody>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_sub_total') }}</td>
                  <td>{{ getSubTotal() }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_discount') }}</td>
                  <td>{{ stockSaleForm.discount }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_total') }}</td>
                  <td>{{ getTotal() }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_paid') }}</td>
                  <td>{{ getPaid() }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_balance') }}</td>
                  <td>{{ getBalance() }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button
          v-show="id"
          type="button"
          class="btn btn-danger "
          @click="$router.push('/inventory/stock/sale')"
        >
          {{ trans('general.cancel') }}
        </button>
        <button
          v-if="!id"
          type="button"
          class="btn btn-danger "
          @click="$emit('cancel')"
        >
          {{ trans('general.cancel') }}
        </button>
        <button
          type="submit"
          class="btn btn-info waves-effect waves-light"
        >
          {{ trans('general.save') }}
        </button>
      </div>
    </form>
  </div>
</template>

<script>

    export default {
        components: {},
        props: ['id'],
        data(){
            return {
                addStockItem: null,
                stockSaleForm: new Form({
                    transfer_type: 'sale',
                    type: 'student',
                    date: `${new Date().getFullYear()}-${('0' + (new Date().getMonth() + 1)).slice(-2)}-${('0' + new Date().getDate()).slice(-2)}`,
                    student_id: '',
                    description: '',
                    details: [],
                    payment_method: '',
                    cash_paid: '',
                    discount: '',
                    upload_token: ''
                }),
                stock_items: [],
                students: [],
                selected_student: null,
                payment_methods: ['wallet', 'cash'],
                module_id: '',
                clearAttachment: true
            }
        },
        computed:{
            available_payment_methods() {
                let student = this.getStudent(this.stockSaleForm.student_id)
                if (student && student.options && student.options.enable_student_wallet && student.options.enable_student_wallet === 1) {
                    return this.payment_methods
                } else {
                    return this.payment_methods.filter(pm => pm !== 'wallet')
                }
            },
        },
        mounted(){
            if(this.id)
                this.get();
            else
                this.stockSaleForm.upload_token = this.$uuid.v4();

            this.getPreRequisite();

            document.getElementById('student_id').autofocus = true

            this.focusField('student_id')
        },
        methods: {
            focusField(field){
                document.getElementById(field).style.display = 'none'
                setTimeout(() => {
                    document.getElementById(field).style.display = 'block'
                }, 300)
            },
            proceed(){
                if(this.id)
                    this.update();
                else
                    this.store();
            },
            getPreRequisite(){
                let loader = this.$loading.show();
                axios.get('/api/stock/transfer/pre-requisite')
                    .then(({students, stock_items}) => {
                        this.students = students;
                        this.stock_items = stock_items.filter(si => si.sale_price !== null);
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            addRow(selectedOption){
                const item = this.stockSaleForm.details.find(x => x.stock_item_id === selectedOption.id)
                if (item) {
                    item.quantity++
                } else {
                let new_index = this.stockSaleForm.details.push({
                    quantity: 1,
                    price: selectedOption.sale_price,
                    stock_item_id: selectedOption.id,
                    description: '',
                    selected_stock_item: Object.assign({}, this.addStockItem),
                })
                }
                this.addStockItem = null
            },
            getStockItemTitle(stockItemID){
                const item = this.stock_items.find((si) => si.id === stockItemID)
                if (item) {
                    return item.name
                }
            },
            getDescriptionName(index){
                return index+'_description';
            },
            getQuantityName(index){
                return index+'_quantity';
            },
            getPriceName(index){
                return index+'_price';
            },
            get(){
                let loader = this.$loading.show();
                axios.get('/api/stock/transfer/'+this.id)
                    .then(response => {
                        this.stockSaleForm.type = response.stock_transfer.type;
                        this.module_id = response.stock_transfer.id;
                        this.stockSaleForm.number = response.stock_transfer.number;
                        this.stockSaleForm.date = response.stock_transfer.date;
                        this.stockSaleForm.description = response.stock_transfer.description;
                        this.stockSaleForm.student_id = response.stock_transfer.student_id;
                        this.selected_student = response.selected_student;
                        response.stock_transfer.details.forEach(detail => {
                            this.stockSaleForm.details.push({
                                quantity: detail.quantity,
                                price: detail.price,
                                stock_item_id: detail.stock_item_id,
                                selected_stock_item: (detail.stock_item_id) ? {id: detail.stock_item_id, name: detail.item.name} : null,
                                description: detail.description
                            });
                        });
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            store(){
                let loader = this.$loading.show();
                this.stockSaleForm.date = helper.toDate(this.stockSaleForm.date);
                this.stockSaleForm.post('/api/stock/transfer')
                    .then(response => {
                        toastr.success(response.message);
                        this.selected_student = null;
                        this.stockSaleForm.details = [];
                        this.clearAttachment = !this.clearAttachment;
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            update(){
                let loader = this.$loading.show();
                this.stockSaleForm.date = helper.toDate(this.stockSaleForm.date);
                this.stockSaleForm.patch('/api/stock/transfer/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/inventory/stock/sale');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    })
            },
            getStudent(student_id){
                return (student_id) ? this.students.find(s => s.id === student_id) : null
            },
            onStudentSelect(selectedOption){
                if (!selectedOption) return
                this.stockSaleForm.student_id = selectedOption.id;
                let student = this.getStudent(selectedOption.id)
                this.stockSaleForm.payment_method = (student && student.options && student.options.enable_student_wallet && student.options.enable_student_wallet === 1) ? 'wallet' : 'cash'
            },
            confirmDelete(index){
                return dialog => this.deleteDetail(index);
            },
            deleteDetail(index){
                this.stockSaleForm.details.splice(index, 1);
            },
            getSubTotal(){
                return this.stockSaleForm.details.map(d => d.price * d.quantity).reduce((a,b) => a+b, 0);
            },
            getTotal(){
                var total = 0
                total += this.getSubTotal();
                total -= this.stockSaleForm.discount;
                return total
            },
            getPaid(){
                if (this.stockSaleForm.payment_method === 'wallet') {
                    return this.getTotal()
                } else if (this.stockSaleForm.payment_method === 'cash') {
                    return this.stockSaleForm.cash_paid
                }
            },
            getBalance(){
                var balance = this.getTotal() - this.getPaid()
                return balance
            },
        }
    }
</script>
