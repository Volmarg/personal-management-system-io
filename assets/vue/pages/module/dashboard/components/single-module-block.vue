<!-- Template -->
<template>

  <div class="tile wrapper-tile">
    <div class="tile is-parent small-tile">

      <!-- block with link -->
      <router-link v-if="isUrlSet"
                   :to="targetUrl"
                   :class="'router-link-tile'">
        <section class="single-block-wrapper">
          <single-block
              :icon="icon"
              :text="text"
          />
        </section>
      </router-link>

      <!-- block without link - with alert -->
      <section v-else class="single-block-wrapper">
        <single-block
            :icon="icon"
            :text="text"
            @click="onSingleNonLinkModuleBlockClicked(uuidForSingleBlockWithoutTargetUrl)"
        />
      </section>

    </div>
  </div>

  <!-- alert for block without link, must be outside to keep alert centered -->
  <sweet-alert
               v-if="!isUrlSet"
               :cancel-button-string="'OK'"
               :id="'singleBlockAlert_' + uuidForSingleBlockWithoutTargetUrl"
               :ref="'singleBlockAlert_' + uuidForSingleBlockWithoutTargetUrl"
               :is-full-width-on-mobile="true"
  >
    <template #additionalDialogContent>
      <slot name="alertDialogContent"></slot>
    </template>
  </sweet-alert>

</template>

<!-- Script -->
<script>

import SingleBlockComponent from "./single-block";
import SweetAlertComponent  from "../../../../components/dialog/sweet-alert/sweet-alert"

import StringUtils          from "../../../../../scripts/core/utils/StringUtils";
import SymfonyRoutes        from "../../../../../scripts/core/symfony/SymfonyRoutes";

import { v4 as uuidv4 }     from 'uuid';

import ParentsChildrenCategoriesHierarchyDto from "../../../../../scripts/core/dto/module/notes/ParentsChildrenCategoriesHierarchyDto";
import ParentChildDto                        from "../../../../../scripts/core/dto/ParentChildDto";

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
  data() {
    return {
      uuidForSingleBlockWithoutTargetUrl: null,
    };
  },
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
    'sweet-alert'  : SweetAlertComponent,
  },
  methods: {
    /**
     * @description handles clicking on the single module block - but the one that is not working directly with
     */
    onSingleNonLinkModuleBlockClicked(alertTargetId){
      let targetAlert = this.$refs['singleBlockAlert_' + alertTargetId];
      targetAlert.showDialog();
    },
  },
  beforeMount() {
    this.uuidForSingleBlockWithoutTargetUrl = uuidv4();
  },
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

.single-block-wrapper {
  width: 100%;
  height: 100%;
}
</style>