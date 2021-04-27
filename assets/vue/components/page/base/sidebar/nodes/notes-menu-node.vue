<!-- Template -->
<template>
  <single-menu-node
      :shown-text="trans('sidebar.menuNodes.notes.label')"
      feathers-icon-name="book"
      :submenu-id="'multi-' + notesCategoriesMenuNodeId"
      :show-collapse="parentChildDtoArray.length !== 0"
  >
    <template #submenu>
      <nested-menu-node :nodes="parentChildDtoArray"
                        :node-identifier="notesCategoriesMenuNodeId"
                        :to-path-name="routeNameModuleNotesCategory"
                        :to-id-param-name="routeNameModuleNotesCategoryIdParam"
      />
    </template>
  </single-menu-node>
</template>

<!-- Script -->
<script type="ts">

import SymfonyRoutes from "../../../../../../scripts/core/symfony/SymfonyRoutes";

import NestedMenuNodeComponent from "../nested-menu-node.vue";
import SingleMenuNodeComponent from "../single-menu-node.vue";

import ParentsChildrenCategoriesHierarchyDto from "../../../../../../scripts/core/dto/module/notes/ParentsChildrenCategoriesHierarchyDto";
import ParentChildDto                        from "../../../../../../scripts/core/dto/ParentChildDto";

export default {
  data(){
    return {
      parentChildDtoArray                 : [],
      notesCategoriesMenuNodeId           : "notesCategories",
      routeNameModuleNotesCategory        : SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY,
      routeNameModuleNotesCategoryIdParam : SymfonyRoutes.ROUTE_NAME_MODULE_NOTES_CATEGORY_ID_PARAM,
    }
  },
  components: {
    "nested-menu-node" : NestedMenuNodeComponent,
    "single-menu-node" : SingleMenuNodeComponent,
  },
  methods: {
    /**
     * @description return the notes categories hierarchy to build men u nodes
     */
    getNotesCategoriesHierarchy(){

      this.axios.get(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_GET_NOTES_CATEGORIES_HIERARCHY)).then( (response) => {
        let apiResponse          = ParentsChildrenCategoriesHierarchyDto.fromAxiosResponse(response);
        this.parentChildDtoArray = apiResponse.hierarchy.map( (object) => {
          return ParentChildDto.fromObject(object);
        });
      });

    },
  },
  beforeMount() {
    this.getNotesCategoriesHierarchy();
  }
}

</script>