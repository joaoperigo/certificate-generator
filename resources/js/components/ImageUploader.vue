<template>
  <div class="image-uploader" @dragover.prevent @drop.prevent="onDrop">
    <div v-if="!isReady" class="text-stone-200 p-4">
      Certificado precisa ser salvo antes de fazer upload de imagens.
    </div>
    <div v-else class="upload-area min-h-[191px]" :class="{ 'drag-over': isDragging }">
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
    <div v-if="uploadProgress > 0 && uploadProgress < 100" class="mt-2">
      <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-purple-600 h-2.5 rounded-full" :style="{ width: uploadProgress + '%' }"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { XCircleIcon, PhotoIcon } from '@heroicons/vue/24/solid'
import axios from 'axios'

export default {
  components: {
    XCircleIcon,
    PhotoIcon,
  },
  props: {
    certificateId: {
      type: Number,
      required: true,
      validator: value => !isNaN(value) && value > 0
    },
    pageNumber: {
      type: Number,
      required: true,
      validator: value => !isNaN(value) && value >= 0
    },
    currentImageUrl: {
      type: String,
      default: null
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      selectedFile: null,
      previewUrl: null,
      isDragging: false,
      uploadProgress: 0
    }
  },
  watch: {
    pageNumber: {
      handler() {
        this.resetState();
      },
      immediate: true
    },
    currentImageUrl: {
      handler(newUrl) {
        if (!newUrl) {
          this.resetState();
        }
      },
      immediate: true
    }
  },
  computed: {
    isReady() {
      return this.certificateId && this.certificateId > 0;
    }
  },
  methods: {
    resetState() {
      this.selectedFile = null;
      this.previewUrl = null;
      this.isDragging = false;
      this.uploadProgress = 0;
    },
    onFileSelected(event) {
      this.$emit('loading-start');
      this.handleFile(event.target.files[0]);
    },
    onDrop(event) {
      this.isDragging = false
      const file = event.dataTransfer.files[0]
      if (file && file.type.startsWith('image/')) {
        this.handleFile(file)
      }
    },
    async handleFile(file) {
      // Debug log para verificar os valores
      console.log('Props values:', {
        certificateId: this.certificateId,
        pageNumber: this.pageNumber,
        typeofCertificateId: typeof this.certificateId,
        typeofPageNumber: typeof this.pageNumber
      });

      // Validação mais específica
      if (!Number.isInteger(this.certificateId) || this.certificateId <= 0) {
        console.error('Invalid certificateId:', this.certificateId);
        alert('Invalid Certificate ID. Please save the certificate first.');
        return;
      }

      if (!Number.isInteger(this.pageNumber) || this.pageNumber < 0) {
        console.error('Invalid pageNumber:', this.pageNumber);
        alert('Invalid Page Number.');
        return;
      }

      // Verificação do tamanho do arquivo
      const maxSize = 10 * 1024 * 1024; // 10MB
      if (file.size > maxSize) {
        alert(`File is too large. Maximum size is 10MB. Your file is ${(file.size / 1024 / 1024).toFixed(2)}MB`);
        return;
      }

      try {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('certificate_id', this.certificateId);
        formData.append('page_number', this.pageNumber);

        const response = await axios.post('/api/images', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          onUploadProgress: (progressEvent) => {
            this.uploadProgress = Math.round(
              (progressEvent.loaded * 100) / progressEvent.total
            );
          }
        });

        // Criar preview local antes de emitir o evento
        this.selectedFile = file;
        this.createPreview(file);

        // Emitir a URL da imagem salva
        if (response.data.success) {
          this.$emit('image-selected', response.data.url);
          this.uploadProgress = 0;
        } else {
          throw new Error(response.data.message || 'Upload failed');
        }
        this.$emit('loading-end');
      } catch (error) {
        this.$emit('loading-end');
        console.error('Upload failed:', {
          message: error.message,
          response: error.response?.data,
          status: error.response?.status
        });
        
        alert(`Failed to upload image: ${error.response?.data?.message || error.message}`);
        this.uploadProgress = 0;
      }
    },
    createPreview(file) {
      const reader = new FileReader()
      reader.onload = (e) => {
        this.previewUrl = e.target.result
      }
      reader.readAsDataURL(file)
    },
    removeImage() {
      this.selectedFile = null
      this.previewUrl = null
      this.uploadProgress = 0
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