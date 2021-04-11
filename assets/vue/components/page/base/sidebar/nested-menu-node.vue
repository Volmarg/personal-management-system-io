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
      <span class="d-flex justify-content-around">
        <a class="sidebar-link collapsed">
          {{ node.name }}
        </a>

        <span
            v-if="hasNodeChildren(node)"
            :data-bs-target="hasNodeChildren(node) ? '#multi-' + node.id : ''"
            :data-bs-toggle="hasNodeChildren(node) ? 'collapse' : ''"
            class="collapsed cursor-pointer"
            aria-expanded="false"
        ></span>
        <span v-else
              class="collapsed hidden"
              aria-expanded="false"
        >
        </span>
      </span>
      <nested-menu-node :nodes="node.children"
                        :node-identifier="node.id"
      ></nested-menu-node>
    </li>
  </ul>
</template>

<!-- Script -->
<script type="ts">
import NestedMenuNode from "./nested-menu-node";

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
    }
  },
  components: {
    'nested-menu-node': NestedMenuNode
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