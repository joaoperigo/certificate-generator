<template>
  <div class="certificate-viewer h-screen flex relative overflow-hidden w-full">
    <sidebar-toggle 
      position="right" 
      :is-collapsed="isRightSidebarCollapsed" 
      @toggle="toggleRightSidebar"
      class="bg-slate-500"
    />
    <sidebar-toggle 
      position="left" 
      :is-collapsed="isLeftSidebarCollapsed" 
      @toggle="toggleLeftSidebar"
      class="bg-slate-500"
    />
        <!-- Left Sidebar -->
    <div 
      :class="['student-list-sidebar absolute left-2 top-0 rounded-lg h-full w-[300px] transition-transform duration-300 overflow-y-auto scroll-smooth custom-scrollbar pb-20', 
               {'transform -translate-x-[calc(100%-3rem)] overflow-y-hidden bg-transparent disappear-sidebar': isLeftSidebarCollapsed}]"
    >
    <certificate-student-form 
      ref="studentForm"
      :certificate="certificate"
      :current-student="currentStudent"
      @update:currentStudent="applyStudentData"
    ></certificate-student-form>

    </div>
    <!-- <div class="flex flex-col md:flex-row"> -->
      <div 
      :class="['flex-grow p-4 overflow-auto transition-all duration-300 px-4 custom-scrollbar', mainContentClass]"
    >


        <div class="flex-grow overflow-auto transition-all duration-300 custom-scrollbar">


          <div class="mb-4 flex items-center justify-center px-10 space-x-6">
          <h1 class="py-4 font-bold text-2xl text-center">{{ certificate.title }}</h1>
          <!-- <div class="space-x-6">
            <button @click="prevPage" :disabled="currentPage === 0" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300 disabled:text-black">Previous Page</button>
          <span >Page {{ currentPage + 1 }} of {{ pages.length }}</span>
          <button @click="nextPage" :disabled="currentPage === pages.length - 1" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300 disabled:text-black">Next Page</button>
          </div>  -->
        </div>

       
        <canvas-editor
  :current-page="currentPage"
  :pages="processedPages"
  :width="currentDimensions.width"
  :height="currentDimensions.height"
  class="h-full w-full"
  ref="canvasEditor"
></canvas-editor>
        </div>
   
        
      
    </div>

    <div 
      :class="['absolute right-0 top-0 h-full w-[300px] pt-24 transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth', 
               {'transform translate-x-[calc(100%-3rem)] overflow-y-hidden bg-transparent  disappear-sidebar': isRightSidebarCollapsed}]"
    >
      <!-- <div class="w-full md:w-1/3 absolute right-0 top-0 h-full transition-transform duration-300 overflow-y-auto custom-scrollbar scroll-smooth"> -->
        <!-- Certificate Student Form -->

        <div class="space-x-6 pt-8 px-8 flex justify-between items-center">
            <button @click="prevPage" :disabled="currentPage === 0" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300 disabled:text-black"><</button>
          <span >Page {{ currentPage + 1 }} of {{ pages.length }}</span>
          <button @click="nextPage" :disabled="currentPage === pages.length - 1" class="bg-purple-500 text-white px-4 py-2 rounded disabled:bg-gray-300 disabled:text-black">></button>
          </div>

        <!-- Text Objects Editor -->
    <div class="p-8">
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
          :current-student="currentStudent"
          buttonClasses="border border-b-4 border-stone-300 hover:bg-purple-400 bg-purple-500 text-white font-bold py-6 px-4 rounded-xl w-full mb-2" 
        ></certificate-download>
      </div>

      </div>

  </div>
</template>

<script>
import CanvasEditor from './CanvasEditor.vue'
import CertificateDownload from './CertificateDownload.vue'
import CertificateStudentForm from './CertificateStudentForm.vue'
import SidebarToggle from './SidebarToggle.vue'
import axios from 'axios'

export default {
  components: {
    CanvasEditor,
    CertificateDownload,
    CertificateStudentForm,
    SidebarToggle
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
    loadedImages: new Map(),
    currentStudent: null,
    isLeftSidebarCollapsed: false,
    isRightSidebarCollapsed: false,
    orientation: this.initialCertificate.orientation || 'landscape',
    landscapeDimensions: {
      width: 303.02, // mm
      height: 215.98 // mm
    },
    portraitDimensions: {
      width: 215.98, // mm
      height: 303.02 // mm
    }
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
    return this.pages.map(page => ({
      ...page,
      backgroundImage: this.loadedImages.get(page.backgroundImage) || page.backgroundImage,
      objects: page.objects.map(obj => ({
        ...obj,
        text: this.replaceStudentData(obj.text)
      }))
    }))
  },
  processedCertificateData() {
    return {
      ...this.certificate,
      orientation: this.orientation,
      dimensions: this.currentDimensions,
      pages: this.processedPages
    };
  },
  mainContentClass() {
    let marginLeft = this.isLeftSidebarCollapsed ? 'ml-12' : 'ml-[300px]'
    let marginRight = this.isRightSidebarCollapsed ? 'mr-12' : 'mr-[300px]'
    return `${marginLeft} ${marginRight}`
  },
  currentDimensions() {
    // Use certificate dimensions if available, otherwise use defaults based on orientation
    if (this.certificate.dimensions) {
      return this.certificate.dimensions;
    }
    
    return this.orientation === 'landscape' 
      ? this.landscapeDimensions 
      : this.portraitDimensions;
  }
},
  methods: {
    replaceStudentData(text) {
  if (!this.currentStudent) return text;
  
  const unitName = this.currentStudent.unit_id 
    ? (this.currentStudent.unit?.name || this.currentStudent.unit) 
    : (this.currentStudent.unit || '');
  
  return text
    .replace(/\[name\]/g, this.currentStudent.name || '')
    .replace(/\[cpf\]/g, this.currentStudent.cpf || '')
    .replace(/\[document\]/g, this.currentStudent.document || '')
    .replace(/\[code\]/g, this.currentStudent.code || '')
    .replace(/\[unit\]/g, unitName)
    .replace(/\[course\]/g, this.currentStudent.course || '')
    .replace(/\[quantity_hours\]/g, this.currentStudent.quantity_hours || '')
    .replace(/\[start_date\]/g, this.formatDate(this.currentStudent.start_date) || '')
    .replace(/\[end_date\]/g, this.formatDate(this.currentStudent.end_date) || '');
},

    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      const day = date.getDate();
      const year = date.getFullYear();
      
      const months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
      ];
      const month = months[date.getMonth()];
      
      return `${day} de ${month} de ${year}`;
    },

    applyStudentData(student) {
      this.currentStudent = student;
    },

    async preloadImages() {
      const pagesToLoad = [this.currentPage];
      if (this.currentPage < this.pages.length - 1) {
        pagesToLoad.push(this.currentPage + 1);
      }

      for (const pageIndex of pagesToLoad) {
        const imageUrl = this.pages[pageIndex]?.backgroundImage;
        if (imageUrl && !this.loadedImages.has(imageUrl)) {
          try {
            const response = await fetch(imageUrl);
            const blob = await response.blob();
            const imageDataUrl = await this.blobToDataURL(blob);
            this.loadedImages.set(imageUrl, imageDataUrl);
          } catch (error) {
            console.error('Error loading image:', error);
          }
        }
      }
    },

    blobToDataURL(blob) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
      });
    },

    prevPage() {
      if (this.currentPage > 0) {
        this.currentPage--;
      }
    },

    nextPage() {
      if (this.currentPage < this.pages.length - 1) {
        this.currentPage++;
      }
    },

    updateObject(index, updatedObject) {
      if (this.pages[this.currentPage] && this.pages[this.currentPage].objects) {
        const newObjects = [...this.pages[this.currentPage].objects];
        newObjects[index] = { ...newObjects[index], ...updatedObject };
        this.pages[this.currentPage].objects = newObjects;
        this.saveCertificate();
      }
    },

    async saveCertificate() {
      try {
        await axios.put(`/certificates/${this.certificate.id}/update-texts`, {
          data: JSON.stringify(this.certificate)
        });
      } catch (error) {
        console.error('Error updating certificate texts:', error);
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


/* ToggleBar */
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