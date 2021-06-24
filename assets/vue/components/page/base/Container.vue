<!-- Template -->
<template>

  <Spinner :text="spinnerText"/>
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
      <RouterView
          @checking-if-data-is-available="setSpinnerTextCheckingIfDataIsAvailable"
          @data-is-available="cleanSpinnerText"
      ></RouterView>
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
      isRightTooltipVisible : true,
      spinnerText           : "",
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
  },
  methods: {
    /**
     * @description Will set the text for spinner when no data delivery is available
     */
    setSpinnerTextCheckingIfDataIsAvailable(){
      this.spinnerText = this.trans('security.login.messages.checkingIfDataHasBeenDelivered')
    },
    /**
     * @description will unset text for spinner
     */
    cleanSpinnerText(){
      this.spinnerText = "";
    }
  }
}
</script>