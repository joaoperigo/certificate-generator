<!-- ImageUploader.vue -->
<template>
  <div class="image-uploader" @dragover.prevent @drop.prevent="onDrop">
    <div class="upload-area" :class="{ 'drag-over': isDragging }">
      <div v-if="!previewUrl && !currentImageUrl" class="upload-prompt">
        <PhotoIcon class="h-9 w-9 text-stone-400"/>
        <p class="text-stone-200 text-sm font-normal mt-1 mb-2">Image size must be <br> <b>303.02mm</b> by <b>215.98mm</b></p>
        <label class="file-input-label">
          Choose a File
          <input type="file" @change="onFileSelected" accept="image/*" class="hidden">
        </label>
      </div>
      <div v-else class="image-preview">
        <img :src="previewUrl || currentImageUrl" alt="Preview" class="preview-image">
        <button @click="removeImage" class="remove-button p-0">
          <XCircleIcon class="h-7 w-7 text-purple-500"/>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { XCircleIcon, PhotoIcon } from '@heroicons/vue/24/solid'

export default {
  components: {
    XCircleIcon,
    PhotoIcon,
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
        this.$emit('image-selected', newUrl);
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
        this.$emit('image-selected', e.target.result)
      }
      reader.readAsDataURL(file)
    },
    removeImage() {
      this.selectedFile = null
      this.previewUrl = null
      this.$emit('remove-image')
    }
  }
}
</script>

<style scoped>
.image-uploader {
  @apply max-w-md mx-auto;
}

.upload-area {
  @apply border border-dashed border-stone-600 bg-stone-900 rounded-lg p-6 text-center cursor-pointer transition-all duration-300 ease-in-out;
}

.drag-over {
  @apply border-purple-500 bg-purple-50;
}

.upload-prompt {
  @apply flex flex-col items-center;
}

.file-input-label {
  @apply mt-2 px-4 py-2 bg-stone-100 text-stone-900 border border-b-4 border-stone-600 rounded-2xl cursor-pointer hover:bg-purple-600 hover:text-slate-100 transition-colors duration-300;
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
</style>