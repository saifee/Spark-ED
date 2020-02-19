<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.skill')}}
                        <span class="card-subtitle d-none d-sm-inline" v-if="skills.total">{{trans('general.total_result_found',{count : skills.total, from: skills.from, to: skills.to})}}</span>
                        <span class="card-subtitle d-none d-sm-inline" v-else>{{trans('general.no_result_found')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" v-if="skills.total && !showCreatePanel" @click="showCreatePanel = !showCreatePanel" v-tooltip="trans('general.add_new')"><i class="fas fa-plus"></i> <span class="d-none d-sm-inline">{{trans('behaviour.add_new_skill')}}</span></button>
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
                        <help-button @clicked="help_topic = 'configuration.behaviour.skill'"></help-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <transition name="fade">
                <div class="card card-form" v-if="showCreatePanel">
                    <div class="card-body">
                        <h4 class="card-title">{{trans('behaviour.add_new_skill')}}</h4>
                        <skill-form @completed="getSkills" @cancel="showCreatePanel = !showCreatePanel"></skill-form>
                    </div>
                </div>
            </transition>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" v-if="skills.total">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{trans('behaviour.skill_name')}}</th>
                                    <th>{{trans('academic.course')}}</th>
                                    <th>{{trans('academic.batch')}}</th>
                                    <th>{{trans('behaviour.skill_points')}}</th>
                                    <th class="table-option">{{trans('general.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="skill in skills.data">
                                    <td v-text="skill.name"></td>
                                    <td>
                                        {{skill.batch.course.name}}
                                    </td>
                                    <td>
                                        {{skill.batch.name}}
                                    </td>
                                    <td v-text="skill.points"></td>
                                    <td class="table-option">
                                        <div class="btn-group">
                                            <button class="btn btn-info btn-sm" v-tooltip="trans('behaviour.edit_skill')" @click.prevent="editSkill(skill)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" :key="skill.id" v-confirm="{ok: confirmDelete(skill)}" v-tooltip="trans('behaviour.delete_skill')"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <module-info v-if="!skills.total" module="behaviour" title="skill_module_title" description="skill_module_description" icon="list">
                        <div slot="btn">
                            <button class="btn btn-info btn-md" v-if="!showCreatePanel" @click="showCreatePanel = !showCreatePanel"><i class="fas fa-plus"></i> {{trans('general.add_new')}}</button>
                        </div>
                    </module-info>
                    <pagination-record :page-length.sync="filter.page_length" :records="skills" @updateRecords="getSkills" @change.native="getSkills"></pagination-record>
                </div>
            </div>
        </div>
        <right-panel :topic="help_topic"></right-panel>
    </div>
</template>


<script>
    import skillForm from './form'

    export default {
        components : { skillForm },
        data() {
            return {
                skills: {
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
                        translation: i18n.behaviour.skill_name
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

            this.getSkills();
        },
        methods: {
            getConfig(config){
                return helper.getConfig(config);
            },
            getSkills(page){
                let loader = this.$loading.show();
                if (typeof page !== 'number') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filter);
                axios.get('/api/behaviour/skill?page=' + page + url)
                    .then(response => {
                        this.skills = response;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            editSkill(skill){
                this.$router.push('/configuration/behaviour/skill/'+skill.id+'/edit');
            },
            confirmDelete(skill){
                return dialog => this.deleteSkill(skill);
            },
            deleteSkill(skill){
                let loader = this.$loading.show();
                axios.delete('/api/behaviour/skill/'+skill.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.getSkills();
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            print(){
                let loader = this.$loading.show();
                axios.post('/api/behaviour/skill/print',{filter: this.filter})
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
                axios.post('/api/behaviour/skill/pdf',{filter: this.filter})
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
                this.getSkills();
            },
            'filter.order': function(val){
                this.getSkills();
            },
            'filter.page_length': function(val){
                this.getSkills();
            }
        },
        computed: {
            authToken(){
                return helper.getAuthToken();
            }
        }
    }
</script>
