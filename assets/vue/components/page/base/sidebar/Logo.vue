<!-- Template -->
<template>
  <router-link :to="{ name: dashboardRouteName }"
               class="base-component position-absolute logo"
    >
      <img src="/logo.svg" style=""/>
  </router-link>
</template>

<!-- Script -->
<script>
import SymfonyRoutes from "../../../../../scripts/core/symfony/SymfonyRoutes";

export default {
  data(){
    return {
      dashboardRouteName: SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
    }
  }
}
</script>

<!-- Style -->
<style scoped>
  .logo {
    top: 14px;
    margin-left: -2px;
    margin-bottom: 10px;
  }

  .logo img {
    width: 49px;
    height: 49px;
  }
</style>