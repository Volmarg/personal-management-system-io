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
          <a href="#" class="card-link">Show password</a>
        </section>

      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script type="ts">

/**
 * todo: handle decrypt password on show click
 * todo: add backend method
 */
export default {
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
    }
  },
  methods: {
    /**
     * @description will decrypt the password of given entity id and returns the raw password from database
     */
    decryptPasswordForId(id){

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