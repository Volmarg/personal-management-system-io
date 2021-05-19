<!-- Template -->
<template>
  <settings-panel ref="settingsPanel"/>
</template>

<!-- Script -->
<script type="ts">
import SettingsPanelComponent from "./panels/settings";

export default {
  components: {
    "settings-panel": SettingsPanelComponent
  }
}
</script>