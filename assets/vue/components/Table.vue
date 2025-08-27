<script>
import {defineComponent, capitalize} from 'vue'
import {BButton, BButtonGroup, BContainer, BTable} from "bootstrap-vue-next";
import Form from './Form.vue';
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
  components: {Form, BButtonGroup, BButton, BContainer, BTable},
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
    async deleteLine(item) {
      let self = this;
      await axios
        .delete('http://localhost/api/' + this.objectName + '/' + item.id, {},
            {
              headers: {
                'Content-type': 'application/ld+json'
              }
            })
        .then(function(response) {
          if (response.status === 204) {
            let index = self.items.indexOf(item);
            if (index !== -1) {
              self.items.splice(index, 1);
            }
          }
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    updateStatus(objectId) {

    }
  },
  props: {
    objectName: String,
    formFields: Object
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
        :primary-key="this.items.id"
    >
      <template #cell(actions)="row">
        <BButtonGroup size="sm">
          <BButton variant="primary" @click="editLine(id)">Edit</BButton>
          <BButton variant="danger" @click="deleteLine(row.item)">Delete</BButton>
          <BButton variant="warning" @click="updateStatus(id)">Update</BButton>
        </BButtonGroup>
      </template>

    </BTable>
    <b-container v-if="createMode">
      <Form :formFields="this.formFields" :objectName="this.objectName" @cancel="createNew()"/>
    </b-container>
    <div v-else>
      <BButton @click="createNew()" size="sm" variant="success">Create new {{ objectName }}</BButton>
      <BButton href="http://localhost/jobs" size="sm">Back to job board</BButton>
    </div>

  </b-container>
</template>

<style scoped>

</style>