<!-- Template -->
<template>
  <nav id="sidebar" class="sidebar">
    <div class="sidebar-content js-simplebar">

      <!-- Logo -->
      <router-link :to="{ name: routeNameHash}"
                   class="sidebar-brand logo-link"
      >
          <span class="align-middle logo-wrapper">
          <img src="/img/logo.png" class="logo-image"/>
        </span>
      </router-link>

      <!-- Links -->
      <ul class="sidebar-nav">
        <li class="sidebar-header"></li>
        <single-menu-node
          :shown-text="dashboardTranslationString"
          feathers-icon-name="sliders"
          :to-path-name="routeNameModuleDashboardOverview"
        />

        <single-menu-node
            :shown-text="notesTranslationString"
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
      </ul>

    </div>
  </nav>
</template>

<!-- Script -->
<script type="ts">
import SingleMenuNodeComponent from './sidebar/single-menu-node';
import NestedMenuNodeComponent from './sidebar/nested-menu-node';
import SymfonyRoutes           from "../../../../scripts/core/symfony/SymfonyRoutes";
import TranslationsService     from "../../../../scripts/core/service/TranslationsService";

import GetParentsChildrenCategoriesHierarchyResponse from "../../../../scripts/core/dto/module/notes/GetParentsChildrenCategoriesHierarchyResponse";
import ParentChildDto                                from "../../../../scripts/core/dto/ParentChildDto";
import Router                                        from "../../../../scripts/libs/vue/Router";

let translationsService = new TranslationsService();

export default {
  data(){
    return {
      parentChildDtoArray                 : [],
      notesCategoriesMenuNodeId           : "notesCategories",
      routeNameModuleNotesCategory        : Router.ROUTE_NAME_MODULE_NOTES_CATEGORY,
      routeNameModuleNotesCategoryIdParam : Router.ROUTE_NAME_MODULE_NOTES_CATEGORY_ID_PARAM,
      routeNameModuleDashboardOverview    : Router.ROUTE_NAME_MODULE_DASHBOARD_OVERVIEW,
      routeNameHash                       : Router.ROUTE_NAME_HASH,
      dashboardTranslationString          : translationsService.getTranslationForString('sidebar.menuNodes.dashboard.label'),
      notesTranslationString              : translationsService.getTranslationForString('sidebar.menuNodes.notes.label'),
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