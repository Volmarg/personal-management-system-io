<!-- Template -->
<template>
  <div class="swal2-container swal2-center swal2-backdrop-show dialog-container d-none dialog-backdrop"
       :id="id"
       @click="closeDialog($event)"
  >
    <div aria-labelledby="swal2-title"
         aria-describedby="swal2-content"
         class="swal2-popup swal2-modal swal2-icon-warning swal2-show d-flex"
         tabindex="-1"
         role="dialog"
         aria-live="assertive"
         aria-modal="true">

      <div class="swal2-header">
        <h2 class="swal2-title d-flex"
            id="swal2-title"
        >
          {{ headerString }}
        </h2>
        <button type="button"
                class="swal2-close d-block"
                @click="closeDialog()">×</button>
      </div>

      <div class="swal2-content mt-3">
        <div id="swal2-content"
             class="swal2-html-container d-block"
             ref="dialogContentWrapper"
        >
          <div v-html="dialogContent"></div>
        </div>
      </div>

      <div class="swal2-actions">
        <div class="swal2-loader"></div>
        <button type="button"
                class="btn btn-success"
                @click.prevent="$emit('confirmButtonClicked')"
                v-if="confirmButtonString"
        >
          {{ confirmButtonString }}
        </button>
        <button type="button"
                class="btn btn-danger ml-3 cancel-button"
                @click="closeDialog()"
        >
          {{ cancelButtonString }}
        </button>
      </div>
    </div>
  </div>
</template>

<!-- Script !-->
<script type="ts">
import SweetAlert from "../../../../scripts/libs/sweetalert/SweetAlert";

export default {
  emits: [
    'confirmButtonClicked',
  ],
  props: {
    confirmButtonString: {
      type     : String,
      required : false
    },
    cancelButtonString: {
      type     : String,
      required : true,
    },
    headerString: {
      type     : String,
      required : false,
      default : ""
    },
    id: {
      type     : String,
      required : true
    },
    dialogContent: {
      type     : String,
      required : true,
    }
  },
  methods: {
    /**
     * @description close the dialog
     */
    closeDialog(event){

      if(undefined !== event){
        this.handleClickOutsideDialog(event);
        return;
      }

      SweetAlert.hideDialogForId(this.id);
    },
    /**
     * @description show the dialog
     */
    showDialog(){
      SweetAlert.showDialogForId(this.id);
    },
    /**
     * @description handle clicking outside the dialog
     *              - close the dialog
     */
    handleClickOutsideDialog(event){
      let targetElement = event.target;

      // check if the targeted element is dialog backdrop
      if( targetElement.classList.contains('dialog-backdrop') ){
        SweetAlert.hideDialogForId(this.id);
      }
    }
  },
  mounted(){
    //todo: handle code highlight - some notes contain it but prismjs is not working with Vue.js
  }
};
</script>

<!-- Style -->
<style scoped>

.cancel-button {
  margin-left: 10px;
}

.dialog-container {
  overflow-y: auto;
}

</style>