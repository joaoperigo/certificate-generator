<template>
  <div class="pb-20 bg-stone-100 mt-24 px-10">
    <h1 class="text-2xl font-bold mb-4">{{ certificate.title }}</h1>
    
    <!-- Students List -->
    <div v-if="certificateStudents.length > 0" class="mb-6">
      <h2 class="text-xl font-semibold mb-3">Registered Students</h2>
      <div v-for="student in certificateStudents" :key="student.id" class="bg-white p-4 rounded-lg shadow mb-3">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="font-medium">{{ student.name }}</h3>
            <p class="text-sm text-gray-600">CPF: {{ student.cpf || 'Not provided' }}</p>
            <p class="text-sm text-gray-600">Code: {{ student.code || 'Not provided' }}</p>
          </div>
          <div class="flex gap-2">
            <button @click="editStudent(student)" class="bg-blue-500 text-white px-3 py-1 rounded">
              Edit
            </button>
            <button @click="removeStudent(student)" class="bg-red-500 text-white px-3 py-1 rounded">
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form @submit.prevent="submitForm" class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Student' : 'New Student' }}</h2>
      
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class=" col-start-1 col-end-3">
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
  </div>
</template>

<script>
import axios from 'axios'

export default {
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
      }
    },
    
    editStudent(student) {
      this.form = { ...student }
      this.isEditing = true
      this.editingId = student.id
      this.$emit('update:currentStudent', student)
    },
    
    async removeStudent(student) {
      if (confirm('Are you sure you want to remove this student?')) {
        try {
          await axios.delete(`/certificates/${this.certificate.id}/certificate-students/${student.id}`)
          await this.loadCertificateStudents()
        } catch (error) {
          console.error('Error removing certificate student:', error)
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