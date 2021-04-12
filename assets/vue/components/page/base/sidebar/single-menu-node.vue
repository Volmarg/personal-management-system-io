<!-- Template -->
<template>
  <li class="sidebar-item active">
    <router-link :to="{
                    name: toPathName,
                  }"
                 class="sidebar-link collapsed"
                 :data-bs-target="showCollapse ? '#' + submenuId : ''"
                 :data-bs-toggle="showCollapse && (submenuId !== '') ? 'collapse' : ''"
                 aria-expanded="false"
    >
      <i class="align-middle"
         :data-feather="feathersIconName"
      ></i>
      <span class="align-middle">
        {{ shownText }}
      </span>
    </router-link>

    <slot name="submenu"></slot>
  </li>
</template>

<!-- Script -->
<script type="ts">
import Router from "../../../../../scripts/libs/vue/Router";

export default {
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
    },
    showCollapse: {
      type    : Boolean,
      default : false,
    },
    toPathName: {
      type     : String,
      required : false,
      default  : Router.ROUTE_NAME_HASH
    }
  }
}
</script>