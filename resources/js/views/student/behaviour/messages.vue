<template>
    <div>
        <div class="page-titles">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <h3 class="text-themecolor">{{trans('behaviour.messages')}}
                        <span class="card-subtitle d-none d-sm-inline">{{trans('behaviour.messages_description')}}</span>
                    </h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="action-buttons pull-right">
                        <button class="btn btn-info btn-sm" @click="$router.push('/student/behaviour')" v-tooltip="trans('behaviour.behaviour')"><span class="d-none d-sm-inline"><i class="fas fa-gem"></i> {{trans('behaviour.behaviour')}}</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <module-info v-if="!students.total" module="behaviour" title="messages" description="messages_description" icon="list">
                    </module-info>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                students: {
                    total: 0,
                    data: []
                },
            };
        },
        mounted(){
        },
        methods: {
            hasPermission(permission){
                return helper.hasPermission(permission);
            },
            hasNotAnyRole(roles){
                return helper.hasNotAnyRole(roles);
            },
            getConfig(config){
                return helper.getConfig(config);
            },
        },
        computed:{
            authToken(){
                return helper.getAuthToken();
            }
        },
        filters: {
        },
    }
</script>
