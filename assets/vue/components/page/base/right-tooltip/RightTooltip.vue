<!-- Template -->
<template>
  <div class="settings js-settings">

    <Icons @right-tooltip-settings-icon-click="rightTooltipSettingsIconClick()" />
    <Panels ref="panels" />

  </div>
</template>

<!-- Script -->
<script type="ts">
import IconsComponent  from "./Icons";
import PanelsComponent from "./Panels";

export default {
  components: {
    "Icons"  : IconsComponent,
    "Panels" : PanelsComponent,
  },
  methods: {
    /**
     * @description handles clicking on the settings icon "cog"
     */
    rightTooltipSettingsIconClick() {
      this.$refs.panels.$refs.settingsPanel.$data.isPanelVisible = true;
    }
  }
}
</script>