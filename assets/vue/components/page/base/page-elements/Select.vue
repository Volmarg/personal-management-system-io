<!-- Template -->
<template>

  <select class="form-select" ref="select" v-model="selectedOption" :class="{
      'border'        : !isValid,
      'border-danger' : !isValid
    }">
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
      required : true,
    },
    isValid : {
      type     : Boolean,
      required : false,
    },
  },
  data(){
    return{
      selectedOption: null,
    }
  }
}
</script>