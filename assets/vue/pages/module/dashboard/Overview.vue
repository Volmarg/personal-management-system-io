<!-- Template -->
<template>

  <PageCard>
    <div class="tile is-ancestor">

      <SingleModuleBlock
        :text="trans('sidebar.menuNodes.notes.label')"
        icon="book"
      >
        <template #alertDialogContent>

          <SearchInput @search-input-changed="filterNotesCategoriesForSearchInput" />

          <div v-for="categoriesInGroup in groupedShownNotesCategories">
            <div class="tile is-ancestor">
              <SingleModuleBlock
                  v-for="categoryDto in categoriesInGroup"
                  :text="categoryDto.name"
                  :target-url="buildNoteCategoryUrl(categoryDto.id)"
              />
            </div>
          </div>

        </template>
      </SingleModuleBlock>

      <SingleModuleBlock
          :text="trans('sidebar.menuNodes.passwords.label')"
          icon="key"
      >
        <template #alertDialogContent>

          <SearchInput @search-input-changed="filterPasswordsGroupsForSearchInput" />

          <div v-for="passwordGroupDtos in groupedShownPasswordsGroups">
            <div class="tile is-ancestor">
              <SingleModuleBlock
                  v-for="passwordGroupDto in passwordGroupDtos"
                  :text="passwordGroupDto.name"
                  :target-url="buildPasswordGroupUrl(passwordGroupDto.id)"
              />
            </div>
          </div>

        </template>
      </SingleModuleBlock>

    </div>
  </PageCard>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent          from '../../../components/page/base/page-elements/PageCard';
import SingleModuleBlockComponent from './components/SingleModuleBlock';
import SearchInputComponent       from '../../../components/page/base/page-elements/SearchInput';

import SymfonyRoutes         from "../../../../scripts/core/symfony/SymfonyRoutes";
import AllNotesCategoriesDto from "../../../../scripts/core/dto/module/notes/AllNotesCategoriesDto";
import NoteCategoryDto       from "../../../../scripts/core/dto/module/notes/NoteCategoryDto";
import PasswordGroupsDto     from "../../../../scripts/core/dto/module/passwords/PasswordGroupsDto";
import PasswordGroupDto      from "../../../../scripts/core/dto/module/passwords/PasswordGroupDto";

import StringUtils from "../../../../scripts/core/utils/StringUtils";

export default {
  data(){
    return {
      allPasswordsGroups          : [],
      groupedShownPasswordsGroups : [],

      allNotesCategories          : [],
      groupedShownNotesCategories : [],

      maxElementsInGroup : 6,
    }
  },
  components: {
    'PageCard'          : PageCardComponent,
    'SingleModuleBlock' : SingleModuleBlockComponent,
    'SearchInput'       : SearchInputComponent,
  },
  methods: {
    /**
     * @description will set all notes categories
     */
    getAllNotesCategories(){
      let calledUrl = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_NOTES_CATEGORIES);
      this.axios.get(calledUrl).then((response) => {
        let responseDto                  = AllNotesCategoriesDto.fromAxiosResponse(response);
        this.allNotesCategories          = responseDto.notesCategoriesJsons.map( json => NoteCategoryDto.fromJson(json) );
        this.groupedShownNotesCategories = this.groupElements(this.maxElementsInGroup, this.allNotesCategories);
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
        let passwordGroupsDto            = PasswordGroupsDto.fromAxiosResponse(response);
        this.allPasswordsGroups          = passwordGroupsDto.passwordsGroupsJsons.map( (json) => PasswordGroupDto.fromJson(json) );
        this.groupedShownPasswordsGroups = this.groupElements(this.maxElementsInGroup, this.allPasswordsGroups);
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
    /**
     * @description will filter the visible notes categories
     */
    filterNotesCategoriesForSearchInput(searchedString){
      if( StringUtils.isEmptyString(searchedString) ){
        this.groupedShownNotesCategories = this.groupElements(this.maxElementsInGroup,  this.allNotesCategories);
        return;
      }

      let filteredCategories = this.allNotesCategories.filter( (noteCategoryDto) => {
        return noteCategoryDto.name.toLowerCase().includes(searchedString.toLowerCase());
      })

      this.groupedShownNotesCategories = this.groupElements(this.maxElementsInGroup, filteredCategories);
    },
    /**
     * @description will filter the visible password groups
     */
    filterPasswordsGroupsForSearchInput(searchedString){
      if( StringUtils.isEmptyString(searchedString) ){
        this.groupedShownPasswordsGroups = this.groupElements(this.maxElementsInGroup, this.allPasswordsGroups);
        return;
      }

      let filteredPasswordGroups = this.allPasswordsGroups.filter( (passwordGroupDto) => {
        return passwordGroupDto.name.toLowerCase().includes(searchedString.toLowerCase());
      })

      this.groupedShownPasswordsGroups = this.groupElements(this.maxElementsInGroup, filteredPasswordGroups);
    },
    /**
     * @description will groups the elements by groups of given size
     */
    groupElements(groupSize, elementsToGroup){
      let groupedElements = [];
      let groupToAdd      = [];

      for(let indexOfElement in elementsToGroup){
        let element = elementsToGroup[indexOfElement];
        groupToAdd.push(element);

        let isGroupSizeOfDesiredSize = (groupToAdd.length == groupSize);
        let isLastElementToHandle    = (elementsToGroup.length - 1 == indexOfElement); // required due to not full group with leftover elements

        if( isGroupSizeOfDesiredSize || isLastElementToHandle ){
          groupedElements.push(groupToAdd);
          groupToAdd = [];
        }
      }

      return groupedElements;
    }
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