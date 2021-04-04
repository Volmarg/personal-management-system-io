<!-- Template -->
<template>

  <div class="wrapper">
    <sidebar/>

    <div class="main">
      <topbar/>
      <page/>
      <footer/>
    </div>
  </div>

</template>

<!-- Script -->
<script type="ts">
import SidebarComponent from './sidebar';
import TopbarComponent  from './topbar';
import PageComponent    from './single-page';
import FooterComponent  from './footer';

export default{
  components: {
    'sidebar' : SidebarComponent,
    'topbar'  : TopbarComponent,
    "page"    : PageComponent,
    "footer"  : FooterComponent,
  }
}
</script>