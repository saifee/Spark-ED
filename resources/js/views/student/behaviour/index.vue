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
            };
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        },
        mounted(){
            this.getBatches();
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
                axios.get('/api/batch?page=' + page)
                    .then(response => {
                        this.batches = response.batches;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
        }
    }
</script>
