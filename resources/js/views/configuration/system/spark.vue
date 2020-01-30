<template>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <label for="">{{trans('configuration_spark.replace_sidebar_menu_with_top_menu')}}</label>
                <div>
                    <switches class="" v-model="configForm.replace_sidebar_menu_with_top_menu" theme="bootstrap" color="success"></switches>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <v-switch v-model="configForm.sidebar_clipped" :label="trans('configuration_spark.sidebar_clipped')">
                </v-switch>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            configForm: {
                required: false
            }
        },
    }
</script>
