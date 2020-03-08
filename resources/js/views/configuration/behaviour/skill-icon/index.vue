<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.skill_icon')}}
                        <span class="card-subtitle d-none d-sm-inline" v-if="skill_icons.total">{{trans('general.total_result_found',{count : skill_icons.total, from: skill_icons.from, to: skill_icons.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="skill_icons.total && !showCreatePanel" @click="showCreatePanel = !showCreatePanel" v-tooltip="trans('general.add_new')"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline">{{trans('behaviour.add_new_skill_icon')}}</span></button>
                        <sort-by :order-by-options="orderByOptions" :sort-by="filter.sort_by" :order="filter.order" @updateSortBy="value => {filter.sort_by = value}"  @updateOrder="value => {filter.order = value}"></sort-by>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle no-caret " role="menu" id="moreOption" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-tooltip="trans('general.more_option')">
                                <i class="fas fa-ellipsis-h"></i> <span class="d-none d-sm-inline"></span>
                            </button>
                            <div :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']" aria-labelledby="moreOption">
                                <button class="dropdown-item custom-dropdown" @click="print"><i class="fas fa-print"></i> {{trans('general.print')}}</button>
                                <button class="dropdown-item custom-dropdown" @click="pdf"><i class="fas fa-file-pdf"></i> {{trans('general.generate_pdf')}}</button>
                            </div>
                        </div>
                        <help-button @clicked="help_topic = 'configuration.behaviour.skill-icon'"></help-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <transition name="fade">
                <div class="card card-form" v-if="showCreatePanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('behaviour.add_new_skill_icon')}}</h4>
                        <skill-icon-form @completed="getSkillIcons" @cancel="showCreatePanel = !showCreatePanel"></skill-icon-form>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="skill_icons.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{trans('behaviour.skill_icon_name')}}</th>
                                    <th>{{trans('behaviour.skill_icon_description')}}</th>
                                    <th class="table-option">{{trans('general.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="skill_icon in skill_icons.data">
                                    <td v-text="skill_icon.name"></td>
                                    <td v-text="skill_icon.description"></td>
                                    <td class="table-option">
                                        <div class="btn-group">
                                            <button class="btn btn-info btn-sm" v-tooltip="trans('behaviour.edit_skill_icon')" @click.prevent="editSkillIcon(skill_icon)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" :key="skill_icon.id" v-confirm="{ok: confirmDelete(skill_icon)}" v-tooltip="trans('behaviour.delete_skill_icon')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!skill_icons.total" module="behaviour" title="skill_icon_module_title" description="skill_icon_module_description" icon="list">
                        <div slot="btn">
                            <button class="btn btn-info btn-md" v-if="!showCreatePanel" @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i> {{trans('general.add_new')}}</button>
                        </div>
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="skill_icons" @updateRecords="getSkillIcons" @change.native="getSkillIcons"></pagination-record>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
    </div>
</template>


<script>
    import skillIconForm from './form'

    export default {
        components : { skillIconForm },
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
        },
        filters: {
          momentDateTime(date) {
            return helper.formatDateTime(date);
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
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
    }
</script>
