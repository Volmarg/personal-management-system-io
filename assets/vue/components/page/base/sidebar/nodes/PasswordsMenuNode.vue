<!-- Template -->
<template>
  <SingleMenuNode
      :shown-text="trans('sidebar.menuNodes.passwords.label')"
      :show-collapse="true"
      :submenu-id="passwordsMenuId"
      feathers-icon-name="key"
  >
    <template #submenu>

      <ul :id="passwordsMenuId"
          class="sidebar-dropdown list-unstyled collapse"
          v-if="passwordsGroups.length !== 0"
      >
        <li class="sidebar-item"
            v-for="passwordGroup in passwordsGroups"
        >
          <span class="d-flex justify-content-start">
              <RouterLink :to="{
                name: routeNameModulePasswordsGroup,
                params: {
                  [routeNameModulePasswordsGroupParamId]: passwordGroup.id
                }
              }"
                           class="sidebar-link"
              >
            {{ passwordGroup.name }}
          </RouterLink>
          </span>

        </li>
      </ul>

    </template>
  </SingleMenuNode>
</template>

<!-- Script -->
<script type="ts">

import SingleMenuNodeComponent from "../SingleMenuNode.vue";

import SymfonyRoutes from "../../../../../../scripts/core/symfony/SymfonyRoutes";

import PasswordGroupsDto  from "../../../../../../scripts/core/dto/module/passwords/PasswordGroupsDto";
import PasswordGroupDto   from "../../../../../../scripts/core/dto/module/passwords/PasswordGroupDto";

export default {
  data(){
    return {
      passwordsGroups                      : [],
      routeNameModulePasswordsGroup        : SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP,
      routeNameModulePasswordsGroupParamId : SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP_ID_PARAM,
      passwordsMenuId                      : "passwordsGroups",
    }
  },
  components: {
    "SingleMenuNode" : SingleMenuNodeComponent,

  },
  methods: {
    /**
     * @description will get all password groups from backend
     */
    getAllPasswordsGroups(){
      this.axios.get(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_ALL_PASSWORDS_GROUPS)).then( (response) => {
        let passwordGroupsDto = PasswordGroupsDto.fromAxiosResponse(response);
        this.passwordsGroups  = passwordGroupsDto.passwordsGroupsJsons.map( (json) => PasswordGroupDto.fromJson(json) );
      })
    }
  },
  beforeMount(){
    this.getAllPasswordsGroups();
  }
}

</script>