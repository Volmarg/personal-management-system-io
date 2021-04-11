<!-- Template -->
<template>

  <div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
      <h3>{{ shownText }}</h3>
    </div>
  </div>

</template>

<!-- Script -->
<script type="ts">
export default{
  props: {
    shownText: {
      type     : String,
      required : true,
    }
  }
}
</script>