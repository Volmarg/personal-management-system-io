<!-- Template -->
<template>
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">

      <!-- Logo -->
      <a class="sidebar-brand" href="index.html">
        <span class="align-middle">AdminKit</span>
      </a>

      <ul class="sidebar-nav">
        <li class="sidebar-header">
          Pages
        </li>
        <menu-node
          shown-text="Dashboard"
          feathers-icon-name="sliders"
        />
        <menu-node
            shown-text="Notes"
            feathers-icon-name="book"
        />
      </ul>

    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import MenuNodeComponent from './sidebar/menu-node';

export default {
  components: {
    "menu-node": MenuNodeComponent
  }
}
</script>