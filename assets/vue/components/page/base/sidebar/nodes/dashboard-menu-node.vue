<!-- Template -->
<template>
  <single-menu-node
      :shown-text="dashboardTranslationString"
      feathers-icon-name="sliders"
      :to-path-name="routeNameModuleDashboardOverview"
  />
</template>

<!-- Script -->
<script type="ts">
import SymfonyRoutes           from "../../../../../../scripts/core/symfony/SymfonyRoutes";
import TranslationsService     from "../../../../../../scripts/core/service/TranslationsService";
import SingleMenuNodeComponent from "../single-menu-node.vue";

let translationsService = new TranslationsService();

export default {
  data(){
    return{
      routeNameModuleDashboardOverview    : SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
      dashboardTranslationString          : translationsService.getTranslationForString('sidebar.menuNodes.dashboard.label'),
    }
  },
  components: {
    "single-menu-node" : SingleMenuNodeComponent,
  }
}
</script>