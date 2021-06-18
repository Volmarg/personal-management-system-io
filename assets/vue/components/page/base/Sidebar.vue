<!-- Template -->
<template>
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">

      <!-- Logo -->
      <router-link :to="{ name: routeNameHash}"
                   class="sidebar-brand logo-link"
      >
        <div class="align-middle logo-wrapper position-relative mb-3" style="height: 60px;">
          <Logo/>
        </div>
      </router-link>

      <!-- Links -->
      <ul class="sidebar-nav">
        <li class="sidebar-header"></li>

        <DashboardMenuNode/>
        <PasswordsMenuNode/>
        <NotesMenuNode/>

      </ul>
    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import DashboardMenuNodeComponent from "./sidebar/nodes/DashboardMenuNode";
import NotesMenuNodeComponent     from "./sidebar/nodes/NotesMenuNode";
import PasswordsMenuNodeComponent from "./sidebar/nodes/PasswordsMenuNode";
import LogoComponent              from "./sidebar/Logo";

import Router from "../../../../scripts/libs/vue/Router";

export default {
  data(){
    return {
      routeNameHash : Router.ROUTE_NAME_HASH,
    }
  },
  components: {
    "Logo"              : LogoComponent,
    "DashboardMenuNode" : DashboardMenuNodeComponent,
    "NotesMenuNode"     : NotesMenuNodeComponent,
    "PasswordsMenuNode" : PasswordsMenuNodeComponent,
  }
}
</script>