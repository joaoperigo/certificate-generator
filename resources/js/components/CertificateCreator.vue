<!-- CertificateCreator.vue -->
<template>
  <div class="certificate-creator h-screen flex relative overflow-hidden w-full bg-stone-900">
    <sidebar-toggle 
      position="right" 
      :is-collapsed="isRightSidebarCollapsed" 
      @toggle="toggleRightSidebar"
    />
    <sidebar-toggle 
      position="left" 
      :is-collapsed="isLeftSidebarCollapsed" 
      @toggle="toggleLeftSidebar"
    />
    
    <!-- Left Sidebar -->
    <div 
      :class="['absolute left-0 top-0 rounded-lg h-full w-[300px] bg-stone-800 transition-transform duration-300 overflow-y-auto custom-scrollbar pb-20', 
               {'transform -translate-x-[calc(100%-3rem)] overflow-y-hidden bg-transparent disappear-sidebar': isLeftSidebarCollapsed}]"
    >
      <div>
        <!-- Left sidebar content -->
        <div>
          <div class="mt-8 mb-0 p-4 border-b border-b-stone-600 pt-20">
            <label for="title" class="block text-stone-200 text-base font-bold mb-2">Certificate title:</label>
            <input 
              v-model="certificate.title" 
              type="text" 
              id="title" 
              required
              class="shadow appearance-none  rounded w-full py-2 px-3 text-stone-900 bg-stone-100 border-b-4 border-stone-600 leading-tight focus:outline-none focus:shadow-outline text-xl"
              placeholder="title:"
            >
            <label for="quantity_hours" class="block text-stone-200 text-base font-bold mb-2">Course Hours:</label>
            <input 
              v-model.number="certificate.quantity_hours" 
              type="number" 
              id="quantity_hours"
              class="shadow appearance-none rounded w-full py-2 px-3 text-stone-900 bg-stone-100 border-b-4 border-stone-600 leading-tight focus:outline-none focus:shadow-outline text-xl"
              placeholder="Course hours:"
            >
          </div>
          
          <div class="pt-4 px-4">
            <certificate-metadata
                :initial-categories="selectedCategories"
                :initial-teachers="selectedTeachers"
                @update:categories="updateCategories"
                @update:teachers="updateTeachers"
            />
          </div>
          
          <div class="pt-4 px-4 border-b border-stone-600">
            <h2 class="font-bold mb-2 text-stone-200 text-base">Page Orientation</h2>
            <div class="flex gap-4 mb-4">
              <button 
                @click="setOrientation('landscape')" 
                :class="['flex flex-col items-center justify-center border border-b-4 rounded-xl p-3 w-full', 
                        orientation === 'landscape' 
                          ? 'bg-stone-100 text-stone-900 border-purple-500' 
                          : 'bg-stone-700 text-stone-200 border-stone-600']"
              >
                <PhFile :size="24" class="mb-1 rotate-90" />
                <span>Landscape</span>
              </button>
              <button 
                @click="setOrientation('portrait')" 
                :class="['flex flex-col items-center justify-center border border-b-4 rounded-xl p-3 w-full', 
                        orientation === 'portrait' 
                          ? 'bg-stone-100 text-stone-900 border-purple-500' 
                          : 'bg-stone-700 text-stone-200 border-stone-600']"
              >
                <PhFile :size="24" class="mb-1" />
                <span>Portrait</span>
              </button>
            </div>
          </div>

          <page-selector 
            :pages="pages" 
            :currentPage="currentPage"
            @add-page="addPage" 
            @switch-page="switchPage"
            @delete-page="deletePage"
            class="p-4 border-b border-stone-600"
          ></page-selector>

          <!-- Modificar o ImageUploader para só aparecer depois que tiver ID -->
          <div v-if="!certificate.id" class="p-4 border-b border-stone-600 text-stone-200 text-center">
            <h2 class="text-xl font-black">Adicionar Imagem</h2>
            <p>Por favor, salve o certificado primeiro antes de adicionar imagens.</p>
          </div>
          
          <image-uploader 
            v-if="certificate.id"
            :key="getUploaderKey"
            :currentImageUrl="currentPageBackgroundImage"
            :certificate-id="Number(certificate.id)"
            :page-number="Number(currentPage)"
            @image-selected="setBackgroundImage"
            @remove-image="removeBackgroundImage"
            class="p-4 border-b border-stone-600"
          ></image-uploader>
                
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
            <div class="pt-4 ps-0">
              <certificate-download 
                v-if="isCertificateDataReady"
                :certificate-data="certificateData"
                buttonClasses="border border-b-4 border-stone-300 hover:bg-stone-700 text-white font-bold py-2 px-0 rounded-xl w-full mb-2" 
              ></certificate-download>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main Content -->
    <div 
      :class="['flex-grow bg-stone-900 p-4 overflow-auto transition-all duration-300 px-12 custom-scrollbar', mainContentClass]"
    >
      <canvas-editor 
        ref="canvasEditor"
        :current-page="currentPage" 
        :pages="pages"
        :width="currentDimensions.width"
        :height="currentDimensions.height"
        class="h-full w-full"
      ></canvas-editor>
    </div>
    
    <!-- Right Sidebar -->
    <div 
      :class="['absolute right-0 top-0 h-full w-[300px] bg-stone-800 transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth', 
               {'transform translate-x-[calc(100%-3rem)] overflow-y-hidden bg-transparent  disappear-sidebar': isRightSidebarCollapsed}]"
    >
      <div id="right-sidebar"></div>
      <div class="sticky top-20 end-0 inline-flex justify-end gap-5 w-full pb-2 pe-6 z-10 bg-stone-800">
        <a href="#right-sidebar" class="text-stone-200 grid grid-cols-1 w-30">
          <PhStackPlus :size="20" class="mx-auto"/>
          <div class="text-center text-sm text-stone-400">Add</div>
        </a>
        <a href="#objects-list" class="text-stone-200 grid grid-cols-1 w-30">
          <PhStack :size="20" class="mx-auto"/>
          <div class="text-center text-sm text-stone-400">Edit</div>
        </a>
      </div>
      
      <!-- Right sidebar content -->
      <div class="p-0 ">
        <div class="sticky top-20">
          <template-selector
            @applyTemplate="addObject"
            @addPage="addPage"
          ></template-selector>
          <quick-object-creator
            @create-object="addObject"
          ></quick-object-creator>
        </div>
        <add-paragraph 
          @add-paragraph="addObject"
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
import TemplateSelector from './TemplateSelector.vue'
import QuickObjectCreator from './QuickObjectCreator.vue'
import CertificateMetadata from './CertificateMetadata.vue'

import { BookmarkIcon } from '@heroicons/vue/24/solid'
import { PhStack, PhStackPlus, PhFile } from '@phosphor-icons/vue'

import axios from 'axios'

export default {
  components: {
    PageSelector,
    ImageUploader,
    CanvasEditor,
    TemplateSelector,
    QuickObjectCreator,
    AddParagraph,
    ObjectList,
    CertificateDownload,
    BookmarkIcon,
    SidebarToggle,
    PhStack,
    PhStackPlus,
    CertificateMetadata,
    PhFile,
  },
  props: {
    initialCertificate: {
      type: Object,
      default: () => ({
        title: '',
        data: '',
        pages: [{
          backgroundImage: null,
          objects: []
        }]
      })
    }
  },
  data() {
    return {
      certificate: {
        id: this.initialCertificate?.id ? Number(this.initialCertificate.id) : null,
        title: this.initialCertificate?.title || '',
        data: this.initialCertificate?.data || '',
        quantity_hours: this.initialCertificate?.quantity_hours || ''
      },
      pages: this.initialCertificate?.pages || [{
        backgroundImage: null,
        objects: []
      }],
      currentPage: 0,
      orientation: 'landscape', // Default value, will be updated in created()
      landscapeDimensions: {
        width: 303.02, // mm
        height: 215.98 // mm
      },
      portraitDimensions: {
        width: 215.98, // mm
        height: 303.02 // mm
      },
      uploaderKey: 0,
      jsonOutput: null,
      previewBackgroundImageUrl: null,
      isLeftSidebarCollapsed: false,
      isRightSidebarCollapsed: false,
      selectedCategories: this.initialCertificate?.categories || [],
      selectedTeachers: this.initialCertificate?.teachers || [],
      updateTimeout: null, // Add this for debouncing
      abortControllers: {
        categories: null,
        teachers: null
      },
      categories: [],
      teachers: []
    }
  },
  created() {
    // Initialize orientation from certificate data if available
    if (this.initialCertificate && this.initialCertificate.data) {
      try {
        const certData = JSON.parse(this.initialCertificate.data);
        if (certData.orientation) {
          this.orientation = certData.orientation;
        }
      } catch (e) {
        console.error('Error parsing certificate data:', e);
      }
    }
  },
  computed: {
    currentPageObjects() {
      return this.pages[this.currentPage]?.objects || []
    },
    currentPageBackgroundImage() {
      return this.pages[this.currentPage]?.backgroundImage || this.previewBackgroundImageUrl
    },
    isCertificateDataReady() {
      return this.certificateData && this.certificateData.pages && this.certificateData.pages.length > 0
    },
    certificateData() {
      return {
        title: this.certificate.title,
        orientation: this.orientation,
        dimensions: this.currentDimensions,
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
            textAlign: obj.textAlign,
            letterSpacing: obj.letterSpacing,
          }))
        }))
      };
    },
    mainContentClass() {
      let marginLeft = this.isLeftSidebarCollapsed ? 'ml-12' : 'ml-[300px]'
      let marginRight = this.isRightSidebarCollapsed ? 'mr-12' : 'mr-[300px]'
      return `${marginLeft} ${marginRight}`
    },
    getUploaderKey() {
      return `uploader-${this.currentPage}-${this.uploaderKey}`
    },
    currentDimensions() {
      return this.orientation === 'landscape' 
        ? this.landscapeDimensions 
        : this.portraitDimensions;
    },
  },
  watch: {
    certificate: {
      handler: 'updateCertificateData',
      deep: true
    },
    pages: {
      handler: 'updateCertificateData',
      deep: true
    },
    orientation: 'updateCertificateData'
  },
  mounted() {
    this.updateCertificateData();
    this.loadCategories();
    this.loadTeachers();
  },
  beforeUnmount() {
    // Cancel any pending requests when component is unmounted
    if (this.abortControllers.categories) {
      this.abortControllers.categories.abort();
    }
    if (this.abortControllers.teachers) {
      this.abortControllers.teachers.abort();
    }
  },
  methods: {
    addPage() {
      this.pages.push({
        backgroundImage: null,
        objects: []
      });
      this.currentPage = this.pages.length - 1;
      this.uploaderKey++; // Incrementa o contador
      this.updateCertificateData();
    },
    
    switchPage(pageIndex) {
      if (pageIndex !== this.currentPage) {
        this.currentPage = pageIndex;
        this.uploaderKey++; // Incrementa o contador ao trocar de página
      }
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
      console.log('Setting background image:', imageUrl);
      
      // Atualizar a página atual com a nova URL da imagem
      if (this.pages[this.currentPage]) {
        this.pages[this.currentPage].backgroundImage = imageUrl;
      
        // Atualizar o JSON do certificado
        this.updateCertificateData();
        
        // Salvar automaticamente para persistir a mudança
        this.saveCertificate();
      }
    },
    
    previewBackgroundImage(imageUrl) {
      this.previewBackgroundImageUrl = imageUrl
    },
    
    async removeBackgroundImage() {
      if (this.pages[this.currentPage]) {
        try {
          // Extrai o ID da imagem da URL
          const imageUrl = this.pages[this.currentPage].backgroundImage;
          if (imageUrl) {
            const imageId = imageUrl.split('/').pop();
            
            // Chama a API para deletar a imagem
            await axios.delete(`/api/images/${imageId}`);
          }
          // Atualiza o estado local
          this.pages[this.currentPage].backgroundImage = null;
          this.updateCertificateData();
          this.uploaderKey++; // Reset do uploader
          // Salva o certificado
          await this.saveCertificate();
        } catch (error) {
          console.error('Error removing image:', error);
          alert('Failed to remove image. Please try again.');
        }
      }
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
      this.pages[this.currentPage].objects[index] = updatedObject
    },
    
    deleteObject(index) {
      this.pages[this.currentPage].objects.splice(index, 1)
    },
    
    generateJSON() {
      const certificateData = {
        title: this.certificate.title,
        orientation: this.orientation,
        dimensions: this.currentDimensions,
        pages: this.pages
      }
      this.jsonOutput = JSON.stringify(certificateData, null, 2)
      this.certificate.data = this.jsonOutput
      console.log('JSON Output:', this.jsonOutput)
    },
    
    async saveCertificate() {
      if (!this.certificate.title.trim()) {
        alert('Certificate title is required.');
        return;
      }
      try {
        this.generateJSON();
        let response;
        const certificateData = {
          title: this.certificate.title,
          data: this.certificate.data,
          quantity_hours: this.certificate.quantity_hours
        };
        
        if (this.certificate.id) {
          response = await axios.put(`/certificates/${this.certificate.id}`, certificateData);
          
          // Use Promise.all to wait for both updates to complete
          await Promise.all([
            this.updateCategories(this.selectedCategories),
            this.updateTeachers(this.selectedTeachers)
          ]);
          
          console.log('Certificate updated:', response.data);
          alert('Certificate saved successfully!');
        } else {
          response = await axios.post('/certificates', certificateData);
          this.certificate.id = Number(response.data.id);
          
          // Now that we have an ID, update relationships
          await Promise.all([
            this.updateCategories(this.selectedCategories),
            this.updateTeachers(this.selectedTeachers)
          ]);
          
          console.log('Certificate created:', response.data);
          alert('Certificate created successfully!');
          window.location.href = `/certificates/${response.data.id}/edit`;
        }
      } catch (error) {
        console.error('Error saving certificate:', error);
        
        // Improved error handling for validation errors
        if (error.response && error.response.status === 422) {
          // Get validation errors
          const validationErrors = error.response.data.errors;
          let errorMessage = 'Please fix the following errors:';
          
          // Format error messages
          for (const field in validationErrors) {
            errorMessage += `\n- ${validationErrors[field].join(', ')}`;
          }
          
          alert(errorMessage);
        } else {
          // Generic error
          alert('Error saving certificate. Please try again.');
        }
      }
    },
    
    updateCertificateData() {
      // Clear any existing timeout
      if (this.updateTimeout) {
        clearTimeout(this.updateTimeout);
      }
      
      // Set a new timeout
      this.updateTimeout = setTimeout(() => {
        // Create deep copy to break reactive connections
        const pagesDeepCopy = JSON.parse(JSON.stringify(this.pages));
        
        const certificateData = {
          title: this.certificate.title,
          orientation: this.orientation,
          dimensions: this.currentDimensions,
          pages: pagesDeepCopy.map(page => ({
            backgroundImage: page.backgroundImage,
            objects: page.objects.map(obj => ({
              text: obj.text,
              fontFamily: obj.fontFamily,
              fontSize: obj.fontSize,
              fontColor: obj.fontColor,
              xPos: obj.xPos,
              yPos: obj.yPos,
              boxWidth: obj.boxWidth,
              textAlign: obj.textAlign,
              letterSpacing: obj.letterSpacing,
            }))
          }))
        };
        
        // Only update if data has changed
        const newDataString = JSON.stringify(certificateData);
        if (this.certificate.data !== newDataString) {
          this.certificate.data = newDataString;
          console.log('Updated certificate data:', certificateData);
        }
      }, 300); // 300ms debounce delay
    },
    
    setOrientation(orientation) {
      if (this.orientation === orientation) return;
      
      // Ask for confirmation if changing orientation with existing pages
      if (this.pages.length > 0) {
        if (!confirm(`Changing orientation might affect your existing pages. Continue?`)) {
          return;
        }
      }
      
      // Update orientation
      this.orientation = orientation;
      
      // Update canvas directly
      this.$nextTick(() => {
        if (this.$refs.canvasEditor) {
          this.$refs.canvasEditor.resizeCanvas(
            this.currentDimensions.width,
            this.currentDimensions.height
          );
        }
      });
    },
    
    updateCertificateDataNoRecursion() {
      const certificateData = {
        title: this.certificate.title,
        orientation: this.orientation,
        dimensions: this.currentDimensions,
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
            textAlign: obj.textAlign,
            letterSpacing: obj.letterSpacing,
          }))
        }))
      };
      
      this.certificate.data = JSON.stringify(certificateData);
      console.log('Updated certificate data:', certificateData);
    },
    
    toggleRightSidebar() {
      this.isRightSidebarCollapsed = !this.isRightSidebarCollapsed;
    },
    
    toggleLeftSidebar() {
      this.isLeftSidebarCollapsed = !this.isLeftSidebarCollapsed;
    },
    
    updateCategories(categories) {
  // Convert undefined or null to empty array
  const newCategories = categories || [];
  
  // Store the new selection
  this.selectedCategories = newCategories;
  
  // Only make API calls if we have a certificate ID
  if (this.certificate.id) {
    try {
      // Make the API call with whatever categories are selected (can be empty)
      return axios.post(`/certificates/${this.certificate.id}/categories`, {
        categories: newCategories.map(c => c.id)
      });
    } catch (error) {
      console.error('Error updating categories:', error);
    }
  }
  
  // Return a resolved promise if no API call was made
  return Promise.resolve();
},

updateTeachers(teachers) {
  // Convert undefined or null to empty array
  const newTeachers = teachers || [];
  
  // Store the new selection
  this.selectedTeachers = newTeachers;
  
  // Only make API calls if we have a certificate ID
  if (this.certificate.id) {
    try {
      // Make the API call with whatever teachers are selected (can be empty)
      return axios.post(`/certificates/${this.certificate.id}/teachers`, {
        teachers: newTeachers.map(t => t.id)
      });
    } catch (error) {
      console.error('Error updating teachers:', error);
    }
  }
  
  // Return a resolved promise if no API call was made
  return Promise.resolve();
},
    
    async loadCategories() {
      try {
        // Cancel any existing request
        if (this.abortControllers.categories) {
          this.abortControllers.categories.abort();
        }
        
        // Create new abort controller
        this.abortControllers.categories = new AbortController();
        
        const response = await axios.get('/api/categories', {
          signal: this.abortControllers.categories.signal
        });
        
        this.categories = response.data;
      } catch (error) {
        if (error.name !== 'CanceledError' && error.code !== 'ECONNABORTED') {
          console.error('Error loading categories:', error);
        }
      }
    },
    
    async loadTeachers() {
      try {
        // Cancel any existing request
        if (this.abortControllers.teachers) {
          this.abortControllers.teachers.abort();
        }
        
        // Create new abort controller
        this.abortControllers.teachers = new AbortController();
        
        const response = await axios.get('/api/teachers', {
          signal: this.abortControllers.teachers.signal
        });
        
        this.teachers = response.data;
      } catch (error) {
        if (error.name !== 'CanceledError' && error.code !== 'ECONNABORTED') {
          console.error('Error loading teachers:', error);
        }
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
  background: #ff00cc;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #ffc000;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #FF0000;
}

.disappear-sidebar > div {
  @apply opacity-0 pointer-events-none;
}
</style>