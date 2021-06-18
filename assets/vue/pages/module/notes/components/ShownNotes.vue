<!-- Template -->
<template>

  <PageCard>
    <NoteCard
        v-for="note in notes"
        @click="callDialog(note.id)"
        :title="note.title"
    />
  </PageCard>

  <SweetAlert
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
  </SweetAlert>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent   from "../../../../components/page/base/page-elements/PageCard.vue";
import NoteCardComponent   from "./NoteCard.vue";
import SweetAlertComponent from "../../../../components/dialog/sweet-alert/SweetAlert.vue";

export default {
  props: {
    notes: {
      type     : Array,
      required : true,
    }
  },
  components: {
    "PageCard"   : PageCardComponent,
    "NoteCard"   : NoteCardComponent,
    "SweetAlert" : SweetAlertComponent,
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