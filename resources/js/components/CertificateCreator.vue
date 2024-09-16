<!-- CertificateCreator.vue -->
<template>
    <div class="certificate-creator flex h-screen">
      <!-- Left Sidebar -->
      <div class="w-1/5 bg-gray-100 p-4 overflow-y-auto">
        
        <div class="mb-4">
          <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Certificate title:</label>
          <input 
            v-model="certificate.title" 
            type="text" 
            id="title" 
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          >
        </div>
        
        <page-selector 
          :pages="pages" 
          @add-page="addPage" 
          @switch-page="switchPage"
          class="mb-4"
        ></page-selector>
        
        <image-uploader 
          @image-selected="setBackgroundImage"
          class="mb-4"
        ></image-uploader>
        
        <button 
          @click="generateJSON" 
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mb-2"
        >
          Generate JSON
        </button>
        
        <button 
          @click="saveCertificate" 
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
        >
          Save Certificate
        </button>
        <certificate-download 
            v-if="isCertificateDataReady"
            :certificate-data="certificateData"
        ></certificate-download>
    </div>
      
      <!-- Main Content -->
      <div class="w-3/5 bg-white p-4 ">
        <canvas-editor 
          :current-page="currentPage" 
          :pages="pages"
          class="w-full h-full"
        ></canvas-editor>
      </div>
      
      <!-- Right Sidebar -->
      <div class="w-1/5 bg-gray-100 p-4 overflow-y-auto">
        <add-paragraph 
          @add-paragraph="addObject"
          class="mb-6"
        ></add-paragraph>
        
        <object-list 
          :objects="currentPageObjects" 
          @update-object="updateObject"
          @delete-object="deleteObject"
        ></object-list>
      </div>
    </div>
  </template>
  
  <script>
  import PageSelector from './PageSelector.vue'
  import ImageUploader from './ImageUploader.vue'
  import CanvasEditor from './CanvasEditor.vue'
  import AddParagraph from './AddParagraph.vue'
  import ObjectList from './ObjectList.vue'
  import CertificateDownload from './CertificateDownload.vue'

  import axios from 'axios' // Certifique-se de ter o axios instalado e importado
//   axios.defaults.baseURL = 'http://127.0.0.1:8000';
  
  export default {
    components: {
      PageSelector,
      ImageUploader,
      CanvasEditor,
      AddParagraph,
      ObjectList,
      CertificateDownload
    },
    data() {
      return {
        certificate: {
          title: '',
          data: ''
        },
        pages: [
          {
            backgroundImage: null,
            objects: []
          }
        ],
        currentPage: 0,
        jsonOutput: null,
        certificateData: null
      }
    },
    computed: {
      currentPageObjects() {
        return this.pages[this.currentPage]?.objects || []
      },
      isCertificateDataReady() {
      return this.certificateData && this.certificateData.pages && this.certificateData.pages.length > 0;
        }
    },
    watch: {
    certificate: {
      handler: 'updateCertificateData',
      deep: true
    },
    pages: {
      handler: 'updateCertificateData',
      deep: true
    }
  },
  mounted() {
    this.updateCertificateData();
  },
    methods: {
      addPage() {
        this.pages.push({
          backgroundImage: null,
          objects: []
        })
      },
      switchPage(pageIndex) {
        this.currentPage = pageIndex
      },
      setBackgroundImage(imageUrl) {
        this.pages[this.currentPage].backgroundImage = imageUrl
      },
      addObject(object) {
        if (!this.pages[this.currentPage].objects) {
          this.pages[this.currentPage].objects = []
        }
        this.pages[this.currentPage].objects.push(object)
      },
      updateObject(index, updatedObject) {
        // Converter mm para pixels para posição e largura da caixa
        updatedObject.xPos *= 3.779528
        updatedObject.yPos *= 3.779528
        if (updatedObject.boxWidth) {
          updatedObject.boxWidth *= 3.779528
        }
        
        this.pages[this.currentPage].objects[index] = updatedObject
      },
      deleteObject(index) {
        this.pages[this.currentPage].objects.splice(index, 1)
      },
      generateJSON() {
        const certificateData = {
          title: this.certificate.title,
          pages: this.pages
        }
        this.jsonOutput = JSON.stringify(certificateData, null, 2)
        this.certificate.data = this.jsonOutput
        
        // Exibir o JSON em um alert (você pode modificar isso para exibir de outra forma)
        alert(this.jsonOutput)
      },
      async saveCertificate() {
      this.generateJSON()
      try {
        const response = await axios.post('/certificates', {
          title: this.certificate.title,
          data: this.certificate.data
        })
        
        console.log('Certificate saved:', response.data)
        alert('Certificate saved successfully!')
        
        // Opcional: redirecionar para a página de listagem ou edição
        // window.location.href = '/certificates';
      } catch (error) {
        console.error('Error saving certificate:', error)
        alert('Error saving certificate. Please try again.')
      }
    },
    updateCertificateData() {
      this.certificateData = {
        title: this.certificate.title,
        pages: this.pages.map(page => ({
          backgroundImage: page.backgroundImage,
          objects: page.objects.map(obj => ({
            text: obj.text,
            fontFamily: obj.fontFamily,
            fontSize: obj.fontSize,
            fontColor: obj.fontColor,
            xPos: obj.xPos,
            yPos: obj.yPos,
            boxWidth: obj.boxWidth,
            textAlign: obj.textAlign
          }))
        }))
      };
    },
    }
  }
  </script>
  
  <style scoped>
  /* Adicione estilos personalizados aqui, se necessário */
  </style>