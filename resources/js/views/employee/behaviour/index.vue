<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.behaviour') }} 
            <span
              v-if="employees.total"
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.total_result_found',{count : employees.total, from: employees.from, to: employees.to}) }}</span>
            <span
              v-else
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.no_result_found') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
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
                <button class="dropdown-item custom-dropdown">
                  <i class="fas fa-print" /> {{ trans('behaviour.view_reports') }}
                </button>
                <button class="dropdown-item custom-dropdown">
                  <i class="fas fa-undo" /> {{ trans('behaviour.reset_bubbles') }}
                </button>
                <div class="dropdown-divider" />
                <button
                  v-for="option in avatarSizeOptions"
                  class="dropdown-item custom-dropdown"
                  @click="avatarSize = option"
                >
                  {{ option }} <span
                    v-if="option == avatarSize"
                    class="pull-right"
                  ><i class="fas fa-check" /></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <div
            v-if="employees.total"
            class="row mt-5"
          >
            <div
              v-for="employee in employees.data"
              class="col-2 text-center"
            >
              <v-badge
                overlap
                :content="employee.employee_terms[0].employee_behaviour_point ? employee.employee_terms[0].employee_behaviour_point.total : '0'"
                :color="employee.employee_terms[0].employee_behaviour_point && employee.employee_terms[0].employee_behaviour_point.total > 0 ? 'success' : !employee.employee_terms[0].employee_behaviour_point || employee.employee_terms[0].employee_behaviour_point.total == 0 ? 'warning' : 'error'"
              >
                <v-avatar
                  :size="avatarSize"
                  @click="giveFeedbackToEmployee(employee)"
                >
                  <v-img :src="getImage(employee)" />
                </v-avatar>
              </v-badge>
              <div>{{ getEmployeeName(employee) }}</div>
            </div>
          </div>
          <module-info
            v-if="!employees.total"
            module="employee"
            title="employee_module_title"
            description="employee_module_description"
            icon="check-circle"
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
            :records="employees"
            @updateRecords="getEmployees"
          />
        </div>
      </div>
    </div>
    <right-panel :topic="help_topic" />
    <transition
      v-if="showFeedbackModal"
      name="modal"
    >
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container modal-lg">
            <div class="modal-header">
              <slot name="header">
                {{ trans('behaviour.give_feedback_to_employee') }}
                <span
                  class="float-right pointer"
                  @click="showFeedbackModal = false"
                >&times;</span>
              </slot>
            </div>
            <div class="modal-body">
              <slot name="body">
                <feedback-form
                  :employee-term-id="employeeTermId"
                  @completed="giveFeedbackToEmployeeComplete"
                  @cancel="showFeedbackModal = false"
                />
                <div class="clearfix" />
              </slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>


<script>
    import feedbackForm from './feedback-form'

    export default {
        components: {feedbackForm,},
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
                employeeTermId: null,
                avatarSize: 70,
                avatarSizeOptions: [20, 30, 40, 50, 60, 70, 80, 90, 100],
                showFeedbackModal: false,
                employees: {
                    total: 0,
                    data: []
                },
                selectAll: false,
                employeeGroupForm: new Form({
                    ids: [],
                    employee_group_id: '',
                    action: 'attach'
                }),
                employee_groups: [],
                selected_group: null,
                filter: {
                    sort_by : 'code',
                    order: 'asc',
                    page_length: helper.getConfig('page_length'),
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    father_name: '',
                    department_id: [],
                    designation_id: [],
                    employee_group_id: [],
                    status: 'active'
                },
                orderByOptions: [
                    {
                        value: 'code',
                        translation: i18n.employee.code
                    },
                    {
                        value: 'first_name',
                        translation: i18n.employee.first_name
                    }
                ],
                showFilterPanel: false,
                showCreatePanel: false,
                help_topic: '',
                departments: [],
                selected_departments: null,
                designations: [],
                selected_designations: null,
                selected_employee_groups: null,
                statuses: [
                    {
                        text: i18n.employee.status_active,
                        value: 'active'
                    },
                    {
                        text: i18n.employee.status_inactive,
                        value: 'inactive'
                    }
                ]
            };
        },
        watch: {
            'filter.sort_by': function(val){
                this.getEmployees();
            },
            'filter.order': function(val){
                this.getEmployees();
            },
            'filter.page_length': function(val){
                this.getEmployees();
            }
        },
        mounted(){
            if(!helper.hasPermission('list-employee')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getEmployees();
            helper.showDemoNotification(['employee']);
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            getConfig(config) {
                return helper.getConfig(config)
            },
            giveFeedbackToEmployee(employee){
                this.employeeTermId = (employee.employee_terms.length > 0) ? employee.employee_terms[0].id : null
                this.showFeedbackModal = true
            },
            giveFeedbackToEmployeeComplete(){
                this.getEmployees()
                this.showFeedbackModal = false
            },
            getImage(employee){
                if (!employee.photo) {
                    return employee.gender == 'female' ? '/images/female.png' : '/images/male.png';
                } else {
                    return '/'+employee.photo;
                }
            },
            getEmployeeName(employee){
                return helper.getEmployeeName(employee);
            },
            getEmployeeCode(employee){
                return helper.getEmployeeCode(employee);
            },
            getEmployees(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                this.selectAll = false;
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/employee?page=' + page + url)
                    .then(response => {
                        this.employees = response.employees;
                        this.departments = response.filters.departments;
                        this.designations = response.filters.designations;
                        this.employee_categories = response.filters.employee_categories;
                        this.employee_groups = response.filters.employee_groups;
                        let ids = [];
                        this.employees.data.forEach(employee => {
                            ids.push(employee.id);
                        })
                        this.selectAll = ids.every(elem => this.employeeGroupForm.ids.indexOf(elem) > -1) ? 1 : 0;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            getStatus(employee){
                let term = employee.employee_terms;
                if (term.length && term[0].date_of_joining <= moment().format('YYYY-MM-DD') && (!term[0].date_of_leaving || term[0].date_of_leaving >= moment().format('YYYY-MM-DD')))
                    return '<span class="label label-success">'+i18n.employee.status_active+'</span>';
                else
                    return '<span class="label label-danger">'+i18n.employee.status_inactive+'</span>';
            },
        },
    }
</script>
