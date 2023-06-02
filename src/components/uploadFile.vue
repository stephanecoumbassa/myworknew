<template>
  <div>
    <form>
      <q-input ref="fileInput" type="file" @change="uploadFile"/>
      <q-btn label="Upload" @click.prevent="submit"/>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  methods: {
    submit() {
      this.$refs.fileInput.click()
    },
    uploadFile() {
      let input = this.$refs.fileInput
      let file = input.files[0]

      let formData = new FormData()
      formData.append('file', file)

      axios.post('/server/upload', formData).then(response => {
        console.log(response.data)
      })
    }
  }
}
</script>
