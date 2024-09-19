<!-- AddParagraph.vue -->
<template>
    <div class="add-paragraph w-full">
      <h2 class="text-base text-stone-200 mt-12 font-bold mb-2">Create object</h2>
      <div class="mb-4">
        <!-- <label class="block text-stone-300 text-sm font-bold mb-2 text-end" for="object-name">Name of the object:</label> -->
        <input v-model="paragraph.objectName" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-transparent" type="text" id="object-name" placeholder="Name of the object">
      </div>
      <div class="mb-4 position-relative">
        <!-- <label class="position-absolute top-4 start-4 block text-stone-300 text-sm font-bold mb-2 text-end" for="text">Default text:</label> -->
        <textarea v-model="paragraph.text" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-transparent" id="text" placeholder="Describe yourself here..."></textarea>
      </div>

      <div class="mb-4">
        <!-- <label class="w-full" for="font-family">Font Family</label> -->
        <select v-model="paragraph.fontFamily" class="w-full bg-stone-700 rounded-xl border border-b-4 border-stone-300" id="font-family">
          <option value="Mangueira-Semibold">Mangueira Regular</option>
          <option value="Mangueira-Medium">Mangueira Bold</option>
          <option value="Myriad-Medium">Myriad Medium</option>
        </select>
      </div>
      
      <div class="columns-2 mb-4">
        <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full position-absolute top-0 end-0" for="font-size">Font Size (px):</label> -->
          <div class="mx-2">
            <span class="border-b border-t border-stone-200 text-stone-200 px-1 text-sm">A</span>
          </div>
          <input v-model.number="paragraph.fontSize" class="w-full bg-stone-700 rounded-xl" type="number" id="font-size" placeholder="font size">
        </div>
        <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full" for="font-color">Font Color:</label> -->
          <div class="mx-2">color</div>
          <input v-model="paragraph.fontColor" class="w-full bg-stone-700 rounded-xl h-10" type="color" id="font-color">
        </div>
      </div>
  
      <div class="columns-2 mb-4">
        <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full" for="x-pos">Position X (mm):</label> -->
           <PhArrowsVertical :size="20" class="mx-2" />
          <input v-model.number="paragraph.xPos" class="w-full bg-stone-700 rounded-xl" type="number" id="x-pos" step="any">
        </div>
        <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full" for="y-pos">Position Y (mm):</label> -->
          <PhArrowsHorizontal :size="20" class="mx-2" />
          <input v-model.number="paragraph.yPos" class="w-full bg-stone-700 rounded-xl" type="number" id="y-pos">
        </div>        
      </div>
  
      <div class="columns-2 mb-4">
          <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full" for="letter-spacing">Letter Spacing</label> -->
            <div class="mx-2">
              <span class="border-s border-e text-sm border-stone-200 text-stone-200 px-1">A</span>
            </div>
          <input v-model="paragraph.letterSpacing" class="w-full bg-stone-700 rounded-xl h-10" type="number" id="letter-spacing" step="0.1">
        </div>
        <div>
          <label class="w-full" for="text-align">Text Align</label>
          <select v-model="paragraph.textAlign" class="w-full" id="text-align">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
          </select>
        </div>
      </div> 
  
      <div class="columns-2 mb-4">
        <div>
          <label class="w-full" for="have-text-box">Have text box?</label>
          <div class="text-start w-full">
            <input v-model="paragraph.haveTextBox" type="checkbox" id="have-text-box">
          </div>
        </div>
        <div v-if="paragraph.haveTextBox" class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
          <!-- <label class="w-full" for="box-width">Box Width (mm):</label> -->
          <div class="mx-2">
            <span class="border border-stone-200 text-stone-200 px-1 text-sm">A</span>
          </div>
          <input v-model.number="paragraph.boxWidth" class="w-full bg-stone-700 rounded-xl" type="number" id="box-width">
        </div>



      </div>
  

  
      <button @click="addParagraph" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" type="button">+ Add Paragraph</button>
    </div>
  </template>
  
  <script>
import { PhArrowsHorizontal, PhArrowsVertical, PhTextAlignLeft, PhTextAlignCenter, PhTextAlignRight } from '@phosphor-icons/vue';

  export default {
    components: {
      PhArrowsVertical,
      PhArrowsHorizontal,
      PhTextAlignLeft,
      PhTextAlignCenter,
      PhTextAlignRight
    },
    data() {
      return {
        paragraph: {
          objectName: '',
          text: '',
          fontSize: 20,
          fontColor: '#2c2c2c',
          xPos: 0,
          yPos: 0,
          boxWidth: 0,
          haveTextBox: false,
          letterSpacing: 0,
          fontFamily: 'Mangueira-Semibold',
          textAlign: 'left'
        }
      }
    },
    methods: {
        addParagraph() {
      const newParagraph = { ...this.paragraph }
      // Aplicar a conversão aqui
      newParagraph.xPos *= 3.779528
      newParagraph.yPos *= 3.779528
      if (newParagraph.boxWidth) {
        newParagraph.boxWidth *= 3.779528
      }
      this.$emit('add-paragraph', newParagraph)
      // Reset form after adding
      this.paragraph = {
        objectName: '',
        text: '',
        fontSize: 20,
        fontColor: '#2c2c2c',
        xPos: 0,
        yPos: 0,
        boxWidth: 0,
        haveTextBox: false,
        letterSpacing: 0,
        fontFamily: 'Mangueira-Semibold',
        textAlign: 'left'
      }
    }
    }
  }
  </script>