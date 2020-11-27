<template>
  <div>
    <form
      @submit.prevent="proceed"
      @keydown="stockSaleForm.errors.clear($event.target.name)"
    >
      <v-row>
        <v-col
          cols="12"
          lg="7"
        >
          <v-row>
            <v-col cols="12">
              <v-autocomplete
                v-model="addStockItem"
                outlined
                dense
                hide-details
                auto-select-first
                clearable
                item-text="name"
                return-object
                :items="stock_items"
                :placeholder="trans('inventory.select_stock_item')"
                @input="addRow"
              />
              <v-divider />
              <v-row>
                <template v-if="stock_items.length === 0">
                  <v-col class="grey--text lighten-2">
                    no items
                  </v-col>
                </template>
                <v-col
                  v-for="(stock_item, i) in stock_items"
                  :key="`stock_item${i}`"
                  cols="12"
                  lg="3"
                >
                  <v-card @click="addRow(stock_item)">
                    <v-avatar
                      class="ma-3"
                      size="125"
                      tile
                    >
                      <v-img src="/camera-placeholder.jpg" />
                    </v-avatar>
                    <v-card-title class="subtitle-1 pt-0">
                      {{ stock_item.name }}
                    </v-card-title>
                    <v-card-subtitle>{{ formatCurrency(stock_item.sale_price) }}</v-card-subtitle>
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-col>
        <v-col
          cols="12"
          lg="5"
        >
          <template v-if="stockSaleForm.details.length === 0">
            <p class="text-center font-italic shadow-none p-3 mb-5 bg-light rounded">
              no selected items
            </p>
          </template>
          <v-list-item
            v-for="(detail, index) in stockSaleForm.details"
            :key="`stockSaleFormDetail${index}`"
          >
            <v-list-item-content>
              <v-list-item-title v-text="getStockItemTitle(detail.stock_item_id)" />
              <v-list-item-subtitle>
                <v-icon
                  small
                  @click="dItemQuantity(detail)"
                >
                  remove_circle
                </v-icon>
                {{ detail.quantity }} Unit(s)
                <v-icon
                  small
                  @click="iItemQuantity(detail)"
                >
                  add_circle
                </v-icon>
                &ndash;
                {{ formatCurrency(detail.price) }} per unit
              </v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-action>
              <v-icon
                small
                @click="deleteDetail(index)"
              >
                cancel
              </v-icon>
              <v-list-item-action-text v-text="formatCurrency(detail.price * detail.quantity)" />
            </v-list-item-action>
          </v-list-item>
          <div
            class="table-responsive"
          >
            <table class="table table-bordered table-sm font-weight-black">
              <tbody>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_sub_total') }}</td>
                  <td>{{ formatCurrency(getSubTotal()) }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_discount') }}</td>
                  <td>{{ formatCurrency(+stockSaleForm.discount) }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_total') }}</td>
                  <td>{{ formatCurrency(getTotal()) }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_paid') }}</td>
                  <td>{{ formatCurrency(getPaid()) }}</td>
                </tr>
                <tr>
                  <td>{{ trans('inventory_sale.stock_sale_balance') }}</td>
                  <td>{{ formatCurrency(getBalance()) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <v-row>
            <v-col
              cols="12"
              lg="6"
            >
              <label for="">{{ trans('student.student') }}</label>
              <v-select
                v-model="stockSaleForm.student_id"
                outlined
                dense
                hide-details
                clearable
                item-text="name"
                item-value="id"
                :items="students"
                :placeholder="trans('student.select_student')"
                @select="onStudentSelect"
                @close="stockSaleForm.errors.clear('student_id')"
                @remove="stockSaleForm.student_id = ''"
              />
              <show-error
                :form-name="stockSaleForm"
                prop-name="student_id"
              />
            </v-col>
            <v-col
              cols="12"
              lg="6"
            >
              <div class="form-group">
                <label for="">{{ trans('inventory_sale.stock_sale_payment_method') }}</label>
                <v-radio-group
                  v-model="stockSaleForm.payment_method"
                  hide-details
                >
                  <v-radio
                    v-for="(payment_method, i) in available_payment_methods"
                    :key="`payment_method${i}`"
                    :label="trans('inventory_sale.'+payment_method)"
                    :value="payment_method"
                  />
                </v-radio-group>
              </div>
            </v-col>
            <template
              v-if="stockSaleForm.payment_method === 'cash'"
            >
              <v-col
                cols="12"
                lg="6"
              >
                <label for="">{{ trans('inventory_sale.stock_sale_paid_amount') }}</label>
                <v-text-field
                  v-model="stockSaleForm.cash_paid"
                  name="cash_paid"
                  type="number"
                  :placeholder="trans('inventory_sale.stock_sale_paid_amount')"
                  outlined
                  dense
                  hide-details
                />
              </v-col>
              <v-col
                cols="12"
                lg="6"
              >
                <label for="">{{ trans('inventory_sale.stock_sale_discount') }}</label>
                <v-text-field
                  v-model="stockSaleForm.discount"
                  name="discount"
                  type="number"
                  :placeholder="trans('inventory_sale.stock_sale_discount')"
                  outlined
                  dense
                  hide-details
                />
              </v-col>
              <v-col cols="12">
                <v-btn
                  color="primary"
                  type="submit"
                >
                  {{ trans('general.save') }}
                </v-btn>
              </v-col>
            </template>
          </v-row>
        </v-col>
      </v-row>
    </form>
  </div>
</template>

<script>
    import { VSelect } from 'vuetify/lib'
    export default {
        components: {
          VSelect,
        },
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
        },
        methods: {
            formatCurrency(price){
                return helper.formatCurrency(price);
            },
            proceed(){
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
                this.$nextTick(() => {
                this.addStockItem = null
                });
            },
            getStockItemTitle(stockItemID){
                const item = this.stock_items.find((si) => si.id === stockItemID)
                if (item) {
                    return item.name
                }
            },
            dItemQuantity(detail){
                if (detail.quantity-1 < 1) {
                    let index = this.stockSaleForm.details.indexOf(detail)
                    this.deleteDetail(index)
                    return
                }
                this.$set(detail, 'quantity', detail.quantity-1)
            },
            iItemQuantity(detail){
                this.$set(detail, 'quantity', detail.quantity+1)
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
            getStudent(student_id){
                return (student_id) ? this.students.find(s => s.id === student_id) : null
            },
            onStudentSelect(selectedOption){
                if (!selectedOption) return
                this.stockSaleForm.student_id = selectedOption.id;
                let student = this.getStudent(selectedOption.id)
                this.stockSaleForm.payment_method = (student && student.options && student.options.enable_student_wallet && student.options.enable_student_wallet === 1) ? 'wallet' : 'cash'
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
                    return +this.stockSaleForm.cash_paid
                }
            },
            getBalance(){
                var balance = this.getTotal() - this.getPaid()
                return balance
            },
        }
    }
</script>
