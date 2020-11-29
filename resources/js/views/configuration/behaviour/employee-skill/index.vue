<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.employee_skill') }}
            <span
              v-if="employee_skills.total"
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.total_result_found',{count : employee_skills.total, from: employee_skills.from, to: employee_skills.to}) }}</span>
            <span
              v-else
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.no_result_found') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              v-if="employee_skills.total && !showCreatePanel"
              v-tooltip="trans('general.add_new')"
              class="btn btn-info btn-sm"
              @click="showCreatePanel = !showCreatePanel"
            >
              <i class="fas fa-plus" /> <span class="d-none d-sm-inline">{{ trans('behaviour.add_new_employee_skill') }}</span>
            </button>
            <sort-by
              :order-by-options="orderByOptions"
              :sort-by="filter.sort_by"
              :order="filter.order"
              @updateSortBy="value => {filter.sort_by = value}"
              @updateOrder="value => {filter.order = value}"
            />
            <div class="btn-group">
              <button
                id="moreOption"
                v-tooltip="trans('general.more_option')"
                type="button"
                class="btn btn-info btn-sm dropdown-toggle no-caret "
                role="menu"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="fas fa-ellipsis-h" /> <span class="d-none d-sm-inline" />
              </button>
              <div
                :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']"
                aria-labelledby="moreOption"
              >
                <button
                  class="dropdown-item custom-dropdown"
                  @click="print"
                >
                  <i class="fas fa-print" /> {{ trans('general.print') }}
                </button>
                <button
                  class="dropdown-item custom-dropdown"
                  @click="pdf"
                >
                  <i class="fas fa-file-pdf" /> {{ trans('general.generate_pdf') }}
                </button>
                <button
                  class="dropdown-item custom-dropdown"
                  @click="$router.push('/configuration/behaviour/skill/icon')"
                >
                  <i class="fas fa-icons" /> {{ trans('behaviour.skill_icon') }}
                </button>
              </div>
            </div>
            <help-button @clicked="help_topic = 'configuration.behaviour.employee_skill'" />
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <transition name="fade">
        <div
          v-if="showCreatePanel"
          class="card card-form"
        >
          <div class="card-body">
            <h4 class="card-title">
              {{ trans('behaviour.add_new_employee_skill') }}
            </h4>
            <employee-skill-form
              @completed="getEmployeeSkills"
              @cancel="showCreatePanel = !showCreatePanel"
            />
          </div>
        </div>
      </transition>
      <div class="card">
        <div class="card-body">
          <div
            v-if="employee_skills.total"
            class="table-responsive"
          >
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>{{ trans('behaviour.employee_skill_name') }}</th>
                  <th>{{ trans('behaviour.employee_skill_points') }}</th>
                  <th class="table-option">
                    {{ trans('general.action') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="employee_skill in employee_skills.data">
                  <td v-text="employee_skill.name" />
                  <td v-text="employee_skill.points" />
                  <td class="table-option">
                    <div class="btn-group">
                      <button
                        v-tooltip="trans('behaviour.edit_employee_skill')"
                        class="btn btn-info btn-sm"
                        @click.prevent="editEmployeeSkill(employee_skill)"
                      >
                        <i class="fas fa-edit" />
                      </button>
                      <button
                        :key="employee_skill.id"
                        v-confirm="{ok: confirmDelete(employee_skill)}"
                        v-tooltip="trans('behaviour.delete_employee_skill')"
                        class="btn btn-danger btn-sm"
                      >
                        <i class="fas fa-trash" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <module-info
            v-if="!employee_skills.total"
            module="behaviour"
            title="employee_skill_module_title"
            description="employee_skill_module_description"
            icon="list"
          >
            <div slot="btn">
              <button
                v-if="!showCreatePanel"
                class="btn btn-info btn-md"
                @click="showCreatePanel = !showCreatePanel"
              >
                <i class="fas fa-plus" /> {{ trans('general.add_new') }}
              </button>
            </div>
          </module-info>
          <pagination-record
            :page-length.sync="filter.page_length"
            :records="employee_skills"
            @updateRecords="getEmployeeSkills"
            @change.native="getEmployeeSkills"
          />
        </div>
      </div>
    </div>
    <right-panel :topic="help_topic" />
  </div>
</template>


<script>
    import employeeSkillForm from './form'

    export default {
        components : { employeeSkillForm },
        filters: {
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        data() {
            return {
                employee_skills: {
                    total: 0,
                    data: []
                },
                filter: {
                    sort_by: 'name',
                    order: 'asc',
                    page_length: helper.getConfig('page_length')
                },
                orderByOptions: [
                    {
                        value: 'name',
                        translation: i18n.behaviour.employee_skill_name
                    }
                ],
                showCreatePanel: false,
                help_topic: ''
            };
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        },
        watch: {
            'filter.sort_by': function(val){
                this.getEmployeeSkills();
            },
            'filter.order': function(val){
                this.getEmployeeSkills();
            },
            'filter.page_length': function(val){
                this.getEmployeeSkills();
            }
        },
        mounted(){
            if(!helper.hasPermission('access-configuration')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getEmployeeSkills();
        },
        methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
            getEmployeeSkills(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/behaviour/employee-skill?page=' + page + url)
                    .then(response => {
                        this.employee_skills = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editEmployeeSkill(employee_skill){
                this.$router.push('/configuration/behaviour/skill/employee/'+employee_skill.id+'/edit');
            },
            confirmDelete(employee_skill){
                return dialog => this.deleteEmployeeSkill(employee_skill);
            },
            deleteEmployeeSkill(employee_skill){
                let loader = this.$loading.show();
                axios.delete('/api/behaviour/employee-skill/'+employee_skill.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getEmployeeSkills();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/behaviour/employee-skill/print',{filter: this.filter})
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
                axios.post('/api/behaviour/employee-skill/pdf',{filter: this.filter})
                    .then(response => {
                        loader.hide();
                        window.open('/download/report/'+response+'?token='+this.authToken);
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
