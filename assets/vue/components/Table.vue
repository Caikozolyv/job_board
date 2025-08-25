<script>
import {defineComponent, capitalize} from 'vue'
import {BButton, BButtonGroup, BContainer, BTable} from "bootstrap-vue-next";
import axios from "axios";

export default defineComponent({
  name: "Table.vue",
  data() {
    return {
      createMode: false,
      items: [],
      displayFields: [],
    }
  },
  async mounted() {
    let self = this;
    await axios
        .get('http://localhost/api/' + this.objectName, {},
            {
              headers: {
                'Content-type': 'application/ld+json'
              }
            })
        .then(function(response) {
          self.items = response.data.member;
          let itemsKeys = Object.keys(response.data.member[0]);
          // if (itemsKeys.includes('id')) {
          //   itemsKeys.splice(itemsKeys.indexOf('id'), 1)
          // }
          let itemsKeysFiltered = itemsKeys.filter((key) => !key.startsWith("@"));
          itemsKeysFiltered.push('actions')
          self.displayFields = itemsKeysFiltered;
        })
        .catch(error => {
          console.log(error.response);
        });
  },
  components: {BButtonGroup, BButton, BContainer, BTable},
  methods: {
    capitalize,
    createNew() {
      this.createMode = !this.createMode;
    },
    validate() {

    },
    goToJobBoard() {

    },
    editLine(objectId) {

    },
    deleteLine(objectId) {

    },
    updateStatus(objectId) {

    }
  },
  props: {
    objectName: String,
  },
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
    <b-container v-if="createMode">
<!--  form component    -->
    </b-container>
    <div v-else>
      <BButton @click="createNew()" size="sm" variant="success">Create new {{ objectName }}</BButton>
      <BButton href="http://localhost/jobs" size="sm">Back to job board</BButton>
    </div>

  </b-container>
</template>

<style scoped>

</style>