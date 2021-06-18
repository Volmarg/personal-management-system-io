<!-- Template -->
<template>
  <article class="tile is-child notification is-info d-flex flex-column justify-content-around">

    <span class="tile-icon d-flex justify-content-center">
      <span
            class="align-middle"
            v-if="isIconSet"
            v-html="getFeathersSvgIcon(icon)"
      >
      </span>
    </span>

    <p class="subtitle d-flex justify-content-center mt-3 mb-0">
      <span>{{ text }}</span>
    </p>

  </article>
</template>

<!-- Script -->
<script>
import StringUtils from "../../../../../scripts/core/utils/StringUtils";
import feather     from 'feather-icons'

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
  },
  methods: {
    /**
     * @description will return svg icon for provided feather icon name
     *              this is required as Vue.js has issue with handling feather icons when reloading content in SPA
     */
    getFeathersSvgIcon(iconName){
      return feather.toSvg(iconName);
    }
  }
}
</script>

<!-- Styles -->
<style scoped>
/** BUG: there is an issue with icons size now  */
.tile-icon svg.feather {
  height: 40px !important;
  width: 40px !important;;
}

.subtitle span {
  font-size: 18px;
}
</style>