<!-- Template -->
<template>
  <PageHeader :shown-text="trans('pages.passwords.group.header', {'{{passwordGroupName}}' : currentGroupName})"/>
  <SearchInput @search-input-changed="filterPasswordsForSearchInput" />
  <ShownPasswords :passwords="shownPasswords"/>
</template>

<!-- Script -->
<script>
import PageHeaderComponent      from "../../../components/page/base/page-elements/Header";
import ShownPasswordsComponents from "./components/ShownPasswords"
import SearchInputComponent     from "../../../components/page/base/page-elements/SearchInput";

import SymfonyRoutes                 from "../../../../scripts/core/symfony/SymfonyRoutes";
import PasswordGroupWithPasswordsDto from "../../../../scripts/core/dto/module/passwords/PasswordGroupWithPasswordsDto";
import PasswordDto                   from "../../../../scripts/core/dto/module/passwords/PasswordDto";
import StringUtils                   from "../../../../scripts/core/utils/StringUtils";

export default {
  data(){
    return {
      currentGroupId   : null,
      currentGroupName : "",
      allPasswords     : [],
      shownPasswords   : [],
    }
  },
  components: {
    'ShownPasswords' : ShownPasswordsComponents,
    'PageHeader'     : PageHeaderComponent,
    'SearchInput'    : SearchInputComponent,
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
        this.currentGroupName          = passwordGroupWithPasswords.passwordGroupName;
        this.allPasswords              = passwordGroupWithPasswords.passwordsJsons.map(
            (json) => PasswordDto.fromJson(json)
        )
        this.shownPasswords = this.allPasswords;
      })

    },
    /**
     * @description will set current group id from route
     */
    setGroupIdFromRoute(){
      this.currentGroupId = this.$route.params[SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP_ID_PARAM]
    },
    /**
     * @description will filter the currently displayed boxes on page based on what's typed in the search input
     */
    filterPasswordsForSearchInput(searchedString){
      if( StringUtils.isEmptyString(searchedString) ){
        this.shownPasswords = this.allPasswords;
        return;
      }

      this.shownPasswords = this.allPasswords.filter( (passwordDto) => {
        return passwordDto.description.toLowerCase().includes(searchedString.toLowerCase());
      })
    }
  },
  beforeMount(){
    this.setGroupIdFromRoute();
    this.getPasswordsForCurrentGroupId();
  },
  watch: {
    /**
     * @description observer router to rebuild data on page
     */
    $route(currRoute, oldRoute){

      /** @description handles fetching passwords upon changing the password group */
      if(
              currRoute.name === SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP
          &&  oldRoute.path  !== currRoute.path
      ){
        this.setGroupIdFromRoute();
        this.getPasswordsForCurrentGroupId();
      }
    }
  }
}
</script>