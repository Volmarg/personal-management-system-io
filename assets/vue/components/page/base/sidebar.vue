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
        <li class="sidebar-item active">
          <a data-bs-target="#dashboards" data-bs-toggle="collapse" class="sidebar-link">
            <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
          </a>
          <ul id="dashboards" class="sidebar-dropdown list-unstyled collapse show" data-bs-parent="#sidebar">
            <li class="sidebar-item active"><a class="sidebar-link" href="index.html">Analytics</a></li>
          </ul>
        </li>
      </ul>

    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
export default {}
</script>