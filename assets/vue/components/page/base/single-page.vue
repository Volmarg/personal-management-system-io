<!-- Template -->
<template>
  <main class="content">
    <div class="container-fluid p-0">

      <slot name="pageContent">
        <router-view></router-view>
      </slot>

    </div>
  </main>
</template>

<!-- Script -->
<script type="ts">
export default {}
</script>