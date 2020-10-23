<template>
  <div>
    <v-toolbar class="elevation-0">
      <v-toolbar-title>{{ tableHeadline }}</v-toolbar-title>
      <v-spacer />
      <div
        v-if="dateSearch"
        class="search-container mr-2"
      >
        <datepicker
          v-model="from_date"
          :bootstrap-styling="true"
          :config="dateOptions"
        />
      </div>
      <div
        v-if="dateSearch"
        class="search-container mr-2"
      >
        <datepicker
          v-model="to_date"
          :bootstrap-styling="true"
          :config="dateOptions"
        />
      </div>
      <div
        v-if="dateSearch"
        class="search-container mr-2"
      >
        <v-btn
          @click.prevent="assainSearchValue()"
        >
          Search
        </v-btn>
      </div>
      <v-text-field
        v-if="search"
        v-model="searchValue"
        single-line
        hide-details
        append-icon="search"
        placeholder="Search.."
        @keyup="getResult(null,'created_at',from_date,to_date)"
      />
      <v-spacer />
      <v-btn
        :to="{name:addLink}"
        color="primary"
        depressed
      >
        Add
      </v-btn>
      <v-icon
        @click.prvent="printMe()"
      >
        print
      </v-icon>
      <v-icon
        @click.prevent="$root.downloadExcel('table1')"
      >
        table_view
      </v-icon>
    </v-toolbar>
    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
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
          <div class="v-data-table">
            <div class="v-data-table__wrapper">
              <table
                id="table1"
                role="grid"
                aria-describedby="table1_info"
              >
                <thead class="v-data-table-header">
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
        </div>
      </div>
      <div
        v-else
        class="center-screen"
      />
      <v-toolbar class="elevation-0">
        <v-select
          id="resultPerPage"
          v-model="resultPerPage"
          outlined
          dense
          hide-details
          prefix="Show"
          suffix="Entries"
          :items="['10', '20', '50', '100', '200', 'All',]"
          @change="onChangePerPage"
        />
        <div>
          ( Showing {{ from }} to
          {{ to }}
          of {{ total }} )
        </div>
        <v-spacer />
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
      </v-toolbar>
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
    import { VSelect } from 'vuetify/lib'
    export default {
        components: {
          VSelect,
        },

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
                resultPerPage: '10',

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
                            this.current_page = response.current_page
                            this.last_page = response.last_page
                            this.per_page = response.per_page
                            this.total = response.total
                            this.path = response.path
                            this.from = response.from
                            this.to = response.to
                        } else {
                            this.dataList = response.data
                        }
                    });
                }
            },
            onChangePerPage() {
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


