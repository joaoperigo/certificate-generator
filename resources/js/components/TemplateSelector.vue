<template>
    <div class="template-selector p-4 border-b border-stone-600 sticky top-20 bg-stone-800">
        <h2 class="text-base text-stone-200 font-bold mb-2">Select Template</h2>
      
      <select 
        v-model="selectedTemplate"
        class="w-full bg-stone-700 text-stone-200 rounded-xl border border-b-4 border-stone-300 p-2 mb-2"
      >
        <option value="">Select a template...</option>
        <option v-for="template in templates" :key="template.id" :value="template.id">
          {{ template.name }}
        </option>
      </select>
  
      <button 
        v-if="selectedTemplate"
        @click="applySelectedTemplate"
        class="w-full mt-2 bg-stone-700 hover:bg-stone-600 text-white font-bold py-2 px-4 rounded-xl border border-b-4 border-stone-500 flex items-center justify-center gap-2"
      >
        <PhArticle class="w-5 h-5" />
        Insert Template
      </button>
    </div>
  </template>
  
  <script>
  import { PhArticle } from '@phosphor-icons/vue';
  
  export default {
    components: {
      PhArticle
    },
    data() {
      return {
        selectedTemplate: '',
        templates: [
          {
            id: 2,
            name: 'Type 2 - Simple',
            objects: [
              {
                objectName: 'Nome',
                text: 'Nome do Aluno',
                fontSize: 15,
                fontColor: '#546789',
                xPos: 20,
                yPos: 100,
                fontFamily: 'Mangueira-Semibold',
                textAlign: 'left',
                letterSpacing: 0,
                haveTextBox: false
              },
              {
                objectName: 'Descrição',
                text: 'Descrição do Certificado',
                fontSize: 30,
                fontColor: '#2c2c2c',
                xPos: 20,
                yPos: 130,
                fontFamily: 'Mangueira-Semibold',
                textAlign: 'left',
                letterSpacing: 0,
                haveTextBox: true,
                boxWidth: 140
              },
              {
                objectName: 'Data e Local',
                text: 'Local e Data',
                fontSize: 30,
                fontColor: '#2c2c2c',
                xPos: 20,
                yPos: 200,
                fontFamily: 'Mangueira-Medium',
                textAlign: 'left',
                letterSpacing: 0,
                haveTextBox: false
              }
            ]
          }
        ]
      }
    },
    methods: {
      applySelectedTemplate() {
        const template = this.templates.find(t => t.id === parseInt(this.selectedTemplate))
        if (!template) return
  
        // Converter cada objeto para o formato esperado e aplicar as conversões necessárias
        const objectsToAdd = template.objects.map(obj => ({
          ...obj,
          xPos: obj.xPos * 3.779528,
          yPos: obj.yPos * 3.779528,
          boxWidth: obj.boxWidth ? obj.boxWidth * 3.779528 : undefined
        }))
  
        // Emitir todos os objetos para serem adicionados
        objectsToAdd.forEach(obj => {
          this.$emit('applyTemplate', obj)
        })
  
        // Resetar seleção
        this.selectedTemplate = ''
      }
    }
  }
  </script>