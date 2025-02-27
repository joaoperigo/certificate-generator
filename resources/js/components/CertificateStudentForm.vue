<template>
  <div class="bg-stone-100 pb-20">    
    <!-- Notification Message -->
    <div v-if="notification.show" 
         :class="['fixed top-4 right-1/2 translate-x-1/2 p-4 rounded-lg shadow-lg transition-all duration-500 transform',
                  notification.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                  'text-white']">
      {{ notification.message }}
    </div>

    <div class="fixed top-0 start-0 inline-flex justify-start gap-5 w-[300px] pt-20 px-6 z-10 bg-stone-100 ">
      <a href="#student-form" class="text-stone-600 grid grid-cols-1 w-30">
        <PhStackPlus :size="20" class="mx-auto"/>
        <div class="text-center text-sm text-stone-500">Form</div>
      </a>
      <a href="#students-list" class="text-stone-600 grid grid-cols-1 w-30">
        <PhStack :size="20" class="mx-auto"/>
        <div class="text-center text-sm text-stone-500">Students</div>
      </a>
    </div>

    <div id="student-form"></div>
    <!-- Form -->
    <form @submit.prevent="submitForm" class="p-6 pt-32">
      <h1 class="text-center font-bold pb-2 pt-2 border-t">
        {{ isEditing ? '' : 'Create' }}
        <!-- Selected Student Message -->
        <span v-if="isEditing" 
            class="text-purple-700">
          {{ form.name }}
        </span>
      </h1>
      
      <div class="space-y-2">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name
          <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </label>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">CPF
          <input v-model="form.cpf" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </label>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Extra Document
          <input v-model="form.document" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </label>
        </div>
        <div class="mb-4">
  <label class="block text-sm font-medium text-gray-700">Unit</label>
  <unit-selector 
    :initial-unit-id="form.unit_id"
    @update:unit="updateUnit"
  ></unit-selector>
</div>
        <div>
    <label class="block text-sm font-medium text-gray-700">Code *
    <div class="flex gap-2">
      <input 
        v-model="form.code" 
        type="text" 
        required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
      >
      <button 
        type="button"
        @click="generateCode"
        class="mt-1 bg-purple-500 text-white px-3 py-2 rounded-md hover:bg-purple-600 flex items-center"
      >
        <PhShuffle :size="20" class="mr-1" />
        Generate
      </button>
    </div>
  </label>
    <p v-if="codeError" class="mt-1 text-sm text-red-600">{{ codeError }}</p>
  </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Unit
          <!-- <input v-model="form.unit" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"> -->
          <input type="hidden" v-model="form.unit">
          </label>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Course
          <input v-model="form.course" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </label>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Course Hours
          <input v-model.number="form.quantity_hours" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </label>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Start Date
          <input v-model="form.start_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </label>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">End Date
            <input v-model="form.end_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </label>
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-6">
        <button 
          v-if="isEditing"
          type="button" 
          @click="cancelEdit" 
          class="bg-gray-500 text-white px-4 py-2 rounded"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          class="bg-purple-500 text-white px-4 py-2 rounded"
        >
          {{ isEditing ? 'Update' : 'Save' }}
        </button>
      </div>
    </form>

    <!-- Students List -->
    <div id="students-list"></div>
    <div v-if="certificateStudents.length > 0" class="mb-6 p-6 pt-32">
      <h2 class="font-semibold mb-3 text-center border-t pt-4">Registered Students</h2>
      
      <!-- Search Section -->
      <div class="space-y-4 mt-4 mb-6">
        <div class="flex gap-4">
          <div class="flex-1">
            <input
              v-model="search.term"
              type="text"
              placeholder="Search by name or code..."
              class="w-full px-3 py-2 border rounded-md"
            />
          </div>
        </div>
        <div class="">
          <h3 class="text-center font-bold">From</h3>
          <div class="flex gap-2 text-sm">
            <label class="block text-sm text-gray-600 mb-1">Start Date</label>
            <input
              v-model="search.startDate"
              type="date"
              class="w-full px-3 py-2 border rounded-md"
            />
          </div>
          <h3 class="text-center font-bold">To</h3>
          <div class="flex gap-2">
            <label class="block text-sm text-gray-600 mb-1">End Date</label>
            <input
              v-model="search.endDate"
              type="date"
              class="w-full px-3 py-2 border rounded-md"
            />
          </div>
        </div>
      </div>

      <!-- Results Count -->
      <div class="text-sm text-gray-600 mb-4">
        Found {{ filteredStudents.length }} student(s)
      </div>

      <!-- Students List -->
      <div v-for="student in filteredStudents" :key="student.id" :id="`student-${student.id}`" class="pb-2 pt-4 border-t">
        <div class="flex justify-between w-full gap-4">
              <h3 class="font-medium">{{ student.name }}</h3>
              <!-- Botão de Selecionar -->
              <button 
                title="Select"
                @click="selectStudent(student)" 
                :class="[
                  'font-medium py-2 px-4 rounded-lg flex items-center gap-2 border transition-colors',
                  currentStudent?.id === student.id 
                    ? 'bg-stone-500 text-white border-purple-700' 
                    : 'text-purple-700 border-purple-200 hover:bg-purple-100'
                ]"
              >
                <PhUserCircle :size="20" />
                >
              </button>
            </div>
        <div class="flex justify-between items-center">
          <div>
            
            <p class="text-sm text-gray-600">{{ student.code || 'Not provided' }}</p>
            <p class="flex gap-4 text-sm text-stone-500">
              {{ formatDateBR(student.start_date) || 'Not provided' }} 
              <span v-if="student.start_date!=student.end_date">
                {{ formatDateBR(student.end_date) || 'Not provided' }}
              </span>
            </p>
          </div>          
        </div>
        <div class="flex gap-2">
          <button  @click="editStudent(student)" class="hover:opacity-50 text-slate-800 font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
            <PhPencilLine :size="20" />
            Edit
          </button>
          <button @click="removeStudent(student)" class="hover:opacity-50 text-slate-800 font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
            <PhEraser :size="20" />
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { PhShuffle, PhPencilLine, PhEraser, PhStack, PhStackPlus, PhUserCircle } from '@phosphor-icons/vue';
import UnitSelector from './UnitSelector.vue';

export default {
  components: {
    PhPencilLine,
    PhEraser,
    PhShuffle,
    PhStack,
    PhStackPlus,
    PhUserCircle,
    UnitSelector
  },
  props: {
    certificate: {
      type: Object,
      required: true
    },
    currentStudent: {  // Adicione esta prop
      type: Object,
      default: null
    }
  },
  
  data() {
    return {
      certificateStudents: [],
      form: this.getEmptyForm(),
      isEditing: false,
      editingId: null,
      notification: {
        show: false,
        message: '',
        type: 'success',
      },
      selectedStudentMessage: '',
      codeError: '',
      search: {
        term: '',
        startDate: '',
        endDate: ''
      }
    }
  },
  
  computed: {
  filteredStudents() {
    return this.certificateStudents
      .filter(student => {
        const searchLower = this.search.term.toLowerCase();
        const nameMatch = student.name?.toLowerCase().includes(searchLower);
        const codeMatch = student.code?.toLowerCase().includes(searchLower);
        
        const startDateMatch = !this.search.startDate || 
          new Date(student.start_date) >= new Date(this.search.startDate);
        
        const endDateMatch = !this.search.endDate || 
          new Date(student.end_date) <= new Date(this.search.endDate);

        return (nameMatch || codeMatch) && startDateMatch && endDateMatch;
      })
      .sort((a, b) => {
        // Ordenar por data de início, mais recente primeiro
        return new Date(b.start_date) - new Date(a.start_date);
      });
  }
},

  mounted() {
    this.loadCertificateStudents()
  },
  
  methods: {
    showNotification(message, type = 'success') {
      this.notification = {
        show: true,
        message,
        type,
      };
      
      // Hide notification after 3 seconds
      setTimeout(() => {
        this.notification.show = false;
      }, 3000);
    },
    updateUnit(unit) {
      if (unit) {
        this.form.unit_id = unit.id;
        this.form.unit = unit.name; // For backward compatibility
      } else {
        this.form.unit_id = null;
        this.form.unit = '';
      }
    },
    getEmptyForm() {
      return {
        name: '',
        cpf: '',
        document: '',
        code: '',
        unit: '',
        unit_id: null, // Add this line
        course: this.certificate.title || '', 
        quantity_hours: this.certificate.quantity_hours || null,
        start_date: '',
        end_date: ''
      }
    },
    
    async loadCertificateStudents() {
      try {
        const response = await axios.get(`/certificates/${this.certificate.id}/certificate-students`)
        this.certificateStudents = response.data
      } catch (error) {
        this.showNotification('Error loading students: ' + this.getErrorMessage(error), 'error');
      }
    },
    
    async generateCode() {
      try {
        const response = await axios.get(`/certificates/${this.certificate.id}/generate-code`);
        this.form.code = response.data.code;
        this.codeError = '';
      } catch (error) {
        this.showNotification('Error generating code', 'error');
      }
    },

    scrollToForm() {
    document.getElementById('student-form')?.scrollIntoView({ behavior: 'smooth' });
  },

  scrollToStudent(studentId) {
    setTimeout(() => {
      const element = document.getElementById(`student-${studentId}`);
      const sidebarContainer = document.querySelector('.student-list-sidebar'); // Adicione uma classe ao container da sidebar
      
      if (element && sidebarContainer) {
        const elementTop = element.offsetTop;
        sidebarContainer.scrollTo({
          top: elementTop - 120,
          behavior: 'smooth'
        });
      }
    }, 100);
  },
  
  editStudent(student) {
    this.form = { ...student };
    this.form.start_date = this.formatDateForInput(student.start_date);
    this.form.end_date = this.formatDateForInput(student.end_date);
    this.isEditing = true;
    this.editingId = student.id;
    this.selectedStudentMessage = `Editing: ${student.name}`;
    this.scrollToForm();
  },

  selectStudent(student) {
    this.$emit('update:currentStudent', student);
    this.selectedStudentMessage = `Selected: ${student.name}`;
  },

  // Auxiliar para formatar data para input
  formatDateForInput(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().split('T')[0];
  },

  async submitForm() {
    try {
      this.codeError = '';
      
      if (!this.form.code) {
        this.codeError = 'Code is required';
        return;
      }

      let response;
      if (this.isEditing) {
        response = await axios.put(
          `/certificates/${this.certificate.id}/certificate-students/${this.editingId}`,
          this.form
        );
        await this.loadCertificateStudents();
      
        // Se o usuário que está sendo editado é o mesmo que está selecionado,
        // precisamos reemitir o evento de seleção com os dados atualizados
        if (this.currentStudent?.id === this.editingId) {
          this.$emit('update:currentStudent', response.data);
        }

        if (response.data.id) {
          this.scrollToStudent(response.data.id);
        }
      } else {
        response = await axios.post(
          `/certificates/${this.certificate.id}/certificate-students`,
          this.form
        );
        await this.loadCertificateStudents();
      
        // Scroll para o estudante após salvar
        if (response.data.id) {
          setTimeout(() => {
            this.scrollToStudent(response.data.id);
          }, 100);
        }
      }
            
      this.resetForm();
    } catch (error) {
      if (error.response?.data?.errors?.code) {
        this.codeError = error.response.data.errors.code[0];
      } else {
        this.showNotification('Error: ' + this.getErrorMessage(error), 'error');
      }
    }
  },
    
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      const year = d.getFullYear();
      const month = ('0' + (d.getMonth() + 1)).slice(-2);
      const day = ('0' + d.getDate()).slice(-2);
      return `${year}-${month}-${day}`;
    },

    formatDateBR(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}/${month}/${year}`;
    },

    async removeStudent(student) {
      if (confirm('Are you sure you want to remove this student?')) {
        try {
          await axios.delete(`/certificates/${this.certificate.id}/certificate-students/${student.id}`);
          this.showNotification(`Student ${student.name} removed successfully`);
          await this.loadCertificateStudents();
        } catch (error) {
          this.showNotification('Error removing student: ' + this.getErrorMessage(error), 'error');
        }
      }
    },
    
    cancelEdit() {
      this.resetForm();
      this.selectedStudentMessage = '';
    },
    
    resetForm() {
      this.form = this.getEmptyForm();
      this.isEditing = false;
      this.editingId = null;
      this.selectedStudentMessage = '';
    },

    getErrorMessage(error) {
      return error.response?.data?.error || error.message || 'An unexpected error occurred';
    }
  }
}
</script>

<style scoped>
.notification-enter-active, .notification-leave-active {
  transition: all 0.5s ease;
}
.notification-enter-from, .notification-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}
</style>