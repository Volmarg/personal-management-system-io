<!-- Template -->
<template>

  <page-header :shown-text="trans('pages.notes.category.header', {'{{noteCategoryName}}' : categoryName})"/>
  <search-input @search-input-changed="filterNotesForSearchInput" />
  <shown-notes :notes="shownNotes"/>

</template>

<!-- Script -->
<script type="ts">
import PageHeaderComponent  from '../../../components/page/base/page-elements/header';
import SearchInputComponent from "../../../components/page/base/page-elements/search-input";
import ShownNotesComponent  from "./components/shown-notes";

import SymfonyRoutes      from "../../../../scripts/core/symfony/SymfonyRoutes";
import NotesInCategoryDto from "../../../../scripts/core/dto/module/notes/NotesInCategoryDto";
import MyNoteDto          from "../../../../scripts/core/dto/module/notes/MyNoteDto";
import StringUtils        from "../../../../scripts/core/utils/StringUtils";

export default {
  data(){
    return {
      categoryId   : null,
      categoryName : "",
      allNotes     : [],
      shownNotes   : [],
    }
  },
  components: {
    "page-header"  : PageHeaderComponent,
    "search-input" : SearchInputComponent,
    "shown-notes"  : ShownNotesComponent
  },
  methods: {
    /**
     * @description will get notes for current category id
     */
    getNotesForCategory(){
      let calledUrl = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_NOTES_FOR_CATEGORY_ID, {
        [SymfonyRoutes.ROUTE_GET_NOTES_FOR_CATEGORY_ID_PARAM_CATEGORY_ID] : this.categoryId
      })

      this.axios.get(calledUrl).then( (response) => {
        let notesInCategoryDto = NotesInCategoryDto.fromAxiosResponse(response);
        this.categoryName      = notesInCategoryDto.name;

        this.allNotes = notesInCategoryDto.notesJsons.map( (json) => {
          return MyNoteDto.fromJson(json);
        });

        this.shownNotes = this.allNotes;
      })
    },
    /**
     * @description will set categoryId based on current route
     */
    setCategoryIdFromRoute(){
      this.categoryId = this.$route.params.id;
    },
    /**
     * @description will filter show notes based on input search text
     */
    filterNotesForSearchInput(searchedString){
      if( StringUtils.isEmptyString(searchedString) ){
        this.shownNotes = this.allNotes;
        return;
      }

      this.shownNotes = this.allNotes.filter( (noteDto) => {
        return noteDto.title.toLowerCase().includes(searchedString.toLowerCase());
      })
    }
  },
  beforeMount() {
    this.getNotesForCategory();
  },
  created(){
    this.setCategoryIdFromRoute();
  },
  watch: {
    $route(oldValue, newValue){
      this.setCategoryIdFromRoute();

      // this might happen when going from note category url to some other - router is still being observed on this step
      if(this.categoryId){
        this.getNotesForCategory();
      }
    }
  }
}
</script>