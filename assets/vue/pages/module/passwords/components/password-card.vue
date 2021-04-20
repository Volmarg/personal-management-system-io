<!-- Template -->
<template>

  <div class="col-12 col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">

        <div class="row">
          <p class="card-text"><i>{{ description }}</i></p>
        </div>

        <section class="mt-3">
          <div><b>Login:</b> <span>{{ login }}</span></div>
          <div><b>Url:</b> <span>{{ url }}</span></div>
        </section>

        <section class="actions">
          <a href="#" class="card-link" @click="showPasswordClick(id)">Show password</a>
        </section>

      </div>
    </div>
  </div>

  <sweet-alert :cancel-button-string="'OK'"
               :id="'passwordDialog' + id"
               :dialog-content="shownPassword"
               :ref="'passwordDialog' + id"

  >

  </sweet-alert>

</template>

<!-- Script -->
<script type="ts">

import SymfonyRoutes from "../../../../../scripts/core/symfony/SymfonyRoutes";

import GetDecryptedPasswordResponseDto  from "../../../../../scripts/core/dto/module/passwords/GetDecryptedPasswordResponseDto";
import SweetAlert                       from "../../../../components/dialog/sweet-alert/sweet-alert.vue";

export default {
  data(){
    return {
      shownPassword: "",
    }
  },
  props: {
    description: {
      type     : String,
      required : false,
      default  : ""
    },
    login: {
      type     : String,
      required : true
    },
    url: {
      type     : String,
      required : true,
      default   : ""
    },
    id: {
      type     : Number,
      required : true
    }
  },
  components: {
    "sweet-alert": SweetAlert
  },
  methods: {
    /**
     * @description will decrypt the password of given entity id and returns the raw password from database
     */
    decryptPasswordForId(id){

      let url = SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_DECRYPT_PASSWORD, {
        [SymfonyRoutes.ROUTE_NAME_DECRYPT_PASSWORD_PARAM_PASSWORD_ID] : id
      })

      this.axios.get( url ).then( (response) => {
        let decryptedPasswordResponse = GetDecryptedPasswordResponseDto.fromAxiosResponse(response);

        this.shownPassword = decryptedPasswordResponse.decryptedPassword;
        this.$refs['passwordDialog' + id].showDialog();
      })

    },
    /**
     * @description handles clicking on the `show password`
     */
    showPasswordClick(passwordId){
      this.decryptPasswordForId(passwordId);
    }
  }
}

</script>

<!-- Style -->
<style scoped>
.actions {
  margin-top: 10px;
}
</style>