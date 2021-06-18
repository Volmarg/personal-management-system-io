<!-- Template -->
<template>
  <div class="row d-flex justify-content-end">
    <div class="mb-3 col-lg-2 col-xs-12">
      <b><label for="exampleInputEmail1" class="form-label">{{ trans('pages.passwords.search.label') }}</label></b>
      <input type="email" class="form-control" id="exampleInputEmail1"
             :placeholder="trans('pages.passwords.search.placeholder')"
             @input="$emit('searchInputChanged', searchInput)"
             v-model="searchInput"
      >
    </div>
  </div>
</template>

<!-- Script -->
<script type="ts">
export default {
  data(){
    return {
      searchInput : "",
    }
  },
  emits: [
      'searchInputChanged'
  ]
}
</script>