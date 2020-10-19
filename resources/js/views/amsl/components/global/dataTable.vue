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
              <i class="fa fa-list-alt" />  {{ tableHeadline }}
            </div>
          </div>
          <div class="response_flex">
            <div
              v-if="dateSearch"
              class="search-container mr-2"
            >
              <date-picker
                v-model="from_date"
                :config="dateOptions"
              />
            </div>
            <div
              v-if="dateSearch"
              class="search-container mr-2"
            >
              <date-picker
                v-model="to_date"
                :config="dateOptions"
              />
            </div>
            <div
              v-if="dateSearch"
              class="search-container mr-2"
            >
              <button
                class="btn btn-secondary  pt-1"
                @click.prevent="assainSearchValue()"
              >
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
                @keyup="getResult(null,'created_at',from_date,to_date)"
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
                  class="fa fa-plus-square-o"
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
            <template v-if="$root.$data.company">
              <p>Contact: {{ $root.$data.company.mobile }}</p>
              <p>Address: {{ $root.$data.company.address }}</p>
            </template>
          </div>
          <table
            id="table1"
            class="table table-striped  table-fw-widget dataTable no-footer table_block"
            role="grid"
            aria-describedby="table1_info"
          >
            <thead>
              <tr role="row">
                <th
                  v-for="(column, index) in columns"
                  :id="column.field"
                  class="sorting text-center"
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
            dateSearch: {
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
                            var totalAmount=0
                            this.dataList.forEach(value=>{
                                if(value.amount){
                                    var amount=Number(value.amount)
                                    totalAmount = totalAmount + amount
                                    value['total_amount']=totalAmount.toFixed(2)
                                }
                            })
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
            printMe(){
                window.print()
            },
            assainSearchValue(){
                this.getResult(null,this.dateSearchFieldName,this.from_date,this.to_date)
            },
            getHoursAndMinute(start,end){
                var diff
                if(start && end){
                        var start=start.slice(11,16)
                        var end =  end.slice(11,16)
                       diff = start.split(':').map((item,index) => end.split(':')[index] - item).join(':')
                    }else{
                        diff='Need Both Time to Calculate'
                    }


                return diff;
            }
        }
    }
</script>

<style scoped>

    .btn{
        line-height: 25px;
    }

</style>
