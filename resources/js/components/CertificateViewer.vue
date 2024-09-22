<!-- CertificateViewer.vue -->
<template>
    <div class="certificate-viewer">
      <h1 class="text-2xl font-bold mb-4">{{ certificate.title }}</h1>
      
      <page-selector 
        :pages="pages" 
        :currentPage="currentPage"
        @switch-page="switchPage"
      ></page-selector>
  
      <div class="flex">
        <div class="w-2/3 pr-4">
          <canvas-editor
            :current-page="currentPage"
            :pages="pages"
            class="w-full h-auto"
          ></canvas-editor>
        </div>
        
        <div class="w-1/3">
          <object-list
            :objects="currentPageObjects"
            @update-object="updateObject"
          ></object-list>
        </div>
      </div>
  
      <certificate-download
        v-if="isCertificateDataReady"
        :certificate-data="certificate"
        class="mt-4"
      ></certificate-download>
  
      <button @click="saveCertificate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
        Save Changes
      </button>
    </div>
  </template>
  
  <script>
  import PageSelector from './PageSelector.vue'
  import CanvasEditor from './CanvasEditor.vue'
  import ObjectList from './ObjectList.vue'
  import CertificateDownload from './CertificateDownload.vue'
  import axios from 'axios'
  
  export default {
    components: {
      PageSelector,
      CanvasEditor,
      ObjectList,
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
        certificate: { ...this.initialCertificate },
        currentPage: 0
      }
    },
    computed: {
      pages() {
        return this.certificate.pages || []
      },
      currentPageObjects() {
        return this.pages[this.currentPage]?.objects || []
      },
      isCertificateDataReady() {
        return this.certificate && this.certificate.pages && this.certificate.pages.length > 0
      }
    },
    methods: {
      switchPage(pageIndex) {
        this.currentPage = pageIndex
      },
      updateObject(index, updatedObject) {
        this.pages[this.currentPage].objects[index] = updatedObject
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