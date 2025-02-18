<template>
  <div class="pb-20 bg-stone-100 mt-24">    
    <!-- Notification Message -->
    <div v-if="notification.show" 
         :class="['fixed top-4 right-1/2 translate-x-1/2 p-4 rounded-lg shadow-lg transition-all duration-500 transform',
                  notification.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                  'text-white']">
      {{ notification.message }}
    </div>

    <!-- Selected Student Message -->
    <div v-if="selectedStudentMessage" 
         class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 mb-4 mx-6">
      {{ selectedStudentMessage }}
    </div>

    <!-- Form -->
    <form @submit.prevent="submitForm" class="p-6 space-y-6">
      <h1 class="font-semibold mb-4">{{ isEditing ? 'Edit Student' : 'New Student' }}</h1>
      
      <div class="space-y-3">
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
          <input v-model="form.unit" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          </label>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Course
          <input v-model="form.curso" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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

      <div class="flex justify-end gap-2">
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
    <div v-if="certificateStudents.length > 0" class="mb-6 p-6 space-y-6">
      <h2 class="font-semibold mb-3">Registered Students</h2>
      <div v-for="student in certificateStudents" :key="student.id" class="p-4 mb-3">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="font-medium">{{ student.name }}</h3>
            <p class="text-sm text-gray-600">{{ student.code || 'Not provided' }}</p>
            <p>
              {{ formatDate(student.start_date) || 'Not provided' }} 
              <span v-if="student.start_date!=student.end_date">
                {{ formatDate(student.end_date) || 'Not provided' }}
              </span>
            </p>
          </div>          
        </div>
        <div class="flex gap-2">
            <button @click="editStudent(student)" class="hover:opacity-50 text-slate-800 font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
              <PhPencilLine :size="20" />
              Select
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
import { PhShuffle, PhPencilLine, PhEraser } from '@phosphor-icons/vue';

export default {
  components: {
    PhPencilLine,
    PhEraser,
    PhShuffle
  },
  props: {
    certificate: {
      type: Object,
      required: true
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

    getEmptyForm() {
      return {
        name: '',
        cpf: '',
        document: '',
        code: '',
        unit: '',
        curso: this.certificate.title || '', // Preenche com o título do certificado
        quantity_hours: this.certificate.quantity_hours || null, // Preenche com as horas do certificado
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

    async submitForm() {
      try {
        this.codeError = '';
        
        // Add validation before submission
        if (!this.form.code) {
          this.codeError = 'Code is required';
          return;
        }

        if (this.isEditing) {
          await axios.put(
            `/certificates/${this.certificate.id}/certificate-students/${this.editingId}`,
            this.form
          );
        } else {
          await axios.post(
            `/certificates/${this.certificate.id}/certificate-students`,
            this.form
          );
        }
        
        await this.loadCertificateStudents();
        this.resetForm();
      } catch (error) {
        if (error.response?.data?.errors?.code) {
          this.codeError = error.response.data.errors.code[0];
        } else {
          this.showNotification('Error: ' + this.getErrorMessage(error), 'error');
        }
      }
    },
    
    editStudent(student) {
      this.form = { ...student };
      this.form.start_date = this.formatDate(student.start_date);
      this.form.end_date = this.formatDate(student.end_date);
      this.isEditing = true;
      this.editingId = student.id;
      this.$emit('update:currentStudent', student);
      
      // Show selected student message
      this.selectedStudentMessage = `Selected student: ${student.name}`;
    },
    
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      const year = d.getFullYear();
      const month = ('0' + (d.getMonth() + 1)).slice(-2);
      const day = ('0' + d.getDate()).slice(-2);
      return `${year}-${month}-${day}`;
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