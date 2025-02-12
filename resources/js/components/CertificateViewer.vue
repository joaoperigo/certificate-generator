<template>
  <div class="certificate-viewer p-4 mt-20">
    <div class="flex flex-col md:flex-row">
      <div class="w-full md:w-2/3 pr-0 md:pr-4 mb-4 md:mb-0 absolute left-0 top-0 h-full transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth px-10 pb-20">
        <div class="mb-4 flex items-center justify-between pt-20 px-2">
          <button @click="prevPage" :disabled="currentPage === 0" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300">Previous Page</button>
          <span>Page {{ currentPage + 1 }} of {{ pages.length }}</span>
          <button @click="nextPage" :disabled="currentPage === pages.length - 1" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300">Next Page</button>
        </div>
        
        <canvas-editor
          :current-page="currentPage"
          :pages="processedPages"
          class="w-full h-auto border border-gray-300"
          ref="canvasEditor"
        ></canvas-editor>
      </div>
      
      <div class="w-full md:w-1/3 absolute right-0 top-0 h-full transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth">
        <div class="pb-20 bg-stone-100 mt-24 px-10">
          <h1 class="text-2xl font-bold mb-4">{{ certificate.title }}</h1>
          <div v-for="(object, index) in currentPageObjects" :key="index" class="mb-4">
            <h3 class="font-bold">{{ object.objectName }}</h3>
            <textarea 
              v-model="object.text" 
              @input="updateObject(index, object)"
              class="w-full p-2 border rounded"
              rows="3"
            ></textarea>
          </div>
          <certificate-download 
            ref="certificateDownload"
            :certificate-data="certificate"
            buttonClasses="border border-b-4 border-stone-300 hover:bg-purple-400 bg-purple-500 text-white font-bold py-6 px-4 rounded-xl w-full mb-2" 
          ></certificate-download>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CanvasEditor from './CanvasEditor.vue'
import CertificateDownload from './CertificateDownload.vue'
import axios from 'axios'

export default {
  components: {
    CanvasEditor,
    CertificateDownload
  },
  props: {
    initialCertificate: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      certificate: JSON.parse(JSON.stringify(this.initialCertificate)),
      currentPage: 0,
      loadedImages: new Map() // Cache para as imagens carregadas
    }
  },
  computed: {
    pages() {
      return this.certificate.pages || []
    },
    currentPageObjects() {
      return this.pages[this.currentPage]?.objects || []
    },
    processedPages() {
      // Retorna uma cópia das páginas com as imagens carregadas do cache
      return this.pages.map(page => ({
        ...page,
        backgroundImage: this.loadedImages.get(page.backgroundImage) || page.backgroundImage
      }))
    }
  },
  watch: {
    'pages.length': {
      immediate: true,
      handler(newLength) {
        if (newLength > 0) {
          this.preloadImages()
        }
      }
    },
    currentPage() {
      // Pré-carrega imagens quando a página muda
      this.preloadImages()
    }
  },
  methods: {
    async preloadImages() {
      // Pré-carrega a imagem da página atual e da próxima
      const pagesToLoad = [this.currentPage]
      if (this.currentPage < this.pages.length - 1) {
        pagesToLoad.push(this.currentPage + 1)
      }

      for (const pageIndex of pagesToLoad) {
        const imageUrl = this.pages[pageIndex]?.backgroundImage
        if (imageUrl && !this.loadedImages.has(imageUrl)) {
          try {
            const response = await fetch(imageUrl)
            const blob = await response.blob()
            const imageDataUrl = await this.blobToDataURL(blob)
            this.loadedImages.set(imageUrl, imageDataUrl)
          } catch (error) {
            console.error('Error loading image:', error)
          }
        }
      }
    },
    blobToDataURL(blob) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader()
        reader.onload = () => resolve(reader.result)
        reader.onerror = reject
        reader.readAsDataURL(blob)
      })
    },
    prevPage() {
      if (this.currentPage > 0) {
        this.currentPage--
      }
    },
    nextPage() {
      if (this.currentPage < this.pages.length - 1) {
        this.currentPage++
      }
    },
    updateObject(index, updatedObject) {
      if (this.pages[this.currentPage] && this.pages[this.currentPage].objects) {
        const newObjects = [...this.pages[this.currentPage].objects]
        newObjects[index] = { ...newObjects[index], ...updatedObject }
        this.pages[this.currentPage].objects = newObjects
      }
    },
    async saveCertificate() {
      try {
        await axios.put(`/certificates/${this.certificate.id}/update-texts`, {
          data: JSON.stringify(this.certificate)
        })
        alert('Certificate texts updated successfully!')
      } catch (error) {
        console.error('Error updating certificate texts:', error)
        alert('Error updating certificate texts. Please try again.')
      }
    }
  }
}
</script>

<style scoped>
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #666666 rgba(0,0,0,0);
}

.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #666666;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #888888;
}
</style>