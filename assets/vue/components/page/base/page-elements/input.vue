<!-- Template -->
<template>
  <input :type="type"
         :class="{
          'border'        : !isValid,
          'border-danger' : !isValid
         }"
         class="form-control"
         :placeholder="placeholder"
         v-model="modelValue"
  />

</template>

<!-- Script -->
<script>

export default {
  data(){
    return {
      modelValue: "",
    }
  },
  props: {
    isValid : {
      type     : Boolean,
      required : false,
      default  : true,
    },
    type: {
      type     : String,
      required : false,
      default  : "text",
    },
    placeholder: {
      type     : String,
      required : false,
      default  : "",
    },
    value: {
      required : false,
      default  : "",
    }
  },
  methods: {
    /**
     * @description will set the model value
     */
    setModelValue(newValue){
      this.modelValue = newValue;
    }
  },
  beforeMount(){
    this.setModelValue(this.value);
  }
}
</script>