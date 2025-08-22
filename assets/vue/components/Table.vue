<script>
import {defineComponent, capitalize} from 'vue'
import {BButton, BButtonGroup, BContainer, BTable} from "bootstrap-vue-next";

export default defineComponent({
  name: "Table.vue",
  components: {BButtonGroup, BButton, BContainer, BTable},
  methods: {
    capitalize,
    editLine(objectId) {

    },
    deleteLine(objectId) {

    },
    updateStatus(objectId) {

    }
  },
  props: {
    items: Array,
    objectName: String
  },
  computed: {
    displayFields() {
      let itemsKeys = Object.keys(this.items[0]);
      if (itemsKeys.includes('id')) {
        itemsKeys.splice(itemsKeys.indexOf('id'), 1)
      }
      itemsKeys.push('actions');
      return itemsKeys;
    }
  }
})
</script>

<template>
  <h1>{{ capitalize(this.objectName) }}</h1>
  <b-container>
    <BTable
        striped
        hover
        :items="this.items"
        :fields="this.displayFields"
        :primary-key="id"
    >
      <template #cell(actions)="data">
        <BButtonGroup size="sm">
          <BButton variant="primary" @click="editLine(id)">Edit</BButton>
          <BButton variant="danger" @click="deleteLine(id)">Delete</BButton>
          <BButton variant="warning" @click="updateStatus(id)">Update</BButton>
        </BButtonGroup>
      </template>

    </BTable>
    <BButton size="sm" variant="success">Create new</BButton>
  </b-container>
</template>

<style scoped>

</style>