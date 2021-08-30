<!-- Template -->
<template>
  <nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle d-flex">
      <i class="hamburger align-self-center"></i>
    </a>

    <router-link class="search-icon-wrapper"
                 :to="{name: searchPageRouteName}"
    >
      <i class="align-middle" data-feather="search"></i>
    </router-link>

    <router-link class="home-icon-wrapper"
                 :to="{name: dashboardRouteName}"
    >
      <i class="align-middle" data-feather="home"></i>
    </router-link>

    <div class="navbar-collapse collapse">
      <ul class="navbar-nav navbar-align">
        <li class="nav-item dropdown">
          <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings align-middle"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          </a>

          <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
            <!-- <img src="img/avatar.png" class="avatar img-fluid rounded me-1" alt="Charles Hall">  --> <span class="text-dark">{{ loggedInUserDto?.shownName ?? "" }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="#" @click="logoutClicked">{{ trans('topbar.menu.logout') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import BaseApiDto from "../../../../scripts/core/dto/BaseApiDto";

import LocalStorageService from "../../../../scripts/core/service/LocalStorageService";
import SymfonyRoutes       from "../../../../scripts/core/symfony/SymfonyRoutes";
import ToastifyService     from "../../../../scripts/libs/toastify/ToastifyService";
import TranslationsService from "../../../../scripts/core/service/TranslationsService";

let translationService = new TranslationsService();

export default {
  data(){
    return{
      loggedInUserDto     : LocalStorageService.getLoggedInUser(),
      searchPageRouteName : SymfonyRoutes.ROUTE_NAME_MODULE_SEARCH_SEARCH_OVERVIEW,
      dashboardRouteName  : SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
    }
  },
  methods: {
    /**
     * @description handles the logout click
     */
    logoutClicked(){
      this.postWithCsrf(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_INVALIDATE_USER)).then( baseResponse => {

        if(baseResponse.success){
          ToastifyService.showGreenNotification(translationService.getTranslationForString('security.logout.messages.loggingOut'))

          // this must be handled without Vue as the login page contains different base component (blank)
          location.href = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_LOGIN);
        }else{
          ToastifyService.showOrangeNotification(translationService.getTranslationForString('security.logout.messages.couldNotLogOut'))
        }

      }).catch( baseResponse => {
        ToastifyService.showRedNotification(translationService.getTranslationForString('general.responseCodes.500'))
        console.warn(baseResponse);
      })

    }
  }
};
</script>

<!-- Style -->
<style scoped>
.search-icon-wrapper svg, .home-icon-wrapper  svg {
  height: 25px;
  width: 25px;
}

.home-icon-wrapper {
  margin-left: 10px;
}
</style>