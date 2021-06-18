<!-- Template -->
<template>
  <SingleMenuNode
      :shown-text="trans('sidebar.menuNodes.dashboard.label')"
      feathers-icon-name="sliders"
      :to-path-name="routeNameModuleDashboardOverview"
  />
</template>

<!-- Script -->
<script type="ts">
import SymfonyRoutes           from "../../../../../../scripts/core/symfony/SymfonyRoutes";
import SingleMenuNodeComponent from "../SingleMenuNode.vue";

export default {
  data(){
    return{
      routeNameModuleDashboardOverview : SymfonyRoutes.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
    }
  },
  components: {
    "SingleMenuNode" : SingleMenuNodeComponent,
  }
}
</script>