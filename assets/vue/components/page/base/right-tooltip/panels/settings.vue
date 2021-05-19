<!-- Template -->
<template>
  <div class="settings-panel bd" :class="{ 'settings-panel-shown' : isPanelVisible }">
    <div class="settings-content">
      <div class="settings-title d-flex justify-content-between">
        <h4 class="text-white mb-0 d-inline-block panel-header">{{ trans('tooltip.right.settings.header' ) }}</h4>
        <span ref="closeSettingsPanel" class="closeButton" @click="closeSettingsPanelClick()">✗</span>
      </div>

      <div class="settings-options">

        <div class="mb-3">
          <small class="d-block text-uppercase font-weight-bold text-muted mb-2">{{ trans('tooltip.right.settings.description' )}}</small>
          <div class="d-flex justify-content-around">
            <div class="form-check form-switch mb-1">
              <input type="radio" class="form-check-input theme-color theme-color-dark square-checkbox bd" value="dark" checked>
            </div>
            <div class="form-check form-switch mb-1">
              <input type="radio" class="form-check-input theme-color theme-color-light square-checkbox bd" value="light">
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<!-- Script -->
<script>
export default {
  data(){
    return {
      isPanelVisible: false,
    }
  },
  methods: {
    closeSettingsPanelClick(){
      this.isPanelVisible = false;
    }
  }
}
</script>

<!-- Style -->
<style scoped>
  .closeButton {
    color: white;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
  }

  .panel-header {
    margin-top: -6px;
    padding: 12px;
    padding-left: 3px;
    padding-right: 15px;
  }
</style>