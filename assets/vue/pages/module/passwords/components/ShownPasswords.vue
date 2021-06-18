<!-- Template -->
<template>
  <div class="row">

    <PasswordCard v-for="password in passwords"
                   :description="password.description"
                   :url="password.url"
                   :login="password.login"
                   :id="password.id"
    >

    </PasswordCard>
  </div>
</template>

<!-- Script -->
<script>
import PasswordCardComponent from "./PasswordCard";

export default {
  props: {
    passwords: {
      type     : Object,
      required : true
    }
  },
  components: {
    'PasswordCard' : PasswordCardComponent,
  }
}
</script>