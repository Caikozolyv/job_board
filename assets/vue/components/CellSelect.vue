<script>
import {defineComponent} from 'vue'
import {BContainer, BFormSelect, BFormSelectOption, BLink} from "bootstrap-vue-next";

export default defineComponent({
  name: "CellSelect",
  components: {BContainer, BLink, BFormSelectOption, BFormSelect},
  props: ['values', 'selected', 'objectName' ,'multiple'],
  emits: ['update:selected'],
  data() {
    return {
      elements: this.values,
      item: null,
    }
  },
  methods: {
    handleUpdate(selectedValue) {
      this.$emit('update:selected', selectedValue);
    }
  }
})
</script>

<template>
  <BContainer>
    <BFormSelect
        :model-value="selected"
        :selected="selected"
        :multiple="multiple"
        @update:model-value="handleUpdate"
    >
      <BFormSelectOption
          v-for="element in elements"
          :value="element.id"
      >
        {{ element.name }}
      </BFormSelectOption>
    </BFormSelect>
    <BLink
        class="btn btn-primary me-2"
        :href="'http://localhost/' + objectName"
        size="sm"
    >Create new</BLink>
  </BContainer>
</template>

<style scoped>

</style>