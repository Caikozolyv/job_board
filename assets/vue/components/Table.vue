<script>
import {defineComponent, capitalize, toRaw} from 'vue'
import {
  BButton,
  BButtonGroup,
  BContainer,
  BFormInput,
  BLink,
  BTable,
  BFormSelect,
  BFormSelectOption
} from "bootstrap-vue-next";
import Form from './Form.vue';
import CellSelect from './CellSelect.vue';
import axios from "axios";

export default defineComponent({
  name: "Table.vue",
  data() {
    return {
      createMode: false,
      edit: null,
      items: [],
      displayFields: [],
      websites: [],
      websiteSelected: null,
      presences: [],
      presenceSelected: null,
      actions: [],
      actionsSelected: []
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
          // hide ids from table
          if (itemsKeys.includes('id')) {
            itemsKeys.splice(itemsKeys.indexOf('id'), 1)
          }
          let itemsKeysFiltered = itemsKeys.filter((key) => !key.startsWith("@"));
          itemsKeysFiltered.push('action_bar');
          self.displayFields = itemsKeysFiltered;
        })
        .catch(error => {
          console.log(error.response);
        });
  },
  components: {
    BLink,
    BFormInput,
    Form,
    BButtonGroup,
    BButton,
    BContainer,
    BTable,
    BFormSelect,
    BFormSelectOption,
    CellSelect
  },
  methods: {
    capitalize,
    createNew() {
      this.createMode = !this.createMode;
    },
    async editLine(item) {
      let id = item.id
      // edit validation
      if (this.edit === id) {
        // check if this is jobs board
        if (item.presence && item.website && item.actions) {
          item['presence'] = toRaw(this.presences.find((presence) => presence.id === this.presenceSelected));
          item['website'] = toRaw(this.websites.find((website) => website.id === this.websiteSelected));
          item['actions'] = toRaw(this.actions.filter((action) => this.actionsSelected.includes(action.id)));
        }

        // var name matching in DefaultStateProcessor ; find a way to be dynamic
        let tempObj = this.prepareObjectToSend(item);

        let patchUrl = 'http://localhost/api/' + this.objectName + '/' + id;
        await axios
          .patch(patchUrl, { tempObj },
          {
            headers: {
              'Content-type': 'application/merge-patch+json'
            }
          })
          .then(function(response) {
            // display toast if 200
          })
          .catch(error => {
            console.log(error.response);
          });
      // clicking on edit to fetch websites presences and actions
      // to populate select inputs
      } else if (
          this.edit !== id
          && item.website
          && item.presence
          && item.actions
      ) {
        this.websiteSelected = item.website.id;
        this.presenceSelected = item.presence.id;
        this.actionsSelected = item.actions.map((action) => action.id);
        let getWebsitesUrl = 'http://localhost/api/websites';
        let getPresencesUrl = 'http://localhost/api/presences';
        let getActionsUrl = 'http://localhost/api/actions';
        let self = this;

        await axios
            .get(getWebsitesUrl, {},
                {
                  headers: {
                    'Content-type': 'application/ld+json'
                  }
                })
            .then(function(response) {
              self.websites = response.data.member;
              // display toast if 200
            })
            .catch(error => {
              console.log(error.response);
            });
        await axios
            .get(getPresencesUrl, {},
                {
                  headers: {
                    'Content-type': 'application/ld+json'
                  }
                })
            .then(function(response) {
              self.presences = response.data.member;
              // display toast if 200
            })
            .catch(error => {
              console.log(error.response);
            });
        await axios
            .get(getActionsUrl, {},
                {
                  headers: {
                    'Content-type': 'application/ld+json'
                  }
                })
            .then(function(response) {
              self.actions = response.data.member;
              // display toast if 200
            })
            .catch(error => {
              console.log(error.response);
            });
      }
      this.edit = this.edit !== id ? id : null;
    },
    cancel(item) {
      let id = item.id;
      this.edit = this.edit !== id ? id : null;
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
    async updateStatus(item, status) {
      item.status = status;
      let tempObj = this.prepareObjectToSend(item);
      let patchUrl = 'http://localhost/api/' + this.objectName + '/' + item.id;
      await axios
          .patch(patchUrl, {tempObj},
              {
                headers: {
                  'Content-type': 'application/merge-patch+json'
                }
              })
          .then(function (response) {
            // display toast if 200
          })
          .catch(error => {
            console.log(error.response);
          });
    },
    getRowClass(item, type) {
      // guard clause if not a row (header, footer...)
      if (!item || type !== 'row' || item.status === 1) {
        return '';
      }

      // TODO make it dynamic with axios call to get status enum
      switch (item.status) {
        case 2:
          return 'answered';
        case 3:
          return 'rejected';
        case 4:
          return 'meeting';
        case 5:
          return 'success';
      }
    },
    // simplyfying patch for simple objects (action, presence, website)
    // keep only useful information ; remove @ fields and id
    prepareObjectToSend(item) {
      let tempObj = {};
      Object.keys(this.formFields).forEach((key, value) => {
        if (Object.hasOwn(item, key)) {
          tempObj[key] = item[key];
        }
      });
      return tempObj;
    },
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
        hover
        :items="this.items"
        :fields="this.displayFields"
        primary-key="id"
        :tbody-tr-class="getRowClass"
    >
      <!-- For all cells in table -->
      <template v-slot:cell()="{ value, item, field: { key }}">
        <template v-if="edit !== item.id">{{ value }}</template>
        <BFormInput v-else v-model="item[key]" />
      </template>

      <template #cell(url)="{ item, field: { key }}">
        <BLink
          v-if="edit !== item.id"
          :href="item.url"
          target="_blank"
        >
          offre
        </BLink>
        <BFormInput v-else v-model="item[key]" />
      </template>

      <template #cell(website)="{ value, item, field: { key } }">
        <template v-if="edit !== item.id">{{ item.website.name }}</template>
        <CellSelect
          v-else
          :values="this.websites"
          v-model:selected="this.websiteSelected"
          objectName="websites"
          :multiple="false"
      />
      </template>

      <template #cell(presence)="{ value, item, field: { key } }">
        <template v-if="edit !== item.id">{{ item.presence.name }}</template>
        <CellSelect
            v-else
            :values="this.presences"
            v-model:selected="this.presenceSelected"
            objectName="presences"
            :multiple="false"
        />
      </template>

      <template #cell(actions)="{ value, item, field: { key }}">
        <template v-if="edit !== item.id" v-for="action in item.actions">
          <span>{{ action.name }}</span><br>
        </template>
        <CellSelect
            v-else
            :values="this.actions"
            v-model:selected="this.actionsSelected"
            objectName="actions"
            :multiple="true"
        />
      </template>

      <template #cell(action_bar)="row">
        <BButtonGroup size="sm">
          <BButton variant="primary" @click="editLine(row.item)">{{ edit === row.item.id ? 'Save' : 'Edit' }}</BButton>
          <BButton v-if="edit === row.item.id" variant="danger" @click="cancel(row.item)">Cancel</BButton>
          <BButton v-if="edit !== row.item.id" variant="danger" @click="deleteLine(row.item)">Delete</BButton>
          <a v-if="row.item.status === 1" type="button" @click="updateStatus(row.item, 2)">✅</a>
          <a type="button" @click="updateStatus(row.item, 3)">❌</a>
        </BButtonGroup>
      </template>

    </BTable>
    <b-container v-if="createMode">
      <Form :formFields="this.formFields" :objectName="this.objectName" @cancel="createNew()"/>
    </b-container>
    <div v-else>
      <BButton @click="createNew()" size="sm" variant="success">Create new {{ objectName }}</BButton>
      <BButton v-if="this.objectName !== 'jobs'" href="http://localhost/jobs" size="sm">Back to job board</BButton>
    </div>

  </b-container>
</template>

<style>
  .answered {
    --bs-table-bg: #F8CDA0;
    --bs-table-hover-bg: #F5BA7A;
  }

  .rejected {
    --bs-table-bg: #FB9B88;
    --bs-table-hover-bg: #F97B62;
  }

  .meeting {
    --bs-table-bg: #FFE485;
    --bs-table-hover-bg: #FFDC5C;
  }

  .success {
    --bs-table-bg: #C6D8AF;
    --bs-table-hover-bg: #A7C284;
  }
</style>