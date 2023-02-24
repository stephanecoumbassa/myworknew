<template>
  <div>
    <form>
      <q-input type="file" ref="fileInput" @change="uploadFile"/>
      <q-btn label="Upload" @click.prevent="submit"/>
    </form>
  </div>
</template>

<script>
export default {
  methods: {
    submit() {
      this.$refs.fileInput.click()
    },
    uploadFile() {
      let input = this.$refs.fileInput
      console.log(input)
      let file = input.files[0]

      let formData = new FormData()
      formData.append('file', file)

      console.log(formData)

      this.$emit('uploaded', formData)
    }
  }
}
</script>
