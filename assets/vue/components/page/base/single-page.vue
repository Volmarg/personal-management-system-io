<!-- Template -->
<template>
  <main class="content">
    <div class="container-fluid p-0">

      <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
          <h3><strong>Analytics</strong> Dashboard</h3>
        </div>
      </div>

      <slot name="pageContent">
        <note-category/>       <!-- todo: remove, this is for testing only, but leave the slot -->
      </slot>

    </div>
  </main>
</template>

<!-- Script -->
<script type="ts">
import NoteCategoryPageComponent from "../../../pages/module/notes/category";

export default {
  components: {
    "note-category": NoteCategoryPageComponent
  }
}
</script>