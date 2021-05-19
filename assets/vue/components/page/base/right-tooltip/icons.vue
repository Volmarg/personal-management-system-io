<!-- Template -->
<template>
  <div class="settings-icons">
    <settings-icon @click="$emit('rightTooltipSettingsIconClick')"/>
  </div>
</template>

<!-- Script -->
<script>
import SettingsIconComponent from "./icons/settings";

export default {
  emits: [
    'rightTooltipSettingsIconClick'
  ],
  components: {
    "settings-icon": SettingsIconComponent
  }
}
</script>