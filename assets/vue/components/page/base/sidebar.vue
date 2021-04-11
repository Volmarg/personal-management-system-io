<!-- Template -->
<template>
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">

      <!-- Logo -->
      <a class="sidebar-brand" href="index.html">
        <span class="align-middle">AdminKit</span>
      </a>

      <ul class="sidebar-nav">
        <li class="sidebar-header">
          Pages
        </li>
        <single-menu-node
          shown-text="Dashboard"
          feathers-icon-name="sliders"
        />
        <single-menu-node
            shown-text="Notes"
            feathers-icon-name="book"
        >
          <template #submenu>
            <nested-menu-node :nodes="parentChildDtoArray"
                              :show="true"
            />
          </template>
        </single-menu-node>
      </ul>

    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import SingleMenuNodeComponent from './sidebar/single-menu-node';
import NestedMenuNodeComponent from './sidebar/nested-menu-node';
import SymfonyRoutes           from "../../../../scripts/core/symfony/SymfonyRoutes";

import GetParentsChildrenCategoriesHierarchyResponse from "../../../../scripts/core/dto/module/notes/GetParentsChildrenCategoriesHierarchyResponse";
import ParentChildDto                                from "../../../../scripts/core/dto/ParentChildDto";

export default {
  data(){
    return {
      parentChildDtoArray: []
    }
  },
  components: {
    "single-menu-node" : SingleMenuNodeComponent,
    "nested-menu-node" : NestedMenuNodeComponent,
  },
  methods: {
    /**
     * @description return the notes categories hierarchy to build men u nodes
     */
    getNotesCategoriesHierarchy(){

      this.axios.get(SymfonyRoutes.GET_NOTES_CATEGORIES_HIERARCHY).then( (response) => {
        let apiResponse = GetParentsChildrenCategoriesHierarchyResponse.fromAxiosResponse(response);
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