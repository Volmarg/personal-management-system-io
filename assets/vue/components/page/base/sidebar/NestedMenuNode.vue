<!-- Template -->
<template>
  <ul :id="'multi-'+nodeIdentifier"
      class="sidebar-dropdown list-unstyled collapse"
      :class="{'show': show}"
      v-if="nodes.length !== 0"
  >
    <li v-for="node in nodes"
        class="sidebar-item"
    >
      <span class="d-flex justify-content-start">
        <RouterLink class="sidebar-link collapsed"
                     :to="
                      {
                        name   : toPathName,
                        params : {
                          [toIdParamName] : node.id
                        }
                      }"
        >
          {{ node.name }}
        </RouterLink>

        <span
            v-if="hasNodeChildren(node)"
            :data-bs-target="hasNodeChildren(node) ? '#multi-' + node.id : ''"
            :data-bs-toggle="hasNodeChildren(node) ? 'collapse' : ''"
            class="collapsed cursor-pointer ms-4"
            aria-expanded="false"
        ></span>
        <span v-else
              class="collapsed hidden"
              aria-expanded="false"
        >
        </span>
      </span>
      <NestedMenuNode :nodes="node.children"
                        :node-identifier="node.id"
                        :to-path-name="toPathName"
                        :to-id-param-name="toIdParamName"
      ></NestedMenuNode>
    </li>
  </ul>
</template>

<!-- Script -->
<script type="ts">
import NestedMenuNodeComponent from "./NestedMenuNode";

export default {
  props: {
    nodes: {
      type     : Array,
      required : true,
    },
    nodeIdentifier: {
      type     : String,
      required : false,
    },
    show: {
      type     : Boolean,
      required : false,
      default  : false
    },
    toPathName: {
      type     : String,
      required : true,
    },
    toIdParamName: {
      type     : String,
      required : true,
    }
  },
  components: {
    'NestedMenuNode': NestedMenuNodeComponent
  },
  methods: {
    /**
     * @description will check if the node has children (returns bool)
     */
    hasNodeChildren(node){
      return (node.children.length != 0);
    }
  }
}

</script>