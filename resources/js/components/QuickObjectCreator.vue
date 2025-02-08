<!-- QuickObjectCreator.vue -->
<template>
    <div class="quick-object-creator p-4 border-b border-stone-600 bg-stone-800">
      <h2 class="text-base text-stone-200 font-bold mb-2">Quick Objects</h2>
      <h3 class="text-xs text-stone-200 mt-4">Página frontal</h3>
      <div class="grid grid-cols-2 gap-4">
        <button 
          @click="createNameObject"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhTextT :size="20" class="text-stone-900" />
            <span>Add Name</span>
          </div>
        </button>
    
        <button 
          @click="createDescriptionObject"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhTextH :size="20" class="text-stone-900" />
            <span>Add Description</span>
          </div>
        </button>

        <button 
          @click="createDataELocalObject"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhCalendarDot :size="20" class="text-stone-900" />
            <span>Add Data e Local</span>
          </div>
        </button>

        <button 
          @click="createCode"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhHash :size="20" class="text-stone-900" />
            <span>Add Code</span>
          </div>
        </button>
      </div>

      <h3 class="text-xs text-stone-200 mt-4">Página verso</h3>
      <div class="grid grid-cols-2 gap-4">
        <button 
          @click="createStudentData"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhUserList :size="20" class="text-stone-900" />
            <span>Add Student Info</span>
          </div>
        </button>

        <button 
          @click="createCodeBack"
          class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 px-4 rounded-xl mt-2 flex justify-start items-start w-full"
        >
          <div class="flex flex-col justify-start items-start text-start gap-2">
            <PhHash :size="20" class="text-stone-900" />
            <span>Adicionar Código Verso</span>
          </div>
        </button>

      </div>
    </div>
  </template>
  
  <script>
  import { PhTextT, PhTextH, PhCalendarDot, PhHash, PhUserList } from '@phosphor-icons/vue';
  
  export default {
    components: {
      PhTextT,
      PhTextH,
      PhCalendarDot,
      PhHash,
      PhUserList
    },
    methods: {
      createNameObject() {
        const titleObject = {
          objectName: 'Nome',
          text: 'Nome do Aluno',
          fontSize: 27,
          fontColor: '#35548E',
          xPos: 20,
          yPos: 98,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'left',
          letterSpacing: 0,
          boxWidth: 0,
          haveTextBox: false
        }
        this.$emit('create-object', titleObject)
      },

      createDescriptionObject() {
        const descriptionObject = {
          objectName: 'Descrição',
          text: 'concluiu com excelência o curso vip de Harmonização Íntima Feminina, realizado no âmbito do Instituto Integrado de Pesquisa e Educação, nos dias xx e xx de Outubro de 2022, com carga horária total de xx horas. Obteve êxito em todas as atividades propostas, aprendeu desde técnicas básicas a avançadas de como cuidar da aparência e saúde da região íntima, tanto masculina, quanto feminina. E também, destacou-se na prática dos procedimentos.',
          fontSize: 11.23,
          fontColor: '#424242',
          xPos: 20,
          yPos: 108,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'left',
          letterSpacing: 0,
          haveTextBox: true,
          boxWidth: 140,
        }
        this.$emit('create-object', descriptionObject)
      },

      createDataELocalObject() {
        const dataELocalObject = {
          objectName: 'Data e Local',
          text: 'Rio de Janeiro, XX de Xxxxxx de 20XX',
          fontSize: 13.39,
          fontColor: '#2A2A2A',
          xPos: 20,
          yPos: 150.4,
          fontFamily: 'Mangueira-Medium',
          textAlign: 'left',
          letterSpacing: 0,
          boxWidth: 0,
          haveTextBox: false
        }
        this.$emit('create-object', dataELocalObject)
      },

      createCode() {
        const codeObject = {
          objectName: 'Código',
          text: 'ASQ23D',
          fontSize: 8,
          fontColor: '#2c2c2c',
          xPos: 266,
          yPos: 157.8,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'center',
          letterSpacing: .2,
          boxWidth: 0,
          haveTextBox: false
        }
        this.$emit('create-object', codeObject)
      },

      createCode() {
        const codeObject = {
          objectName: 'Código',
          text: 'ASQ23D',
          fontSize: 8,
          fontColor: '#2c2c2c',
          xPos: 266,
          yPos: 157.8,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'center',
          letterSpacing: .2,
          boxWidth: 0,
          haveTextBox: false
        }
        this.$emit('create-object', codeObject)
      },

      createStudentData() {
        const alunoDataObject = {
          objectName: 'Dados do Aluno',
          text: 'Nome:\nCPF:',
          fontSize: 6.42,
          fontColor: '#424242',
          xPos: 199.5, 
          yPos: 87,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'left',
          letterSpacing: 0,
          boxWidth: 0,
          haveTextBox: false,
        }
        this.$emit('create-object', alunoDataObject)
      },

      createCodeBack() {
        const codeBackObject = {
          objectName: 'Código',
          text: 'ASQ23D',
          fontSize: 8.71,
          fontColor: '#424242',
          xPos: 225.8, 
          yPos: 129.7,
          fontFamily: 'Mangueira-Regular',
          textAlign: 'left',
          letterSpacing: .2,
          boxWidth: 0,
          haveTextBox: false
        }
        this.$emit('create-object', codeBackObject)
      }


    }
  }
  </script>