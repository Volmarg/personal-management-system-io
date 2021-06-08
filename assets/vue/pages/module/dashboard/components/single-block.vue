<!-- Template -->
<template>
  <article class="tile is-child notification is-info d-flex flex-column justify-content-around">

    <span class="tile-icon d-flex justify-content-center">
      <i
          class="align-middle"
          v-if="isIconSet"
          :data-feather="icon"
      ></i>
    </span>

    <p class="subtitle d-flex justify-content-center mt-3 mb-0">
      <span>{{ text }}</span>
    </p>

  </article>
</template>

<!-- Script -->
<script>
import StringUtils from "../../../../../scripts/core/utils/StringUtils";

export default {
  props: {
    text: {
      type     : String,
      required : true,
    },
    icon: {
      required : false,
      default  : "",
    }
  },
  computed: {
    /**
     * @description checks if icon is being set
     */
    isIconSet(){
      return !StringUtils.isEmptyString(this.icon);
    }
  }
}
</script>

<!-- Styles -->
<style scoped>
.tile-icon svg {
  height: 40px;
  width: 40px;
}

.subtitle span {
  font-size: 18px;
}
</style>