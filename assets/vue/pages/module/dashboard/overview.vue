<!-- Template -->
<template>

  <page-card>
    <div class="tile is-ancestor">

      <single-module-block
        :text="trans('sidebar.menuNodes.notes.label')"
        icon="book"
      >
        <template #alertDialogContent>

          <div class="tile is-ancestor">
            <single-module-block
                v-for="categoryDto in allCategories"
                :text="categoryDto.name"
                :target-url="buildNoteCategoryUrl(categoryDto.id)"
            />
          </div>

        </template>
      </single-module-block>

      <single-module-block
          :text="trans('sidebar.menuNodes.passwords.label')"
          icon="key"
      >
        <template #alertDialogContent>

          <div class="tile is-ancestor">
            <single-module-block
                v-for="passwordGroupDto in passwordsGroups"
                :text="passwordGroupDto.name"
                :target-url="buildPasswordGroupUrl(passwordGroupDto.id)"
            />
          </div>

        </template>
      </single-module-block>

    </div>
  </page-card>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent          from '../../../components/page/base/page-elements/card';
import SingleModuleBlockComponent from './components/single-module-block';

import SymfonyRoutes         from "../../../../scripts/core/symfony/SymfonyRoutes";
import AllNotesCategoriesDto from "../../../../scripts/core/dto/module/notes/AllNotesCategoriesDto";
import NoteCategoryDto       from "../../../../scripts/core/dto/module/notes/NoteCategoryDto";
import PasswordGroupsDto     from "../../../../scripts/core/dto/module/passwords/PasswordGroupsDto";
import PasswordGroupDto      from "../../../../scripts/core/dto/module/passwords/PasswordGroupDto";

export default {
  data(){
    return {
      allCategories   : [],
      passwordsGroups : [],
    }
  },
  components: {
    'page-card'           : PageCardComponent,
    'single-module-block' : SingleModuleBlockComponent,
  },
  methods: {
    /**
     * @description will set all notes categories
     */
    getAllNotesCategories(){
      let calledUrl = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_NOTES_CATEGORIES);
      this.axios.get(calledUrl).then((response) => {
        let responseDto    = AllNotesCategoriesDto.fromAxiosResponse(response);
        this.allCategories = responseDto.notesCategoriesJsons.map( json => NoteCategoryDto.fromJson(json) );
      })
    },
    /**
     * @description will return note category url for given id
     */
    buildNoteCategoryUrl(categoryId){
      let path = SymfonyRoutes.getPathForName(
          SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY,
          {
            [SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY_ID_PARAM]: categoryId,
          }
      )
      return path;
    },
    /**
     * @description will get all password groups from backend
     */
    getAllPasswordsGroups(){
      this.axios.get(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_ALL_PASSWORDS_GROUPS)).then( (response) => {
        let passwordGroupsDto = PasswordGroupsDto.fromAxiosResponse(response);
        this.passwordsGroups  = passwordGroupsDto.passwordsGroupsJsons.map( (json) => PasswordGroupDto.fromJson(json) );
      })
    },
    /**
     * @description will return note category url for given id
     */
    buildPasswordGroupUrl(groupId){
      let path = SymfonyRoutes.getPathForName(
          SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP,
          {
            [SymfonyRoutes.ROUTE_NAME_MODULE_PASSWORDS_GROUP_ID_PARAM]: groupId,
          }
      )
      return path;
    },
  },
  beforeMount() {
    this.getAllNotesCategories();
    this.getAllPasswordsGroups();
  }
}
</script>

<!-- Styles -->
<style scoped>
  @media(min-width: 800px) {
    .tile {
      display: flex;
      justify-content: center;
    }
  }
</style>