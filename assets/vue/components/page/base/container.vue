<!-- Template -->
<template>

  <spinner/>
  <div class="wrapper" v-if="includeBaseComponents">
    <sidebar/>

    <div class="main">
      <topbar/>
      <page/>
      <footer-bar/>
    </div>

  </div>

  <div class="wrapper" v-else>
    <div class="main">
      <router-view></router-view>
    </div>
  </div>

  <right-tooltip v-if="isRightTooltipVisible"/>
</template>

<!-- Script -->
<script type="ts">
import SidebarComponent      from './sidebar';
import TopbarComponent       from './topbar';
import PageComponent         from './single-page';
import FooterComponent       from './footer';
import SpinnerComponent      from "./../../spinner/spinner";
import RightTooltipComponent from "./right-tooltip/right-tooltip";
import SymfonyRoutes         from "../../../../scripts/core/symfony/SymfonyRoutes";

export default {
  data(){
    return {
      isRightTooltipVisible: true,
    }
  },
  props: {
    includeBaseComponents: {
      type     : Boolean,
      required : false,
      default  : true,
    }
  },
  components: {
    'sidebar'       : SidebarComponent,
    'topbar'        : TopbarComponent,
    "page"          : PageComponent,
    "footer-bar"    : FooterComponent,
    "spinner"       : SpinnerComponent,
    "right-tooltip" : RightTooltipComponent,
  },
  watch: {
    $route(currRoute){
      this.isRightTooltipVisible = (currRoute.name !== SymfonyRoutes.ROUTE_NAME_LOGIN);
    }
  }
}
</script>