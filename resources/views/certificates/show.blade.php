<template>
  <div class="certificate-viewer">
    <h1>{{ certificate.title }}</h1>
    
    <page-selector 
      :pages="pages" 
      :currentPage="currentPage"
      @switch-page="switchPage"
    ></page-selector>

    <canvas-editor
      :current-page="currentPage"
      :pages="pages"
    ></canvas-editor>

    <object-list
      :objects="currentPageObjects"
      @update-object="updateObject"
    ></object-list>

    <certificate-download
      v-if="isCertificateDataReady"
      :certificate-data="certificate"
    ></certificate-download>

    <button @click="saveCertificate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
    certificateId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      certificate: null,
      currentPage: 0
    }
  },
  computed: {
    pages() {
      return this.certificate ? this.certificate.pages : []
    },
    currentPageObjects() {
      return this.pages[this.currentPage]?.objects || []
    },
    isCertificateDataReady() {
      return this.certificate && this.certificate.pages && this.certificate.pages.length > 0
    }
  },
  mounted() {
    this.loadCertificate()
  },
  methods: {
    async loadCertificate() {
      try {
        const response = await axios.get(`/certificates/${this.certificateId}/data`)
        this.certificate = response.data
      } catch (error) {
        console.error('Error loading certificate:', error)
      }
    },
    switchPage(pageIndex) {
      this.currentPage = pageIndex
    },
    updateObject(index, updatedObject) {
      this.pages[this.currentPage].objects[index] = updatedObject
    },
    async saveCertificate() {
      try {
        await axios.put(`/certificates/${this.certificateId}/update-texts`, this.certificate)
        alert('Certificate texts updated successfully!')
      } catch (error) {
        console.error('Error updating certificate texts:', error)
        alert('Error updating certificate texts. Please try again.')
      }
    }
  }
}
</script>