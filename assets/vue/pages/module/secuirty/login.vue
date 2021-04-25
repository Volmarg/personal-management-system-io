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
                      >
                    </div>
                    <div class="mb-3">
                      <label class="form-label">{{ trans('pages.security.login.form.inputs.password.label') }}</label>
                      <input class="form-control form-control-lg"
                             type="password"
                             name="password"
                             :placeholder="trans('pages.security.login.form.inputs.password.placeholder')"
                             ref="passwordInput"
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
import SymfonyRoutes from "../../../../scripts/core/symfony/SymfonyRoutes";

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
      // todo: handle response
      this.postWithCsrf(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_LOGIN), data).then( (response) => {
        console.log(response);
      });
    }
  },
}
</script>