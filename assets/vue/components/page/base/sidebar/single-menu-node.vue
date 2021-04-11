<!-- Template -->
<template>
  <li class="sidebar-item active">
    <a class="sidebar-link collapsed"
       href="#123"
       :data-bs-target="'#' + submenuId"
       :data-bs-toggle="submenuId !== '' ? 'collapse' : ''"
       aria-expanded="false"
    >
      <i class="align-middle"
         :data-feather="feathersIconName"
      ></i>
      <span class="align-middle">
        {{ shownText }}
      </span>
    </a>
    <slot name="submenu"></slot>
  </li>
</template>

<!-- Script -->
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
    },
    submenuId: {
      type     : String,
      required : false,
      default  : ""
    }
  }
}
</script>