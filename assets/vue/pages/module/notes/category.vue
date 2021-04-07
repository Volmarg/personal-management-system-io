<!-- Template -->
<template>

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
import NoteCardComponent   from './components/note-card';
import SweetAlertComponent from "../../../components/dialog/sweet-alert/sweet-alert";

import TranslationsService            from "../../../../scripts/core/service/TranslationsService";
import SymfonyRoutes                  from "../../../../scripts/core/symfony/SymfonyRoutes";
import GetNotesForCategoryResponseDto from "../../../../scripts/core/dto/module/notes/GetNotesForCategoryResponseDto";
import MyNoteDto                      from "../../../../scripts/core/dto/module/notes/MyNoteDto";

let translationService = new TranslationsService();

export default {
  data(){
    return {
      categoryId : 1,
      notes      : []
    }
  },
  components: {
    "page-card"   : PageCardComponent,
    "note-card"   : NoteCardComponent,
    "sweet-alert" : SweetAlertComponent,
  },
  methods: {
    /**
     * @description will call sweet alert dialog for given (related) note
     */
    callDialog(noteId) {
      let dialog = this.$refs['noteDialogId_' + noteId];
      console.log(dialog);
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
        let getNotesForCategoryResponseDto = GetNotesForCategoryResponseDto.fromAxiosResponse(response);

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
    }
  },
  beforeMount(){
    this.getNotesForCategory();
  }
}
</script>