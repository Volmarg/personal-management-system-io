<!-- Template -->
<template>
  <li class="sidebar-item active">
    <a class="sidebar-link" href="#123">
      <i class="align-middle" :data-feather="feathersIconName"></i>
      <span class="align-middle">
        {{ shownText }}
      </span>
    </a>
  </li>
</template>

<script type="ts">
export default{
  props: {
    shownText: {
      type     : String,
      required : true
    },
    feathersIconName: {
      type     : String,
      required : true,
    }
  }
}
</script>