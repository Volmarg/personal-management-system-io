<!-- Template -->
<template>

  <page-header :shown-text="categoryName"/>

  <page-card>
    <note-card
      v-for="note in notes"
      @click="callDialog(note.id)"
      :title="note.title"
    />
  </page-card>

  <sweet-alert
    v-for="note in notes"
    :cancel-button-string="dialogCloseButtonTranslatedString()"
    :header-string="note.title"
    :dialog-content="note.body"
    :id="'noteDialogId_' + note.id"
    :ref="'noteDialogId_' + note.id"
  >
    <template #body-content>
      {{ note.body }}
    </template>
  </sweet-alert>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent   from '../../../components/page/base/page-elements/card';
import PageHeaderComponent from '../../../components/page/base/page-elements/header';
import NoteCardComponent   from './components/note-card';
import SweetAlertComponent from "../../../components/dialog/sweet-alert/sweet-alert";

import TranslationsService            from "../../../../scripts/core/service/TranslationsService";
import SymfonyRoutes                  from "../../../../scripts/core/symfony/SymfonyRoutes";
import GetCategoryResponseDto from "../../../../scripts/core/dto/module/notes/GetCategoryResponseDto";
import MyNoteDto                      from "../../../../scripts/core/dto/module/notes/MyNoteDto";

let translationService = new TranslationsService();

export default {
  data(){
    return {
      categoryId   : null,
      categoryName : null,
      notes        : []
    }
  },
  components: {
    "page-card"   : PageCardComponent,
    "page-header" : PageHeaderComponent,
    "note-card"   : NoteCardComponent,
    "sweet-alert" : SweetAlertComponent,
  },
  methods: {
    /**
     * @description will call sweet alert dialog for given (related) note
     */
    callDialog(noteId) {
      let dialog = this.$refs['noteDialogId_' + noteId];
      dialog.showDialog();
    },
    /**
     * @description will get notes for current category id
     */
    getNotesForCategory(){
      let calledUrl = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_NOTES_FOR_CATEGORY_ID, {
        [SymfonyRoutes.GET_NOTES_FOR_CATEGORY_ID_PARAM_CATEGORY_ID] : this.categoryId
      })

      this.axios.get(calledUrl).then( (response) => {
        let getNotesForCategoryResponseDto = GetCategoryResponseDto.fromAxiosResponse(response);
        this.categoryName                  = getNotesForCategoryResponseDto.name;

        this.notes = getNotesForCategoryResponseDto.notesJsons.map( (json) => {
          return MyNoteDto.fromJson(json);
        });

      })
    },
    /**
     * @description gets the translation for the close button in modal
     */
    dialogCloseButtonTranslatedString(){
      return translationService.getTranslationForString('dialogs.buttons.default.close')
    },
    /**
     * @description will set categoryId based on current route
     */
    setCategoryIdFromRoute(){
      this.categoryId = this.$route.params.id;
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
      this.getNotesForCategory();
    }
  }
}
</script>