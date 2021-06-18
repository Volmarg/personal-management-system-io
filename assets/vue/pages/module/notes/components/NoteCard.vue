<!-- Template -->
<template>
  <div class="card mb-3 bg-light cursor-pointer border">
    <div class="card-body p-3">

      <p>{{ title }}</p>

    </div>
  </div>
</template>

<!-- Script -->
<script>
export default {
  props: {
    title: {
      type     : String,
      required : true
    }
  }
}
</script>
