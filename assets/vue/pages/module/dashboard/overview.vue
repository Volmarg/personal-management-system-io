<!-- Template -->
<template>

  <page-card>
    <div class="tile is-ancestor">

      <single-module-block
        :text="trans('sidebar.menuNodes.notes.label')"
        icon="book"
      />

      <single-module-block
          :text="trans('sidebar.menuNodes.passwords.label')"
          icon="key"
      />

    </div>
  </page-card>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent   from '../../../components/page/base/page-elements/card';
import SingleModuleBlock   from './components/single-module-block';

export default {
  components: {
    'page-card'           : PageCardComponent,
    'single-module-block' : SingleModuleBlock,
  }
}
</script>

<!-- Styles -->
<style scoped>
  @media(min-width: 800px) {
    .tile {
      display: flex;
      justify-content: center;
    }
  }
</style>