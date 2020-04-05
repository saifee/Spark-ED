<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.behaviour') }}
            <span
              v-if="batches.total"
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.total_result_found',{count : batches.total, from: batches.from, to: batches.to}) }}</span>
            <span
              v-else
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.no_result_found') }}</span>
          </h3>
        </div>
      </div>
    </div>
    <v-container>
      <v-row>
        <v-col cols="12">
          <v-row v-if="batches.total">
            <v-col
              v-for="(batch, i) in batches.data"
              :key="`batch${i}`"
              cols="3"
            >
              <v-card
                class="text-center p"
                @click="$router.push(`/student/behaviour/${batch.id}`)"
              >
                <v-card-text>
                  <div class="py-4">
                    <v-avatar
                      color="primary"
                      :size="60"
                    >
                      <v-icon dark>
                        grade
                      </v-icon>
                    </v-avatar>
                  </div>
                  <div
                    class="title"
                    v-text="`${batch.course.name} ${batch.name}`"
                  />
                  <div
                    class="subtitle-1"
                    v-text="`${batch.student_records_count} Students`"
                  />
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
    <module-info
      v-if="!batches.total"
      module="academic"
      title="batch_module_title"
      description="batch_module_description"
      icon="list"
    />
    <pagination-record
      :page-length.sync="filter.page_length"
      :records="batches"
      @updateRecords="getBatches"
    />
  </div>
  </div>
</template>

<script>
    export default {
        filters: {
          moment(date) {
            return helper.formatDate(date);
          },
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        data() {
            return {
                batches: {
                    total: 0,
                    data: []
                },
                filter: {
                    sort_by : 'name',
                    order: 'asc',
                    course_id: [],
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'name',
                        translation: i18n.academic.batch_name
                    }
                ],
                courses: [],
                selected_courses: null,
                showFilterPanel: false,
            };
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getBatches();
            },
            'filter.order': function(val){
                this.getBatches();
            },
            'filter.page_length': function(val){
                this.getBatches();
            }
        },
        mounted(){
            if(!helper.hasPermission('list-batch')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getBatches();
            helper.showDemoNotification(['academic']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getBatches(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/batch?page=' + page + url)
                    .then(response => {
                        this.batches = response.batches;
                        this.courses = response.filters.courses;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editBatch(batch){
                this.$router.push('/academic/batch/'+batch.id+'/edit');
            },
            confirmDelete(batch){
                return dialog => this.deleteBatch(batch);
            },
            deleteBatch(batch){
                let loader = this.$loading.show();
                axios.delete('/api/batch/'+batch.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getBatches();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/batch/print',{filter: this.filter})
                    .then(response => {
                        let print = window.open("/print");
                        loader.hide();
                        print.document.write(response);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            pdf(){
                let loader = this.$loading.show();
                axios.post('/api/batch/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            onCourseSelect(selectedOption){
                this.filter.course_id.push(selectedOption.id);
            },
            onCourseRemove(removedOption){
                this.filter.course_id.splice(this.filter.course_id.indexOf(removedOption.id), 1);
            }
        }
    }
</script>
