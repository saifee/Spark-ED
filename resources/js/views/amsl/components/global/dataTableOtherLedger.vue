<template>
  <div>
    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
      <div class="row be-datatable-header card-header">
        <div
          id="adjustHeader"
          class="col-md-12 d-flex justify-content-between  flex-wrap"
        >
          <div class="d-flex align-items-center justify-content-start">
            <div class="cateTitle">
              <i class="far fa-list-alt" /> -  {{ tableHeadline }}
            </div>
          </div>
          <div class="response_flex">
            <div
              v-if="search"
              class="search-container mr-2"
            >
              <datepicker
                v-model="from_date"
                :bootstrap-styling="true"
                :config="dateOptions"
                @input="searched=false"
              />
            </div>
            <div
              v-if="search"
              class="search-container mr-2"
            >
              <datepicker
                v-model="to_date"
                :bootstrap-styling="true"
                :config="dateOptions"
                @input="searched=false"
              />
            </div>
            <div
              v-if="search"
              class="search-container mr-2"
            >
              <button @click.prevent="assainSearchValue(),searched=true">
                Search
              </button>
            </div>
            <div
              v-if="search"
              class="search-container mr-2"
            >
              <input
                v-model="searchValue"
                type="text"
                placeholder="Search.."
                @keyup="getResult(null,dateSearchFieldName,from_date,to_date)"
              >
              <i class="fa fa-search searchIcon" />
            </div>
            <div class="printableArea">
              <router-link
                :to="{name:addLink}"
                class="headerIcon pt-1 pb-1"
              >
                <i
                  v-if="addButton"
                  class="fa fa-plus-circle"
                />
              </router-link>
              <a
                href=""
                class="headerIcon pt-1 pb-1"
                @click.prvent="printMe()"
              >
                <i class="fa fa-print" />
              </a>
              <a
                href=""
                class="headerIcon pt-1 pb-1"
                @click.prevent="$root.downloadExcel('table1')"
              >
                <i class="fa fa-file-excel-o" />
              </a>
            </div>
          </div>
        </div>
      </div>
      <div
        v-if="dataList.length"
        class="row be-datatable-body"
      >
        <div
          id="section-to-print"
          class="col-md-12  mt-2 mb-2"
          style="overflow-x: auto;"
        >
          <div
            id="tableTop"
            style="display: none"
          >
            <h2
              v-if="$root.$data.company"
              class="text-center"
            >
              {{ $root.$data.company.name }}
            </h2>
            <h4 class="text-center">
              {{ tableHeadline }}
            </h4>
            <span style="display: flex; justify-content: center">FOR THE PERIOD
              <template v-if="from_date && to_date && searched">
                <strong>{{ $root.parseDateISO(from_date) | moment("DD MMMM, YYYY") }}</strong>  To <strong>{{ $root.parseDateISO(to_date) | moment("DD MMMM, YYYY") }}</strong>
              </template>
              <template v-else>
                (All Data From Starting to End)
              </template>

            </span>

            <template v-if="$root.$data.company">
              <h6 class="text-center">
                {{ $root.$data.company.mobile }}<br>
                {{ $root.$data.company.address }}<br>
              </h6>
            </template>
          </div>
          <table
            id="table1"
            class="table table-striped  table-fw-widget dataTable no-footer table_block ledgerTable"
            role="grid"
            aria-describedby="table1_info"
          >
            <thead>
              <tr role="row">
                <th
                  v-for="(column, index) in columns"
                  :id="column.field"
                  class="sorting"
                  tabindex="0"
                  rowspan="1"
                  colspan="1"
                  @click="onClickColumn"
                >
                  {{ column.name }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="data in dataList">
                <slot
                  name="items"
                  :data="data"
                />
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div
        v-else
        class="center-screen"
      />
      <div class="table__footer">
        <div class="bottomAdjust">
          <div>
            <div class="dataTables_length">
              <label>Show
                <select
                  id="resultPerPage"
                  style="text-align-last: center; height: 24px;"
                  name="table1_length"
                  aria-controls="table1"
                  class="form-control form-control-sm customShown"
                  @change="onChangePerPage"
                >
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="200">200</option>
                  <option value="All">All</option>
                </select> Entries &nbsp;</label>
            </div>
          </div>
          <div>
            <div
              id="table1_info"
              class="dataTables_info"
              role="status"
              aria-live="polite"
            >
              ( Showing {{ from }} to
              {{ to }}
              of {{ total }} )
            </div>
          </div>
        </div>
        <div class="bottom_pagination">
          <div
            v-if="paging"
            class="dataTables_paginate paging_simple_numbers mt-1"
          >
            <ul class="pagination">
              <li
                id="table1_previous"
                :class="{'paginate_button': true, 'page-item':true, 'previous':true, 'disabled':(current_page === 1)}"
              >
                <a
                  tabindex="0"
                  class="page-link"
                  @click="getResult(current_page - 1)"
                >Previous</a>
              </li>
              <template v-for="page in last_page">
                <li
                  v-if="Math.abs(page - current_page) < 3"
                  :class="{'paginate_button': true, 'page-item':true, 'active':(page === current_page)}"
                >
                  <a
                    tabindex="0"
                    class="page-link"
                    @click="getResult(page)"
                  >{{ page }}</a>
                </li>
              </template>
              <li
                id="table1_next"
                :class="{'paginate_button': true, 'page-item':true, 'next':true, 'disabled':(current_page === last_page)}"
              >
                <a
                  tabindex="0"
                  class="page-link"
                  @click="getResult(current_page + 1)"
                >Next</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row be-datatable-footer">
        <!--<div class="col-sm-5 d-flex align-items-center flex-wrap" id="bottomAdjust">-->
        <!---->
        <!---->
        <!--</div>-->
        <div
          id="adjustPagination"
          class="col-sm-7"
        />
      </div>
    </div>
  </div>
</template>

<script>
    import _ from 'lodash'
    export default {

        props: {


            data: {
                type: Array,
                default: () => []
            },
            columns: {
                type: Array,
                default: () => []
            },
            ajax: {
                type: Object,
                default: () => {
                }
            },
            paging: {
                type: Boolean,
                default: () => true
            },
            scrollX: {
                type: Boolean,
                default: () => false
            },
            scrollY: {
                type: Boolean,
                default: () => false
            },
            select: {
                type: Boolean,
                default: () => false
            },
            search: {
                type: Boolean,
                default: () => false
            },
            sorting: {
                type: Boolean,
                default: () => true
            },
            addLink: {
                type: String,
                default: () => ''
            },
            tableHeadline:{
                type:String,
                default:()=>''
            },
            searchField:{
                type:String,
                default:()=>''
            }
            ,
            addButton:{
                type:Boolean,
                default:()=>true
            },
            userAddButton:{
                type:Boolean,
                default:()=>false
            },
            dateSearchFieldName:{
                type:String,
                default:()=>''
            }

        },
        data() {
            return {
                dataList: [],
                searched:false,
                reverseData:[],
                columnList: [],
                sortName: 'id',
                sortOrder: 'desc',
                current_page: 1,
                last_page: 0,
                per_page: 0,
                total: 0,
                path: '',
                from: 0,
                to: 0,
                pageLimit: 10,
                resultPerPage: 10,
                searchValue:null,
                dateOptions: {
                    format: 'DD-MM-YYYY',
                    useCurrent: true,
                },
                from_date:null,
                to_date:null,
            }
        },
        watch:{
            ajax:function(newVal) {
                this.getDataList()
            }

        },
        created(){
            this.getDataList()

        },
        methods: {

            getDataList(){
                if (_.isEmpty(this.ajax)) {
                    this.dataList = this.data
                } else {
                    this.getResult()
                }
            },
            onClickColumn(e) {
                if (this.sorting) {

                    let sortname = $(e.target).text().trim().toLowerCase().replace(' ', '_')

                    this.sortName = sortname != 'action' ? sortname : 'id'
//                    this.sortName = e.target.id
                    if (e.target.classList[0] === 'sorting') {
                        e.target.classList.remove('sorting')
                        e.target.classList.add('sorting_asc')
                        this.sortOrder = 'asc'
                    } else if (e.target.classList[0] === 'sorting_asc') {
                        e.target.classList.remove('sorting_asc')
                        e.target.classList.add('sorting_desc')
                        this.sortOrder = 'desc'
                    } else if (e.target.classList[0] === 'sorting_desc') {
                        e.target.classList.remove('sorting_desc')
                        e.target.classList.add('sorting_asc')
                        this.sortOrder = 'asc'
                    }
                    this.removeAllSorting(e.target.parentElement.childNodes, e)
                    this.getResult()
                }
            },
            removeAllSorting(childList, e) {
                for (var i = 0; i < childList.length; i++) {
                    if (childList[i].id === e.target.id) continue
                    childList[i].classList.remove('sorting_asc')
                    childList[i].classList.remove('sorting_desc')
                    childList[i].classList.add('sorting')
                }
            },
            getResult(page = 1,serachFied,fromDate,toDate) {
                this.current_page = page
                if (!_.isEmpty(this.ajax)) {
                    axios.get(this.ajax.url, {
                        params: {
                            sortName: this.sortName=='status'?'is_active':this.sortName,
                            sortOrder: this.sortOrder,
                            paging: this.paging,
                            page: page,
                            resultPerPage: this.resultPerPage,
                            searchKey: this.searchField,
                            searchValue: this.searchValue,
                            fromDate: fromDate,
                            toDate: toDate,
                            fieldName: serachFied,
                        }
                    }).then(response => {
                        if (this.paging) {
                            this.dataList = response.data
                            this.reverseData = (response.data).slice().reverse()
                            var totalAmount=response.data.previous_balance
                            var totalDueAmount=response.data.previous_due_balance
                            var i=1
                            this.reverseData.forEach(value=>{
                                if(value.amount){
                                    var amount=Number(value.amount)

                                    if(value.type=='employeeliabilityAp'){
                                        totalAmount=totalAmount-amount
                                        totalDueAmount=totalDueAmount-amount
                                    }else {
                                        if(value.type=='fixed-asset'){
                                            if(value.transaction_type =='Initial' || value.transaction_type=='Purchase'){
                                                totalAmount=totalAmount+amount
                                            }else{
                                                totalAmount=totalAmount-amount
                                            }

                                        }else{
                                            if(value.type=='long-term-liabilities'){
                                                if(value.transaction_type =='Initial' || value.transaction_type=='Receive'){
                                                    totalAmount=totalAmount+amount
                                                }else{
                                                    totalAmount=totalAmount-amount
                                                }

                                            }else{
                                                if(value.type=='assetAr'){
                                                    if(value.transaction_type=='Payment'){
                                                        totalAmount=totalAmount+amount
                                                    }else{
                                                        totalAmount=totalAmount-amount
                                                    }
                                                }else{
                                                    if(value.type=='liabilityAp'){

                                                        if(value.transaction_type=='Payment'){
                                                            totalAmount=totalAmount-amount
                                                        }else{
                                                            totalAmount=totalAmount+amount
                                                        }

                                                    }else{
                                                        totalAmount=totalAmount+amount

                                                    }
                                                }
                                                if(value.payment_type=='Accounts Payable'){
                                                    totalDueAmount=totalDueAmount+amount
                                                }
                                            }

                                        }


                                    }

                                    this.dataList[this.dataList.length-i]['total_amount']=totalAmount
                                    this.dataList[this.dataList.length-i]['total_due_mount']=totalDueAmount
                                    i++
                                }
                            })
                            this.dataList[this.dataList.length]={account_id: null,amount:null,date: 'balance',description: "Previous Balance",id: null,payment_type: ""
                                ,ref: "",type: "",total_amount:response.data.previous_balance,total_due_mount:response.data.previous_balance}
                            this.current_page = response.data.current_page
                            this.last_page = response.data.last_page
                            this.per_page = response.data.per_page
                            this.total = response.data.total
                            this.path = response.data.path
                            this.from = response.data.from
                            this.to = response.data.to
                        } else {
                            this.dataList = response.data
                        }
                    });

                }
            },
            onChangePerPage() {
                this.resultPerPage = document.getElementById('resultPerPage').value;
                this.getResult()
            },
            assainSearchValue(){
                this.getResult(null,this.dateSearchFieldName,this.from_date,this.to_date)
            },
            printMe(){
                window.print()
            },
        }
    }
</script>


