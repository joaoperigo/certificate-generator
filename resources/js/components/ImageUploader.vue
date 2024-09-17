<!-- ImageUploader.vue -->
<template>
  <div class="image-uploader bg-white" @dragover.prevent @drop.prevent="onDrop">
    <div class="upload-area" :class="{ 'drag-over': isDragging }">
      <div v-if="!previewUrl && !currentImageUrl" class="upload-prompt">
        <i class="fas fa-cloud-upload-alt text-4xl mb-2"></i>
        <p>The image size must be <b>303.02mm</b> wide by <b>215.98mm</b> high</p>
        <label class="file-input-label">
          Escolha um arquivo
          <input type="file" @change="onFileSelected" accept="image/*" class="hidden">
        </label>
      </div>
      <div v-else class="image-preview">
        <img :src="previewUrl || currentImageUrl" alt="Preview" class="preview-image">
        <button @click="removeImage" class="remove-button p-0">
          <XCircleIcon class="h-7 w-7 text-blue-500"/>
        </button>
      </div>
      <button @click="uploadImage" :disabled="!selectedFile && !currentImageUrl" class="mt-6 upload-button">
        Add Image to page >>
      </button>
    </div>
  </div>
</template>

<script>
import { XCircleIcon } from '@heroicons/vue/24/solid'

export default {
  components: {
    XCircleIcon
  },
  props: {
    currentImageUrl: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      selectedFile: null,
      previewUrl: null,
      isDragging: false
    }
  },
  watch: {
    currentImageUrl(newUrl) {
      if (newUrl) {
        this.previewUrl = null;
      }
    }
  },
  methods: {
    onFileSelected(event) {
      this.handleFile(event.target.files[0])
    },
    onDrop(event) {
      this.isDragging = false
      const file = event.dataTransfer.files[0]
      if (file && file.type.startsWith('image/')) {
        this.handleFile(file)
      }
    },
    handleFile(file) {
      this.selectedFile = file
      this.createPreview(file)
    },
    createPreview(file) {
      const reader = new FileReader()
      reader.onload = (e) => {
        this.previewUrl = e.target.result
        this.$emit('image-preview', e.target.result)
      }
      reader.readAsDataURL(file)
    },
    removeImage() {
      this.selectedFile = null
      this.previewUrl = null
      this.$emit('remove-image')
    },
    uploadImage() {
      if (this.selectedFile) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.$emit('image-selected', e.target.result)
        }
        reader.readAsDataURL(this.selectedFile)
        this.selectedFile = null
      } else if (this.currentImageUrl) {
        this.$emit('image-selected', this.currentImageUrl)
      }
    }
  }
}
</script>

<style scoped>
.image-uploader {
  @apply max-w-md mx-auto;
}

.upload-area {
  @apply border-2 border-dashed border-gray-300 rounded-lg p-6 mb-4 text-center cursor-pointer transition-all duration-300 ease-in-out;
}

.drag-over {
  @apply border-blue-500 bg-blue-50;
}

.upload-prompt {
  @apply flex flex-col items-center;
}

.file-input-label {
  @apply mt-2 px-4 py-2 bg-blue-500 text-white rounded cursor-pointer hover:bg-blue-600 transition-colors duration-300;
}

.image-preview {
  @apply relative;
}

.preview-image {
  @apply max-w-full h-auto rounded-lg;
}

.remove-button {
  @apply absolute top-2 right-2 bg-stone-50 text-white rounded-full hover:bg-stone-500 transition-colors duration-300;
}

.upload-button {
  @apply w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed;
}
</style>