<template>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="">{{trans('auth_social.social_login_google')}}</label>
                        <div>
                            <switches v-model="configForm.social_login_google" theme="bootstrap" color="success"></switches>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6" v-if="configForm.social_login_google">
                    <div class="form-group">
                        <label for=""><small>{{trans('auth_social.social_login_google_client_id')}}</small></label>
                        <input class="form-control" type="text" v-model="configForm.social_login_google_client_id" name="social_login_google_client_id" :placeholder="trans('auth_social.social_login_google_client_id')">
                        <small class="form-text text-muted"><a target="_blank" href="https://developers.google.com/identity/sign-in/web/sign-in">click here</a> to get client id</small>
                        <show-error :form-name="configForm" prop-name="social_login_google_client_id"></show-error>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="form-group">
                        <label for="">{{trans('auth_social.social_login_microsoft')}}</label>
                        <div>
                            <switches v-model="configForm.social_login_microsoft" theme="bootstrap" color="success"></switches>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6" v-if="configForm.social_login_microsoft">
                    <div class="form-group">
                        <label for=""><small>{{trans('auth_social.social_login_microsoft_client_id')}}</small></label>
                        <input class="form-control" type="text" v-model="configForm.social_login_microsoft_client_id" name="social_login_microsoft_client_id" :placeholder="trans('auth_social.social_login_microsoft_client_id')">
                        <small class="form-text text-muted"><a target="_blank" href="https://docs.microsoft.com/en-us/azure/active-directory/develop/quickstart-v2-javascript">click here</a> to get client id</small>
                        <show-error :form-name="configForm" prop-name="social_login_microsoft_client_id"></show-error>
                    </div>
                </div>
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
