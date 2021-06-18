<!-- Template -->
<template>
  <SettingsPanel ref="settingsPanel"/>
</template>

<!-- Script -->
<script type="ts">
import SettingsPanelComponent from "./panels/SettingsPanel";

export default {
  components: {
    "SettingsPanel": SettingsPanelComponent
  }
}
</script>