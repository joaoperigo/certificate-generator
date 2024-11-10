<template>
    <div class="certificate-viewer p-4 mt-20">
  
      <div class="flex flex-col md:flex-row">
        <div class="w-full md:w-2/3 pr-0 md:pr-4 mb-4 md:mb-0 absolute left-0 top-0 h-full transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth px-10 pb-20">
          <div class="mb-4 flex items-center justify-between pt-20 px-2">
            <button @click="prevPage" :disabled="currentPage === 0" class="bg-blue-500 text-white px-4 py-2 rounded disabled:bg-gray-300">Previous Page</button>
            <span>Page {{ currentPage + 1 }} of {{ pages.length }}</span>
            <button @click="nextPage" :disabled="currentPage === pages.length - 1" class="bg-blue-500 text-white px-4 py-2 rounded disabled:bg-gray-300">Next Page</button>
          </div>
          <canvas-editor
            :current-page="currentPage"
            :pages="pages"
            class="w-full h-auto border border-gray-300"
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
            <!-- <certificate-download 
              ref="certificateDownload"
              :certificate-data="certificate"
            ></certificate-download> -->
            <button @click="downloadPDF" class="bg-blue-500 text-white px-4 py-4 rounded-lg w-full">Download PDF</button>
          </div>
        </div>
      </div>
  
      <!-- <div class="mt-4 flex justify-between">
        <button @click="saveCertificate" class="bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
        <button @click="downloadPDF" class="bg-blue-500 text-white px-4 py-2 rounded">Download PDF</button>
      </div> -->
  

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
        currentPage: 0
      }
    },
    computed: {
      pages() {
        return this.certificate.pages || []
      },
      currentPageObjects() {
        return this.pages[this.currentPage]?.objects || []
      }
    },
    watch: {
      certificate: {
        deep: true,
        handler() {
          console.log('Certificate updated:', this.certificate);
        }
      }
    },
    methods: {
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
          const newObjects = [...this.pages[this.currentPage].objects];
          newObjects[index] = { ...newObjects[index], ...updatedObject };
          this.pages[this.currentPage].objects = newObjects;
        }
      },
      async downloadPDF() {
        if (this.$refs.certificateDownload) {
          try {
            await this.$refs.certificateDownload.downloadCertificate()
          } catch (error) {
            console.error('Error downloading PDF:', error)
            alert('Error downloading PDF. Please try again.')
          }
        } else {
          console.error('CertificateDownload component not found')
          alert('Unable to download PDF. Component not found.')
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