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
    confirm-button-string="yes"
    cancel-button-string="no"
    header-string="Header"
    id="id"
    :ref="'noteDialogId_' + note.id"
  >
    <template #body-content>
      Dialog content
    </template>
  </sweet-alert>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent   from '../../../components/page/base/page-elements/card';
import NoteCardComponent   from './components/note-card';
import SweetAlertComponent from "../../../components/dialog/sweet-alert/sweet-alert";

export default {
  data(){
    return {
      notes: [ // testing
        {
          id    : 1,
          title : "test 123",
        },
        {
          id    : 2,
          title : "test 456"
        }
      ]
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
      dialog.showDialog();
    }
  }
}
</script>