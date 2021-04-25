<!-- Template -->
<template>
  <main class="d-flex w-100 h-100">
    <div class="container d-flex flex-column">
      <div class="row vh-100">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
          <div class="d-table-cell align-middle">

            <div class="text-center mt-4">
              <h1 class="h2">{{ trans('pages.security.login.form.header.main') }}</h1>
              <p class="lead">
                {{ trans('pages.security.login.form.header.sub') }}
              </p>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="m-sm-4">
                  <form>
                    <div class="mb-3">
                      <label class="form-label">{{ trans('pages.security.login.form.inputs.username.label') }}</label>
                      <input class="form-control form-control-lg"
                             type="text"
                             name="username"
                             :placeholder="trans('pages.security.login.form.inputs.username.placeholder')"
                             ref="usernameInput"
                             @keypress.enter="loginFormSubmitted"
                      >
                    </div>
                    <div class="mb-3">
                      <label class="form-label">{{ trans('pages.security.login.form.inputs.password.label') }}</label>
                      <input class="form-control form-control-lg"
                             type="password"
                             name="password"
                             :placeholder="trans('pages.security.login.form.inputs.password.placeholder')"
                             ref="passwordInput"
                             @keypress.enter="loginFormSubmitted"
                      >
                    </div>

                    <div class="text-center mt-3">
                      <a href="#" class="btn btn-lg btn-primary" @click="loginFormSubmitted">{{ trans('pages.security.login.form.buttons.login') }}</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<!-- Script -->
<script>
import SymfonyRoutes       from "../../../../scripts/core/symfony/SymfonyRoutes";
import ToastifyService     from "../../../../scripts/libs/toastify/ToastifyService";
import TranslationsService from "../../../../scripts/core/service/TranslationsService";
import StringUtils         from "../../../../scripts/core/utils/StringUtils";
import SpinnerService      from "../../../../scripts/core/service/SpinnerService";

import LoggedInUserDataDto from "../../../../scripts/core/dto/LoggedInUserDataDto";
import LocalStorageService from "../../../../scripts/core/service/LocalStorageService";

let translationService = new TranslationsService();

export default {
  methods: {
    /**
     * @description handles the login form submission
     */
    loginFormSubmitted(){
      let data = {
        username : this.$refs.usernameInput.value ?? "",
        password : this.$refs.passwordInput.value ?? "",
      }

      /** @var BaseInternalApiResponseDto baseApiResponse */
      SpinnerService.showSpinner();
      this.postWithCsrf(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_LOGIN), data).then( (baseApiResponse) => {
        SpinnerService.hideSpinner();

        if(baseApiResponse.success){
          ToastifyService.showGreenNotification(baseApiResponse.message)

          if( StringUtils.isEmptyString(baseApiResponse.data.redirectRouteName) ){
            ToastifyService.showRedNotification(translationService.getTranslationForString('general.responseCodes.500'))
            return;
          }

          // this must be handled without Vue as the login page contains different base component (blank)
          location.href = SymfonyRoutes.getPathForName(baseApiResponse.data.redirectRouteName);
        }else{
          ToastifyService.showRedNotification(baseApiResponse.message);
        }

      }).catch( (response) => {
        SpinnerService.hideSpinner();
        ToastifyService.showRedNotification(translationService.getTranslationForString('general.responseCodes.500'))
        console.warn(response);
      });
    },
    /**
     * @description will check if user should be able to access the login page
     *              - if not logged in then yes,
     *              - if logged in then go to dashboard,
     */
    checkLoginPageAccessAttempt(){
      SpinnerService.showSpinner();
      // check if user is logged in, and if yes then go to dashboard
      this.axios.get(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_LOGGED_IN_USER_DATA)).then( response => {

        let loggedInUserDataDto = LoggedInUserDataDto.fromAxiosResponse(response);
        if(!loggedInUserDataDto.success){
          SpinnerService.hideSpinner();
          ToastifyService.showRedNotification(translationService.getTranslationForString('general.responseCodes.500'))
          return;
        }

        if(loggedInUserDataDto.loggedIn){
          // already logged in and tries to enter login page
          if( LocalStorageService.isLoggedInUserSet() ){
            ToastifyService.showOrangeNotification(translationService.getTranslationForString('security.login.messages.alreadyLoggedIn'))
          }else{
            LocalStorageService.setLoggedInUser(loggedInUserDataDto);
          }

          // let user read the message
          setTimeout(() => {
            // this must be handled without Vue as the login page contains different base component (blank)
            location.href = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW);
          }, 1000)
          return;
        }

        SpinnerService.hideSpinner();
      }).catch( (response) => {
        SpinnerService.hideSpinner();
        ToastifyService.showRedNotification(translationService.getTranslationForString('general.responseCodes.500'))
        console.warn(response);
      });
    }
  },
  beforeMount(){
    this.checkLoginPageAccessAttempt();
  }
}
</script>