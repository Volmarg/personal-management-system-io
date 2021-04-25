<!-- Template -->
<template>
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">

      <!-- Logo -->
      <router-link :to="{ name: routeNameHash}"
                   class="sidebar-brand logo-link"
      >
          <span class="align-middle logo-wrapper">
          <img src="/img/logo.png" class="logo-image"/>
        </span>
      </router-link>

      <!-- Links -->
      <ul class="sidebar-nav">
        <li class="sidebar-header"></li>

        <dashboard-menu-node/>
        <password-menu-node/>
        <notes-menu-node/>

      </ul>
    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import DashboardMenuNodeComponent from "./sidebar/nodes/dashboard-menu-node";
import NotesMenuNodeComponent     from "./sidebar/nodes/notes-menu-node";
import PasswordsMenuNodeComponent from "./sidebar/nodes/passwords-menu-node";

import Router from "../../../../scripts/libs/vue/Router";

export default {
  data(){
    return {
      routeNameHash : Router.ROUTE_NAME_HASH,
    }
  },
  components: {
    "dashboard-menu-node" : DashboardMenuNodeComponent,
    "notes-menu-node"     : NotesMenuNodeComponent,
    "password-menu-node"  : PasswordsMenuNodeComponent,
  }
}
</script>