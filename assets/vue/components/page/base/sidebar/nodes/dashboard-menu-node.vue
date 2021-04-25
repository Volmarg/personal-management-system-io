<!-- Template -->
<template>
  <single-menu-node
      :shown-text="trans('sidebar.menuNodes.dashboard.label')"
      feathers-icon-name="sliders"
      :to-path-name="routeNameModuleDashboardOverview"
  />
</template>

<!-- Script -->
<script type="ts">
import SymfonyRoutes           from "../../../../../../scripts/core/symfony/SymfonyRoutes";
import SingleMenuNodeComponent from "../single-menu-node.vue";

export default {
  data(){
    return{
      routeNameModuleDashboardOverview : SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
    }
  },
  components: {
    "single-menu-node" : SingleMenuNodeComponent,
  }
}
</script>