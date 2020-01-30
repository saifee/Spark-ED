<template>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <v-switch v-model="configForm.replace_sidebar_menu_with_top_menu" :label="trans('configuration_spark.replace_sidebar_menu_with_top_menu')" color="success" />
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <v-switch v-model="configForm.sidebar_clipped" :label="trans('configuration_spark.sidebar_clipped')" color="success" />
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
