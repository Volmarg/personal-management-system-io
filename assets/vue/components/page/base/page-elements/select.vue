<!-- Template -->
<template>

  <select class="form-select" ref="select" v-model="selectedOption">
    <option v-for="(name, value) in options"
            :value="value">{{ name }}</option>
  </select>

</template>

<!-- Script -->
<script type="ts">
export default {
  props: {
    options: {
      type     : Object,
      required : true
    }
  },
  data(){
    return{
      selectedOption: null,
    }
  }
}
</script>