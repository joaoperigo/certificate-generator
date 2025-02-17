<template>
  <div class="pb-20 bg-stone-100 mt-24">    

<!-- Form -->
<form @submit.prevent="submitForm" class="p-6 space-y-6">
      <h1 class="font-semibold mb-4">{{ isEditing ? 'Edit Student' : 'New Student' }}</h1>
      
      <div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">CPF</label>
          <input v-model="form.cpf" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Document</label>
          <input v-model="form.document" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Code</label>
          <input v-model="form.code" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Unit</label>
          <input v-model="form.unit" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <!-- <div>
          <label class="block text-sm font-medium text-gray-700">Unit</label>
          <select v-model="form.unit">
            <option value="Rio de Janeiro">Rio de Janeiro</option>
            <option value="Brasília">Brasília</option>
          </select>
        </div> -->
        
        <div>
          <label class="block text-sm font-medium text-gray-700">Start Date</label>
          <input v-model="form.start_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">End Date</label>
          <input v-model="form.end_date" type="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
          <div class="flex gap-2">
            <button @click="editStudent(student)" class=" hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
              <PhPencilLine :size="20" />
              Edit
            </button>
            <button @click="removeStudent(student)" class=" hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
              <PhEraser :size="20" />
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedStudentMessage" 
     class="bg-stone-100 border-l-4 border-blue-500 text-stone-700 p-4 mb-4 rounded-r-lg shadow-sm transition-all duration-300 ease-in-out">
  <div class="flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
    </svg>
    <span class="font-medium">{{ selectedStudentMessage }}</span>
  </div>
</div>
  </div>
</template>

<script>
import axios from 'axios'

import { PhPencilLine, PhEraser } from '@phosphor-icons/vue';

export default {
  components: {
    PhPencilLine,
    PhEraser
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
      editingId: null
    }
  },
  
  mounted() {
    this.loadCertificateStudents()
  },
  
  methods: {
    getEmptyForm() {
      return {
        name: '',
        cpf: '',
        document: '',
        code: '',
        unit: '',
        start_date: '',
        end_date: ''
      }
    },
    
    async loadCertificateStudents() {
      try {
        const response = await axios.get(`/certificates/${this.certificate.id}/certificate-students`)
        this.certificateStudents = response.data
      } catch (error) {
        console.error('Error loading certificate students:', error)
      }
    },
    
    async submitForm() {
      try {
        if (this.isEditing) {
          // Atualiza a URL para usar certificateStudent
          await axios.put(
            `/certificates/${this.certificate.id}/certificate-students/${this.editingId}`,
            this.form
          )
        } else {
          await axios.post(
            `/certificates/${this.certificate.id}/certificate-students`,
            this.form
          )
        }
        
        await this.loadCertificateStudents()
        this.resetForm()
      } catch (error) {
        console.error('Error saving certificate student:', error)
        alert('Erro ao salvar aluno: ' + (error.response?.data?.error || error.message))
      }
    },
    
    editStudent(student) {
      this.form = { ...student }

      // Converte as datas para o formato YYYY-MM-DD
      this.form.start_date = this.formatDate(student.start_date);
      this.form.end_date = this.formatDate(student.end_date);

      this.isEditing = true
      this.editingId = student.id
      this.$emit('update:currentStudent', student)
    },
    
    // Função para formatar a data no formato YYYY-MM-DD
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      const year = d.getFullYear();
      const month = ('0' + (d.getMonth() + 1)).slice(-2); // Adiciona zero à esquerda se necessário
      const day = ('0' + d.getDate()).slice(-2); // Adiciona zero à esquerda se necessário
      return `${year}-${month}-${day}`;
    },

    async removeStudent(student) {
      if (confirm('Tem certeza que deseja remover este aluno?')) {
        try {
          // Atualiza a URL para usar certificateStudent
          await axios.delete(`/certificates/${this.certificate.id}/certificate-students/${student.id}`)
          await this.loadCertificateStudents()
        } catch (error) {
          console.error('Error removing certificate student:', error)
          alert('Erro ao remover aluno: ' + (error.response?.data?.error || error.message))
        }
      }
    },
    
    cancelEdit() {
      this.resetForm()
    },
    
    resetForm() {
      this.form = this.getEmptyForm()
      this.isEditing = false
      this.editingId = null
    }
  }
}
</script>