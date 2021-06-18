<!-- Template -->
<template>
  <div class="settings-icons">
    <SettingsIcon @click="$emit('rightTooltipSettingsIconClick')"/>
  </div>
</template>

<!-- Script -->
<script>
import SettingsIconComponent from "./icons/SettingsIcon";

export default {
  emits: [
    'rightTooltipSettingsIconClick'
  ],
  components: {
    "SettingsIcon": SettingsIconComponent
  }
}
</script>