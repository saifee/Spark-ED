<template>
  <v-app>
    <v-main class="grey lighten-4">
      <v-container fluid>
        <v-row
          justify="center"
          align="center"
          no-gutters
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-card-text>
                <div class="text-center">
                  <img
                    :src="getLogo"
                    class="org-logo"
                  >
                </div>
                <form
                  id="loginform"
                  @keydown="loginForm.errors.clear($event.target.name)"
                >
                  <h3 class="box-title m-t-20 m-b-10">
                    {{ trans('auth.login') }}
                  </h3>
                  <social-login v-if="getConfig('made') === 'saudi'" />
                  <div v-if="! login_with_otp">
                    <v-text-field
                      v-model="loginForm.email_or_username"
                      color="primary"
                      type="text"
                      name="email_or_username"
                      :label="trans('auth.email_or_username')"
                      :persistent-hint="loginForm.errors.has('email_or_username')"
                      :error="loginForm.errors.has('email_or_username')"
                      :hint="loginForm.errors.get('email_or_username')"
                      outlined
                      @keyup.enter="process"
                    />
                    <v-text-field
                      v-model="loginForm.password"
                      color="primary"
                      type="password"
                      name="password"
                      :label="trans('auth.password')"
                      :persistent-hint="loginForm.errors.has('password')"
                      :error="loginForm.errors.has('password')"
                      :hint="loginForm.errors.get('password')"
                      outlined
                      @keyup.enter="process"
                    />
                  </div>
                  <div v-else>
                    <div class="form-group">
                      <input
                        v-if="!otp_generated"
                        v-model="loginForm.mobile"
                        type="text"
                        name="mobile"
                        class="form-control"
                        :placeholder="trans('auth.mobile')"
                      >
                      <label v-if="otp_generated">{{ loginForm.mobile }}</label>
                      <show-error
                        :form-name="loginForm"
                        prop-name="mobile"
                      />
                    </div>
                    <div
                      v-if="otp_generated"
                      class="form-group"
                    >
                      <input
                        v-model="loginForm.otp"
                        type="text"
                        name="otp"
                        class="form-control"
                        :placeholder="trans('auth.otp')"
                      >
                      <show-error
                        :form-name="loginForm"
                        prop-name="otp"
                      />
                    </div>
                  </div>
                  <div
                    v-if="getConfig('recaptcha') && getConfig('login_recaptcha')"
                    class="g-recaptcha"
                    :data-sitekey="getConfig('recaptcha_key')"
                  />
                  <v-btn
                    block
                    x-large
                    dark
                    color="primary"
                    @click="process"
                  >
                    {{ trans('auth.sign_in') }}
                  </v-btn>
                  <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                      <p v-if="getConfig('reset_password')">
                        {{ trans('auth.forgot_your_password?') }} <router-link
                          to="/password"
                          class="text-info m-l-5"
                        >
                          <b>{{ trans('auth.reset_here!') }}</b>
                        </router-link>
                      </p>
                    </div>
                    <template v-if="getConfig('login_with_otp')">
                      <div
                        v-if="!login_with_otp"
                        class="col-sm-12 text-center"
                      >
                        <p>
                          <a
                            href="#"
                            @click="otpLogin(true)"
                          >{{ trans('auth.login_with_otp') }}</a>
                        </p>
                      </div>
                      <div
                        v-if="login_with_otp"
                        class="col-sm-12 text-center"
                      >
                        <p>
                          <a
                            href="#"
                            @click="otpLogin(false)"
                          >{{ trans('auth.login_with_password') }}</a>
                        </p>
                      </div>
                    </template>
                  </div>
                  <div
                    v-if="!getConfig('mode')"
                    class="row m-t-10 justify-content-center"
                  >
                    <div class="col-6 text-center">
                      <button
                        id="loginAs"
                        type="button"
                        class="btn btn-success text-uppercase btn-block dropup"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        Auto Login As <i class="fas fa-chevron-up m-l-5" />
                      </button>
                      <div
                        :class="['dropdown-menu',getConfig('direction') == 'ltr' ? 'dropdown-menu-right' : '']"
                        aria-labelledby="loginAs"
                      >
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('admin')"
                        >
                          Admin Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('manager')"
                        >
                          Manager Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('principal')"
                        >
                          Principal Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('staff')"
                        >
                          Staff Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('accountant')"
                        >
                          Accountant Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('librarian')"
                        >
                          Librarian Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('student')"
                        >
                          Student Role
                        </button>
                        <button
                          type="button"
                          style="cursor:pointer;"
                          class="dropdown-item"
                          @click="loginAs('parent')"
                        >
                          Parent Role
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </v-card-text>
              <!-- <guest-footer></guest-footer> -->
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
    import socialLogin from '@views/auth/social-login.vue'
    import guestFooter from '@js/layouts/partials/guest-footer.vue'

    export default {
        components: {
            socialLogin,
            guestFooter
        },
        data() {
            return {
                loginForm: new Form({
                    email_or_username: '',
                    password: '',
                    mobile: '',
                    otp: ''
                }, false),
                login_with_otp: false,
                otp_generated: false
            }
        },
        computed: {
            getLogo(){
                return helper.getLogo();
            }
        },
        mounted(){
            helper.showDemoNotification(['login','login_with_different_role']);
        },
        methods: {
            otpLogin(status){
                this.login_with_otp = status;
            },
            process(){
                if (this.login_with_otp) {
                    if (!this.otp_generated)
                        this.submitOTP();
                    else
                        this.processOTP();
                } else {
                    this.submit();
                }
            },
            submitOTP(){
                let loader = this.$loading.show();
                this.loginForm.post('/api/auth/login/otp')
                    .then(response =>  {
                        this.otp_generated = true;
                        toastr.success(response.message);
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            processOTP(){
                let loader = this.$loading.show();
                this.loginForm.post('/api/auth/login/otp')
                    .then(response =>  {
                        this.$cookie.set('auth_token',response.token,1);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.token;
                        this.$store.dispatch('setConfig',response.config);
                        this.$store.dispatch('setAuthUserDetail',{
                            id: response.user.id,
                            email: response.user.email,
                            name: response.user.name,
                            username: response.user.username,
                            roles: response.user.user_roles,
                            permissions: response.user.user_permissions,
                            two_factor_code: response.user.two_factor_code,
                            color_theme: response.user.user_preference.color_theme || this.getConfig('color_theme'),
                            locale: response.user.user_preference.locale || this.getConfig('locale'),
                            direction: response.user.user_preference.direction || this.getConfig('direction'),
                            sidebar: response.user.user_preference.sidebar || this.getConfig('sidebar')
                        });
                        this.$store.dispatch('setAcademicSession',response.academic_sessions);
                        this.$store.dispatch('setDefaultAcademicSession',response.default_academic_session);
                        
                        toastr.success(response.message);
                        var redirect_path = response.reload ? '/dashboard?reload=1' : '/dashboard';

                        let role = response.user.roles.find(o => o.name == 'admin');
                        if(role && helper.getConfig('setup_wizard'))
                            redirect_path = '/setup';

                        this.$router.push(redirect_path);
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            submit(){
                let loader = this.$loading.show();
                this.loginForm.post('/api/auth/login')
                    .then(response =>  {
                        this.$cookie.set('auth_token',response.token,1);
                        axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.token;
                        this.$store.dispatch('setConfig',response.config);
                        this.$store.dispatch('setAuthUserDetail',{
                            id: response.user.id,
                            uuid: response.user.uuid,
                            email: response.user.email,
                            name: response.user.name,
                            username: response.user.username,
                            roles: response.user.user_roles,
                            permissions: response.user.user_permissions,
                            two_factor_code: response.user.two_factor_code,
                            color_theme: response.user.user_preference.color_theme || this.getConfig('color_theme'),
                            locale: response.user.user_preference.locale || this.getConfig('locale'),
                            direction: response.user.user_preference.direction || this.getConfig('direction'),
                            sidebar: response.user.user_preference.sidebar || this.getConfig('sidebar')
                        });
                        this.$store.dispatch('setAcademicSession',response.academic_sessions);
                        this.$store.dispatch('setDefaultAcademicSession',response.default_academic_session);
                        
                        toastr.success(response.message);

                        if(helper.getConfig('two_factor_security')){
                            this.$router.push('/auth/security');
                        }
                        else {
                            var redirect_path = response.reload ? '/dashboard?reload=1' : '/dashboard';

                            let role = response.user.roles.find(o => o.name == 'admin');
                            if(role && helper.getConfig('setup_wizard'))
                                redirect_path = '/setup';

                            this.$store.dispatch('resetTwoFactorCode');
                            this.$router.push(redirect_path);
                        }
                        loader.hide();
                    }).catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            loginAs(role){
                this.loginForm.email_or_username = role+'@'+role+'.com';
                this.loginForm.password = 'password';
                this.submit();
            },
            getConfig(config){
                return helper.getConfig(config);
            }
        }
    }
</script>
