<script>
import {capitalize, defineComponent} from 'vue'
import {BButton, BContainer, BFormInput, BRow, BToast} from "bootstrap-vue-next";
import axios from 'axios'

export default defineComponent({
  name: "Form.vue",
  data() {
    return {
      text: '',
      // axiosToast: false,
      // axiosMessage: '',
      values: this.formFields
    }
  },
  components: {BToast, BButton, BFormInput, BRow, BContainer},
  props: ['formFields', 'objectName'],
  emits: ['cancel'],
  methods: {
    capitalize,
    async validate() {
      // let self = this;
      let dataToSend = this.getDataToSubmit();

      await axios
          .post('http://localhost/api/' + this.objectName,
              dataToSend, {
            headers: {
              'Content-type': 'application/ld+json'
            }
          })
          .then(function(response) {
            // self.axiosToast = true;
            // self.axiosMessage = response.request.statusText;
            if (response.status === 201) {
              window.location.reload();
            }
          })
          .catch(error => {
            console.log(error.response);
          });
    },
    cancel() {
      this.$emit('cancel');
    },
    getDataToSubmit() {
      let keys = (Object.keys(this.formFields));
      let dataToSend = {};
      for (let i = 0; i < keys.length; i++) {
        dataToSend[keys[i]] = this.formFields[keys[i]].content;
      }
      return dataToSend;
    }
  }
})
</script>

<template>
  <b-container>
<!--    <b-toast v-if="axiosToast" id="axiosToast" title="{{ capitalize(this.objectName) }} creation" static>-->
<!--      {{ this.axiosMessage }}-->
<!--    </b-toast>-->
    <BRow
        v-for="(field, index) in this.values" :key="index"
        class="my-1"
    >
      <label :for="index">{{ index }}</label>
      <BFormInput
        :id="index"
        :type="field['type']"
        v-model="field['content']"
      />
    </BRow>
    <BButton @click="validate()" size="sm" variant="success">Validate</BButton>
    <BButton @click="cancel()" size="sm" variant="danger">Cancel</BButton>
  </b-container>
</template>

<style scoped>

</style>