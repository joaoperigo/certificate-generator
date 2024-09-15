<!-- ImageUploader.vue -->
<template>
  <div class="image-uploader">
    <input type="file" @change="onFileSelected" accept="image/*">
    <button @click="uploadImage" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
      Upload Image
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectedFile: null
    }
  },
  methods: {
    onFileSelected(event) {
      this.selectedFile = event.target.files[0]
    },
    uploadImage() {
      if (this.selectedFile) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.$emit('image-selected', e.target.result)
        }
        reader.readAsDataURL(this.selectedFile)
      }
    }
  }
}
</script>