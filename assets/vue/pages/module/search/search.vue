<!-- Template -->
<template>

  <page-card>
    <form @submit.prevent class="form">

      <!-- search input -->
      <div class="mb-2">
        <div class="input-group input-group-navbar">
          <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
          <button class="btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search align-middle"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </button>
        </div>
      </div>

      <!-- select list -->
      <div class="mb-2">
        <select-component
            :options="options"
            ref="moduleSelect"
            @change="onModuleSelectChange"
        />
      </div>

      <!-- search confirm -->
      <button class="btn btn-lg btn-info"
              @click="getSearchResults"
      >Search</button>

    </form>
  </page-card>

  <!-- results -->
  <section class="results-wrapper">
    <shown-notes :notes="showResults" v-if="selectedModule == supportedModuleNameNotes"/>
  </section>

</template>

<!-- Script -->
<script type="ts">
import PageCardComponent   from "../../../components/page/base/page-elements/card";
import SelectComponent     from "../../../components/page/base/page-elements/select";
import ShownNotesComponent from "../notes/components/shown-notes.vue";

import SymfonyRoutes       from "../../../../scripts/core/symfony/SymfonyRoutes";
import MyNoteDto           from "../../../../scripts/core/dto/module/notes/MyNoteDto";
import TranslatedModulesNameDto from "../../../../scripts/core/dto/module/search/TranslatedModulesNameDto";
import ToastifyService          from "../../../../scripts/libs/toastify/ToastifyService";
import SearchModule             from "../../../../scripts/core/module/search/SearchModule";



export default {
  data(){
    return {
      options        : {},
      showResults    : [],
      selectedModule : null,
      supportedModuleNameNotes     : SearchModule.SUPPORTED_MODULE_NOTES,
      supportedModuleNamePasswords : SearchModule.SUPPORTED_MODULES_PASSWORDS,
    }
  },
  components: {
    "shown-notes"      : ShownNotesComponent,
    "page-card"        : PageCardComponent,
    "select-component" : SelectComponent,
  },
  methods: {
    /**
     * @description will get the search results for provided parameters
     */
    getSearchResults(){
      let dataBag = {
        searchedString : "C",
        moduleName     : "notes", // todo: will need to make static from it
      };
      this.axios.post(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_SEARCH_GET_RESULTS), dataBag).then(response => {
        this.buildSearchResultsForModule(response.data.resultsJsons); // todo: not really - need dto first the get search result
      }).catch(response => {
        ToastifyService.showRedNotification(this.trans('general.responseCodes.500'))
        console.warn(response);
      })
    },
    /**
     * @description handles module selection change
     */
    onModuleSelectChange(){
      this.selectedModule = this.$refs.moduleSelect.selectedOption;
      console.log(this.selectedModule);
    },
    /**
     * @description will build search results depending on selected module
     */
    buildSearchResultsForModule(searchResults){

      switch(this.selectedModule){
        case "notes":
        {
          this.showResults = searchResults.map((json) => {
            return MyNoteDto.fromJson(json);
          })
        }
        break;

        default:
          throw {
            "info"   : "Selected module is not supported for building search result",
            "module" : this.selectedModule
          }
      }
    },
    /**
     * @description will return modules supported in search
     */
    getSupportedSearchModules()
    {
      this.axios.get(SymfonyRoutes.getPathForName(SymfonyRoutes.ROUTE_NAME_MODULE_GET_ALL_TRANSLATED_MODULES_NAMES_FOR_SEARCH)).then(response => {
        let translatedModulesNameDto = TranslatedModulesNameDto.fromAxiosResponse(response);
        this.options                 = translatedModulesNameDto.modulesNames;
      }).catch(response => {
          ToastifyService.showRedNotification(this.trans('general.responseCodes.500'))
          console.warn(response);
      })
    }
  },
  beforeMount(){
    this.getSupportedSearchModules();
  }
}
</script>
