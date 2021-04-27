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
import PageCardComponent                        from "../../../components/page/base/page-elements/card";
import PasswordCardComponent                    from "./components/password-card";

import SymfonyRoutes                 from "../../../../scripts/core/symfony/SymfonyRoutes";
import PasswordGroupWithPasswordsDto from "../../../../scripts/core/dto/module/passwords/PasswordGroupWithPasswordsDto";
import PasswordDto                   from "../../../../scripts/core/dto/module/passwords/PasswordDto";

export default {
  data(){
    return {
      currentGroupId   : null,
      currentGroupName : "",
      passwords        : [],
    }
  },
  components: {
    'page-card'     : PageCardComponent,
    'password-card' : PasswordCardComponent
  },
  methods: {
    /**
     * @description will return passwords in given group, alongside with some basic data about the group
     */
    getPasswordsForCurrentGroupId(){
      let url = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_PASSWORDS_FOR_GROUP_ID, {
        [SymfonyRoutes.ROUTE_NAME_GET_PASSWORDS_FOR_GROUP_ID_PARAM_ID] : this.currentGroupId,
      })

      this.axios.get(url).then( (response) => {
        let passwordGroupWithPasswords = PasswordGroupWithPasswordsDto.fromAxiosResponse(response);
        this.passwords                 = passwordGroupWithPasswords.passwordsJsons.map(
            (json) => PasswordDto.fromJson(json)
        )
      })

    },
    /**
     * @description will set current group id from route
     */
    setGroupIdFromRoute(){
      this.currentGroupId = this.$route.params[SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP_ID_PARAM]
    }
  },
  beforeMount(){
    this.setGroupIdFromRoute();
    this.getPasswordsForCurrentGroupId();
  },
  watch: {
    $route(currRoute){

      /** @description handles fetching passwords upon changing the password group */
      if(currRoute.name === SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP){
        this.setGroupIdFromRoute();
        this.getPasswordsForCurrentGroupId();
      }
    }
  }
}
</script>