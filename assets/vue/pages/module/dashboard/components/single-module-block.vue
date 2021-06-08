<!-- Template -->
<template>

  <div class="tile wrapper-tile"
        @click="$emit('singleModuleBlockClicked')"
  >
    <div class="tile is-parent small-tile">

      <router-link v-if="isUrlSet"
                   :to="targetUrl"
                  :class="'router-link-tile'">
        <single-block
            :icon="icon"
            :text="text"
        />

      </router-link>

      <single-block
          v-else
          :icon="icon"
          :text="text"
      />

    </div>
  </div>

</template>

<!-- Script -->
<script>

import SingleBlockComponent from "./single-block";
import StringUtils          from "../../../../../scripts/core/utils/StringUtils";

export default {
  props: {
    text: {
      type     : String,
      required : true,
    },
    icon: {
      required : false,
      default  : "",
    },
    targetUrl: {
      type     : String,
      required : false,
      default  : "",
    }
  },
  emits: [
      'singleModuleBlockClicked'
  ],
  computed: {
    /**
     * @description will check if route name is set or not
     */
    isUrlSet(){
      return !StringUtils.isEmptyString(this.targetUrl);
    }
  },
  components: {
    'single-block' : SingleBlockComponent,
  }
}
</script>

<!-- Style -->
<style scoped>
@media(min-width: 800px) {
  .wrapper-tile, .small-tile {
    max-width: 200px;
    max-height: 200px;
  }
}

.small-tile article {
  padding: 0;
  padding-bottom: 15px;
  padding-top: 10px;
}

.wrapper-tile:hover {
  opacity: 0.7;
  cursor: pointer;
}

.router-link-tile {
  width: 100%;
}
</style>