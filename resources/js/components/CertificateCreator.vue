<!-- CertificateCreator.vue -->
<template>
    <div class="certificate-creator h-screen flex relative overflow-hidden w-full bg-stone-900">
      <!-- Left Sidebar -->
      <div 
        :class="['absolute left-0 top-0 rounded-lg h-full w-[300px] bg-stone-800 transition-transform duration-300 overflow-y-auto custom-scrollbar pb-20', 
                 {'transform -translate-x-[calc(100%-3rem)] overflow-y-hidden bg-transparent disappear-sidebar': isLeftSidebarCollapsed}]"
      >
        <sidebar-toggle 
          position="left" 
          :is-collapsed="isLeftSidebarCollapsed" 
          @toggle="toggleLeftSidebar"
          class="absolute right-2 top-2 z-10"
        />
        <div>
        <!-- Left sidebar content -->
        <div>
          <div class="mt-10 mb-0 p-4 border-b border-b-stone-600">
            <label for="title" class="block text-stone-200 text-base font-bold mb-2">Certificate title:</label>
            <input 
              v-model="certificate.title" 
              type="text" 
              id="title" 
              required
              class="shadow appearance-none  rounded w-full py-2 px-3 text-stone-900 bg-stone-100 border-b-4 border-stone-600 leading-tight focus:outline-none focus:shadow-outline text-xl"
              placeholder="title:"
            >
          </div>
          
          <!-- <hr class="mt-8 mb-2 border-b-2 border-b-stone-700"> -->
          <page-selector 
            :pages="pages" 
            :currentPage="currentPage"
            @add-page="addPage" 
            @switch-page="switchPage"
            @delete-page="deletePage"
            class="p-4 border-b border-stone-600"
          ></page-selector>
          <!-- <hr class="my-8"> -->
          <image-uploader 
            :currentImageUrl="currentPageBackgroundImage"
            @image-preview="previewBackgroundImage"
            @remove-image="removeBackgroundImage"
            @image-selected="setBackgroundImage"
            class="p-4 border-b border-stone-600"
          ></image-uploader>
          
          <!-- <hr class="my-8"> -->
          
          <button 
            @click="saveCertificate" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-3 rounded-full fixed w-64 bottom-4 start-4 flex content-center items-center gap-4 bg-gradient-to-r from-teal-400 to-blue-500 hover:from-pink-500 hover:to-orange-500 border border-b-4 border-stone-300"
          >
            <BookmarkIcon class="w-7 h-7 ms-2"/>
            <div class="text-xl">Save Certificate</div>
          </button>
  
          <div class="columns-2 pb-4 px-4">
            <div class="border-e border-stone-700 pt-4 pe-4">
              <button 
                @click="generateJSON" 
                class="border border-b-4 border-stone-300 hover:bg-stone-700 text-white font-bold py-2 px-4 rounded-xl w-full mb-2"
              >
                <span class="text-2xl">{ ... }</span>
                Generate JSON
              </button>
            </div>
            <div class="pt-4 ps-4">
              <certificate-download 
                v-if="isCertificateDataReady"
                :certificate-data="certificateData"
              ></certificate-download>
            </div>
          </div>
        </div>
      </div>
      </div>
      
      <!-- Main Content -->
      <div 
        :class="['flex-grow bg-stone-900 p-4 overflow-auto transition-all duration-300 px-12', mainContentClass]"
      >
        <canvas-editor 
          :current-page="currentPage" 
          :pages="pages"
          class="h-full w-full"
        ></canvas-editor>
      </div>
      
      <!-- Right Sidebar -->
      <div 
        :class="['absolute right-0 top-0 h-full w-[300px] bg-stone-800 transition-transform duration-300 overflow-y-auto', 
                 {'transform translate-x-[calc(100%-3rem)] overflow-y-hidden': isRightSidebarCollapsed}]"
      >
        <sidebar-toggle 
          position="right" 
          :is-collapsed="isRightSidebarCollapsed" 
          @toggle="toggleRightSidebar"
          class="absolute left-2 top-2 z-10"
        />
        
        <!-- Right sidebar content -->
        <div class="p-4">
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
    </div>
  </template>
  
  <script>
  import PageSelector from './PageSelector.vue'
  import ImageUploader from './ImageUploader.vue'
  import CanvasEditor from './CanvasEditor.vue'
  import AddParagraph from './AddParagraph.vue'
  import ObjectList from './ObjectList.vue'
  import CertificateDownload from './CertificateDownload.vue'
  import SidebarToggle from './SidebarToggle.vue'
  
  import { BookmarkIcon } from '@heroicons/vue/24/solid'
  
  import axios from 'axios'
  
  export default {
    components: {
      PageSelector,
      ImageUploader,
      CanvasEditor,
      AddParagraph,
      ObjectList,
      CertificateDownload,
      BookmarkIcon,
      SidebarToggle
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
        certificateData: null,
        previewBackgroundImage: null,
        isLeftSidebarCollapsed: false,
        isRightSidebarCollapsed: false,
      }
    },
    computed: {
      currentPageObjects() {
        return this.pages[this.currentPage]?.objects || []
      },
      currentPageBackgroundImage() {
        return this.pages[this.currentPage]?.backgroundImage || this.previewBackgroundImage
      },
      isCertificateDataReady() {
        return this.certificateData && this.certificateData.pages && this.certificateData.pages.length > 0
      },
      mainContentClass() {
        let marginLeft = this.isLeftSidebarCollapsed ? 'ml-12' : 'ml-[300px]'
        let marginRight = this.isRightSidebarCollapsed ? 'mr-12' : 'mr-[300px]'
        return `${marginLeft} ${marginRight}`
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
      this.updateCertificateData()
    },
    methods: {
      addPage() {
        this.pages.push({
          backgroundImage: null,
          objects: []
        })
        this.currentPage = this.pages.length - 1
      },
      switchPage(pageIndex) {
        this.currentPage = pageIndex
      },
      deletePage(index) {
        if (this.pages.length > 1) {
          this.pages.splice(index, 1)
          this.currentPage = Math.max(0, this.currentPage - 1)
          this.updateCertificateData()
        } else {
          alert('Não é possível deletar a última página.')
        }
      },
      setBackgroundImage(imageUrl) {
        this.pages[this.currentPage].backgroundImage = imageUrl
      },
      previewBackgroundImage(imageUrl) {
        this.previewBackgroundImage = imageUrl
      },
      removeBackgroundImage() {
        this.pages[this.currentPage].backgroundImage = null
        this.previewBackgroundImage = null
      },
      addObject(object) {
        if (!this.pages[this.currentPage].objects) {
          this.pages[this.currentPage].objects = []
        } 
        this.pages[this.currentPage].objects.push(object)
        console.log('Object Added:', object)
        this.updateCertificateData()
      },
      updateObject(index, updatedObject) {
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
        }
      },
      toggleLeftSidebar() {
        this.isLeftSidebarCollapsed = !this.isLeftSidebarCollapsed
      },
      toggleRightSidebar() {
        this.isRightSidebarCollapsed = !this.isRightSidebarCollapsed
      }
    }
  }
  </script>
  
  <style scoped>
/* Estilo para a barra de rolagem */
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #666666 rgba(0,0,0,0);
}

/* Para navegadores WebKit (Chrome, Safari, etc.) */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #ff00cc;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #ffc000;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #FF0000;
}
/* Disappear sidebar on press */
 .disappear-sidebar > div {
  @apply opacity-0 pointer-events-none;
}
/* .disappear-sidebar .not-disappear-toggle {
  opacity: 1!important
}
.disappear-sidebar .not-disappear-toggle path {
  opacity: 1!important
} */
  </style>