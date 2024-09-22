<template>
  <div class="certificate-editor">
    <certificate-creator
      :initial-certificate="certificate"
      @save="saveCertificate"
    ></certificate-creator>
  </div>
</template>

<script>
import CertificateCreator from './CertificateCreator.vue'
import axios from 'axios'

export default {
  components: {
    CertificateCreator
  },
  props: {
    certificateId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      certificate: null
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
    async saveCertificate(updatedCertificate) {
      try {
        await axios.put(`/certificates/${this.certificateId}`, updatedCertificate)
        alert('Certificate updated successfully!')
      } catch (error) {
        console.error('Error updating certificate:', error)
        alert('Error updating certificate. Please try again.')
      }
    }
  }
}
</script>