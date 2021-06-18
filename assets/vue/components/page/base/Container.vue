<!-- Template -->
<template>

  <Spinner/>
  <div class="wrapper" v-if="includeBaseComponents">
    <Sidebar/>

    <div class="main">
      <TopBar/>
      <SinglePage/>
      <FooterBar/>
    </div>

  </div>

  <div class="wrapper" v-else>
    <div class="main">
      <RouterView></RouterView>
    </div>
  </div>

  <RightTooltip v-if="isRightTooltipVisible"/>
</template>

<!-- Script -->
<script type="ts">
import SidebarComponent      from './Sidebar';
import TopBarComponent       from './TopBar';
import SinglePageComponent   from './SinglePage';
import FooterBarComponent    from './FooterBar';
import SpinnerComponent      from "./../../spinner/Spinner";
import RightTooltipComponent from "./right-tooltip/RightTooltip";
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
    'Sidebar'      : SidebarComponent,
    'TopBar'       : TopBarComponent,
    "SinglePage"   : SinglePageComponent,
    "FooterBar"    : FooterBarComponent,
    "Spinner"      : SpinnerComponent,
    "RightTooltip" : RightTooltipComponent,
  },
  watch: {
    $route(currRoute){
      this.isRightTooltipVisible = (currRoute.name !== SymfonyRoutes.ROUTE_NAME_LOGIN);
    }
  }
}
</script>