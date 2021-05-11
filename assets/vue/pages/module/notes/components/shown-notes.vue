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
      :cancel-button-string="trans('dialogs.buttons.default.close')"
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
import PageCardComponent   from "../../../../components/page/base/page-elements/card.vue";
import NoteCardComponent   from "./note-card.vue";
import SweetAlertComponent from "../../../../components/dialog/sweet-alert/sweet-alert.vue";

export default {
  props: {
    notes: {
      type     : Array,
      required : true,
    }
  },
  components: {
    "page-card"    : PageCardComponent,
    "note-card"    : NoteCardComponent,
    "sweet-alert"  : SweetAlertComponent,
  },
  methods: {
    /**
     * @description will call sweet alert dialog for given (related) note
     */
    callDialog(noteId) {
      let dialog = this.$refs['noteDialogId_' + noteId];
      dialog.showDialog();
    },
  }
}
</script>