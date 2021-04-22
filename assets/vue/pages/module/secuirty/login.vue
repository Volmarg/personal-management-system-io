<!-- Template -->
<template>
  <main class="d-flex w-100 h-100">
    <div class="container d-flex flex-column">
      <div class="row vh-100">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
          <div class="d-table-cell align-middle">

            <div class="text-center mt-4">
              <h1 class="h2">{{ mainHeaderTranslatedString }}</h1>
              <p class="lead">
                {{ subHeaderTranslatedString }}
              </p>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="m-sm-4">
                  <form>
                    <div class="mb-3">
                      <label class="form-label">{{ usernameInputLabelTranslatedString }}</label>
                      <input class="form-control form-control-lg"
                             type="text"
                             name="username"
                             :placeholder="usernameInputPlaceholderTranslatedString"
                             ref="usernameInput"
                      >
                    </div>
                    <div class="mb-3">
                      <label class="form-label">{{ passwordInputLabelTranslatedString }}</label>
                      <input class="form-control form-control-lg"
                             type="password"
                             name="password"
                             :placeholder="passwordInputPlaceholderTranslatedString"
                             ref="passwordInput"
                      >
                    </div>

                    <div class="text-center mt-3">
                      <a href="#" class="btn btn-lg btn-primary" @click="loginFormSubmitted">{{ singInButtonTranslatedString }}</a>
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

import TranslationsService from "../../../../scripts/core/service/TranslationsService";
import SymfonyRoutes       from "../../../../scripts/core/symfony/SymfonyRoutes";

let translationsService = new TranslationsService();

export default {
  data(){
    return {
      mainHeaderTranslatedString               : translationsService.getTranslationForString("pages.security.login.form.header.main"),
      subHeaderTranslatedString                : translationsService.getTranslationForString("pages.security.login.form.header.sub"),
      usernameInputLabelTranslatedString       : translationsService.getTranslationForString("pages.security.login.form.inputs.username.label"),
      usernameInputPlaceholderTranslatedString : translationsService.getTranslationForString("pages.security.login.form.inputs.username.placeholder"),
      passwordInputLabelTranslatedString       : translationsService.getTranslationForString("pages.security.login.form.inputs.password.label"),
      passwordInputPlaceholderTranslatedString : translationsService.getTranslationForString("pages.security.login.form.inputs.password.placeholder"),
      singInButtonTranslatedString             : translationsService.getTranslationForString("pages.security.login.form.buttons.login"),
    }
  },
  methods: {
    /**
     * @description handles the login form submission
     */
    loginFormSubmitted(){
      let data = {
        username : this.$refs.usernameInput.value,
        password : this.$refs.passwordInput.value,
      }
      // todo: handle response
      this.postWithCsrf(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_HANDLE_LOGIN), data);
    }
  },
}
</script>