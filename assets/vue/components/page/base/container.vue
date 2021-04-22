<!-- Template -->
<template>

  <div class="wrapper" v-if="includeBaseComponents">
    <sidebar/>

    <div class="main">
      <topbar/>
      <page/>
      <footer-bar/>
    </div>

  </div>

  <div class="wrapper" v-else>
    <div class="main">
      <router-view></router-view>
    </div>
  </div>

</template>

<!-- Script -->
<script type="ts">
import SidebarComponent from './sidebar';
import TopbarComponent  from './topbar';
import PageComponent    from './single-page';
import FooterComponent  from './footer';

export default {
  props: {
    includeBaseComponents: {
      type     : Boolean,
      required : false,
      default  : true,
    }
  },
  components: {
    'sidebar'    : SidebarComponent,
    'topbar'     : TopbarComponent,
    "page"       : PageComponent,
    "footer-bar" : FooterComponent,
  }
}
</script>