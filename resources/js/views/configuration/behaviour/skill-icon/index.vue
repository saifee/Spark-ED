<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.skill_icon') }}
            <span
              v-if="skill_icons.total"
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.total_result_found',{count : skill_icons.total, from: skill_icons.from, to: skill_icons.to}) }}</span>
            <span
              v-else
              class="card-subtitle d-none d-sm-inline"
            >{{ trans('general.no_result_found') }}</span>
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              v-if="skill_icons.total && !showCreatePanel"
              v-tooltip="trans('general.add_new')"
              class="btn btn-info btn-sm"
              @click="showCreatePanel = !showCreatePanel"
            >
              <i class="fas fa-plus" /> <span class="d-none d-sm-inline">{{ trans('behaviour.add_new_skill_icon') }}</span>
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
              </div>
            </div>
            <help-button @clicked="help_topic = 'configuration.behaviour.skill-icon'" />
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
              {{ trans('behaviour.add_new_skill_icon') }}
            </h4>
            <skill-icon-form
              @completed="getSkillIcons"
              @cancel="showCreatePanel = !showCreatePanel"
            />
          </div>
        </div>
      </transition>
      <div class="card">
        <div class="card-body">
          <div
            v-if="skill_icons.total"
            class="table-responsive"
          >
            <table class="table table-sm">
              <thead>
                <tr>
                  <th />
                  <th>{{ trans('behaviour.skill_icon_name') }}</th>
                  <th>{{ trans('behaviour.skill_icon_description') }}</th>
                  <th class="table-option">
                    {{ trans('general.action') }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="skill_icon in skill_icons.data">
                  <td><v-icon v-text="skill_icon.name" /></td>
                  <td v-text="skill_icon.name" />
                  <td v-text="skill_icon.description" />
                  <td class="table-option">
                    <div class="btn-group">
                      <button
                        v-tooltip="trans('behaviour.edit_skill_icon')"
                        class="btn btn-info btn-sm"
                        @click.prevent="editSkillIcon(skill_icon)"
                      >
                        <i class="fas fa-edit" />
                      </button>
                      <button
                        :key="skill_icon.id"
                        v-confirm="{ok: confirmDelete(skill_icon)}"
                        v-tooltip="trans('behaviour.delete_skill_icon')"
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
            v-if="!skill_icons.total"
            module="behaviour"
            title="skill_icon_module_title"
            description="skill_icon_module_description"
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
            :records="skill_icons"
            @updateRecords="getSkillIcons"
            @change.native="getSkillIcons"
          />
        </div>
      </div>
    </div>
    <right-panel :topic="help_topic" />
  </div>
</template>


<script>
    import skillIconForm from './form'

    export default {
        components : { skillIconForm },
        filters: {
          momentDateTime(date) {
            return helper.formatDateTime(date);
          }
        },
        data() {
            return {
                skill_icons: {
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
                        translation: i18n.behaviour.skill_icon_name
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
                this.getSkillIcons();
            },
            'filter.order': function(val){
                this.getSkillIcons();
            },
            'filter.page_length': function(val){
                this.getSkillIcons();
            }
        },
        mounted(){
            if(!helper.hasPermission('access-configuration')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }

            this.getSkillIcons();
        },
        methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
            getSkillIcons(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/behaviour/skill-icon?page=' + page + url)
                    .then(response => {
                        this.skill_icons = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editSkillIcon(skill_icon){
                this.$router.push('/configuration/behaviour/skill/icon/'+skill_icon.id+'/edit');
            },
            confirmDelete(skill_icon){
                return dialog => this.deleteSkillIcon(skill_icon);
            },
            deleteSkillIcon(skill_icon){
                let loader = this.$loading.show();
                axios.delete('/api/behaviour/skill-icon/'+skill_icon.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getSkillIcons();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/behaviour/skill-icon/print',{filter: this.filter})
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
                axios.post('/api/behaviour/skill-icon/pdf',{filter: this.filter})
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
