<!-- Template -->
<template>
  <div class="row">

    <password-card v-for="password in passwords"
                   :description="password.description"
                   :url="password.url"
                   :login="password.login"
                   :id="password.id"
    >

    </password-card>
  </div>
</template>

<!-- Script -->
<script>

import PageCardComponent     from "../../../../components/page/base/page-elements/card";
import PasswordCardComponent from "./password-card";

export default {
  props: {
    passwords: {
      type     : Object,
      required : true
    }
  },
  components: {
    'page-card'       : PageCardComponent,
    'password-card'   : PasswordCardComponent,
  }
}
</script>