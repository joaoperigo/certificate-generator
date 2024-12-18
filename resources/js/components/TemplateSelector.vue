<template>
    <div class="template-selector p-4 border-b border-stone-600 bg-stone-800">
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
        class="border border-b-4 border-stone-600  hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 ps-2 pe-4 mb-6 rounded-xl mt-6 flex justify-center gap-2 items-center w-full"
      >
        <PhCaretDoubleLeft :size="20" class="text-stone-900" />
        Insert Template
        <PhCheckFat class="w-5 h-5" />
      </button>
    </div>
  </template>
  
  <script>
  import { PhCheckFat, PhCaretDoubleLeft } from '@phosphor-icons/vue';
  
  export default {
    components: {
      PhCheckFat, 
      PhCaretDoubleLeft
    },
    data() {
      return {
        selectedTemplate: '',
        templates: [
          {
            id: 1,
            name: 'Type 1 - Two Pages',
            objects: {
              page1: [
                {
                  objectName: 'Nome',
                  text: 'Nome do Aluno',
                  fontSize: 30,
                  fontColor: '#2c2c2c',
                  xPos: 20,
                  yPos: 89,
                  fontFamily: 'Mangueira-Semibold',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: false
                },
                {
                  objectName: 'Descrição',
                  text: 'Descrição do Certificado',
                  fontSize: 24,
                  fontColor: '#2c2c2c',
                  xPos: 20,
                  yPos: 99,
                  fontFamily: 'Mangueira-Medium',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: true,
                  boxWidth: 200
                },
                {
                  objectName: 'Data e Local',
                  text: 'Local e Data',
                  fontSize: 20,
                  fontColor: '#2c2c2c',
                  xPos: 20,
                  yPos: 119,
                  fontFamily: 'Mangueira-Medium',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: false
                },
                {
                  objectName: 'Código',
                  text: 'CÓDIGO DE VALIDAÇÃO',
                  fontSize: 16,
                  fontColor: '#2c2c2c',
                  xPos: 100,
                  yPos: 200,
                  fontFamily: 'Myriad-Medium',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: false
                }
              ],
              page2: [
                {
                  objectName: 'Dados do Aluno',
                  text: 'Nome:\nCPF:',
                  fontSize: 20,
                  fontColor: '#2c2c2c',
                  xPos: 20,
                  yPos: 89,
                  fontFamily: 'Myriad-Medium',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: true,
                  boxWidth: 200
                },
                {
                  objectName: 'Código',
                  text: 'CÓDIGO DE VALIDAÇÃO',
                  fontSize: 16,
                  fontColor: '#2c2c2c',
                  xPos: 100,
                  yPos: 200,
                  fontFamily: 'Myriad-Medium',
                  textAlign: 'left',
                  letterSpacing: 0,
                  haveTextBox: false
                }
              ]
            }
          },
          {
            id: 2,
            name: 'Type 2 - Simple',
            objects: {
              page1: [
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
          }
        ]
      }
    },
    methods: {
      applySelectedTemplate() {
        const template = this.templates.find(t => t.id === parseInt(this.selectedTemplate))
        if (!template) return
  
        if (template.id === 1) {
          // Primeiro adiciona os objetos da primeira página
          if (template.objects.page1) {
            const page1Objects = template.objects.page1.map(obj => ({
              ...obj,
              xPos: obj.xPos * 3.779528,
              yPos: obj.yPos * 3.779528,
              boxWidth: obj.boxWidth ? obj.boxWidth * 3.779528 : undefined
            }))
  
            // Emitir objetos da primeira página
            page1Objects.forEach(obj => {
              this.$emit('applyTemplate', obj, 0)
            })
          }
  
          // Depois cria a segunda página
          this.$emit('addPage')
  
          // Após criar a página, adicionar os objetos da segunda página
          setTimeout(() => {
            if (template.objects.page2) {
              const page2Objects = template.objects.page2.map(obj => ({
                ...obj,
                xPos: obj.xPos * 3.779528,
                yPos: obj.yPos * 3.779528,
                boxWidth: obj.boxWidth ? obj.boxWidth * 3.779528 : undefined
              }))
  
              // Emitir objetos da segunda página
              page2Objects.forEach(obj => {
                this.$emit('applyTemplate', obj, 1)
              })
            }
          }, 100)
        } else {
          // Para o tipo 2
          if (template.objects.page1) {
            const objectsToAdd = template.objects.page1.map(obj => ({
              ...obj,
              xPos: obj.xPos * 3.779528,
              yPos: obj.yPos * 3.779528,
              boxWidth: obj.boxWidth ? obj.boxWidth * 3.779528 : undefined
            }))
  
            objectsToAdd.forEach(obj => {
              this.$emit('applyTemplate', obj)
            })
          }
        }
  
        this.selectedTemplate = ''
      }
    }
  }
  </script>