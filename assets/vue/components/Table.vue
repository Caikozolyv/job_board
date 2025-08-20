<script>
import {defineComponent, capitalize} from 'vue'

export default defineComponent({
  name: "Table.vue",
  methods: {
    capitalize,
    editLine(objectId) {

    },
    deleteLine(objectId) {

    }
  },
  props: {
    datas: Array
  }
})
</script>

<template>
  <h1 class="title">{{ capitalize(this.datas['object']) }}</h1>
  <table>
    <thead>
      <tr>
        <th v-for="column in this.datas['cols']" :key="column.id">
          {{ column }}
        </th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="values in this.datas['values']" :key="values.id">
        <td v-for="value in values" :key="values.id">
          {{ value }}
        </td>
        <td @click="editLine(values.id)">Edit</td>
        <td @click="deleteLine(values.id)">Delete</td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>

</style>