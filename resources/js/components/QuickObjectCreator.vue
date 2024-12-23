<!-- QuickObjectCreator.vue -->
<template>
    <div class="quick-object-creator p-4 border-b border-stone-600 bg-stone-800">
      <h2 class="text-base text-stone-200 font-bold mb-2">Quick Objects</h2>
      <button 
        @click="createNameObject"
        class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 ps-2 pe-4 rounded-xl mt-2 flex justify-between items-center w-full"
      >
        <div class="flex items-center gap-2">
          <PhTextT :size="20" class="text-stone-900" />
          <span>Add Standard Text</span>
        </div>
        <span class="text-xs text-stone-500">30px Mangueira</span>
      </button>
  
      <button 
        @click="createDescriptionObject"
        class="border border-b-4 border-stone-600 hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 ps-2 pe-4 rounded-xl mt-2 flex justify-between items-center w-full"
      >
        <div class="flex items-center gap-2">
          <PhTextH :size="20" class="text-stone-900" />
          <span>Add Title</span>
        </div>
        <span class="text-xs text-stone-500">48px Centered</span>
      </button>
    </div>
  </template>
  
  <script>
  import { PhTextT, PhTextH } from '@phosphor-icons/vue';
  
  export default {
    components: {
      PhTextT,
      PhTextH
    },
    methods: {
      createNameObject() {
        const standardObject = {
          objectName: 'Student Name',
          text: 'Seu Nome Aqui',
          fontSize: 30,
          fontColor: '#2c2c2c',
          xPos: 19 * 3.779528,
          yPos: 89 * 3.779528,
          boxWidth: 0,
          haveTextBox: true,
          letterSpacing: 0,
          fontFamily: 'Mangueira-Medium',  // Atualizado
          textAlign: 'left'
        }
        this.$emit('create-object', standardObject)
      },

      createDescriptionObject() {
        const titleObject = {
          objectName: 'Certificate Description',
          text: 'Main Title',
          fontSize: 48,
          fontColor: '#2c2c2c',
          xPos: 151.5 * 3.779528,
          yPos: 50 * 3.779528,
          boxWidth: 250 * 3.779528,
          haveTextBox: true,
          letterSpacing: 1,
          fontFamily: 'Mangueira-Regular',  // Atualizado
          textAlign: 'center'
        }
        this.$emit('create-object', titleObject)
      }
    }
  }
  </script>