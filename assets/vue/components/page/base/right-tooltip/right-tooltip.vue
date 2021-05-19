<!-- Template -->
<template>
  <div class="settings js-settings">

    <icons @right-tooltip-settings-icon-click="rightTooltipSettingsIconClick()" />
    <panels ref="panels" />

  </div>
</template>

<!-- Script -->
<script type="ts">
import IconsComponent  from "./icons";
import PanelsComponent from "./panels";

export default {
  components: {
    "icons"  : IconsComponent,
    "panels" : PanelsComponent,
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