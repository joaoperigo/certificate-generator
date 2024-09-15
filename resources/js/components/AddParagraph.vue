<!-- AddParagraph.vue -->
<template>
    <div class="add-paragraph w-full">
      <h2 class="text-xl mt-8 font-bold mb-2">Create object</h2>
      <div class="mb-4">
        <label class="block text-gray-700 text-base font-bold mb-2" for="object-name">Name of the object:</label>
        <input v-model="paragraph.objectName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="object-name">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-base font-bold mb-2" for="text">Default text:</label>
        <textarea v-model="paragraph.text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="text"></textarea>
      </div>
      
      <div class="columns-2 mb-4">
        <div>
          <label class="w-full" for="font-size">Font Size (px):</label>
          <input v-model.number="paragraph.fontSize" class="w-full" type="number" id="font-size">
        </div>
        <div>
          <label class="w-full" for="font-color">Font Color:</label>
          <input v-model="paragraph.fontColor" class="w-full" type="color" id="font-color">
        </div>
      </div>
  
      <div class="columns-2 mb-4">
        <div>
          <label class="w-full" for="x-pos">Position X (mm):</label>
          <input v-model.number="paragraph.xPos" class="w-full" type="number" id="x-pos">
        </div>
        <div>
          <label class="w-full" for="y-pos">Position Y (mm):</label>
          <input v-model.number="paragraph.yPos" class="w-full" type="number" id="y-pos">
        </div>        
      </div>
  
      <div class="columns-2 mb-4">
        <div>
          <label class="w-full" for="letter-spacing">Letter Spacing</label>
          <input v-model="paragraph.letterSpacing" class="w-full" type="text" id="letter-spacing">
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
        <div v-if="paragraph.haveTextBox">
          <label class="w-full" for="box-width">Box Width (mm):</label>
          <input v-model.number="paragraph.boxWidth" class="w-full" type="number" id="box-width">
        </div>
      </div>
  
      <div class="mb-4">
        <label class="w-full" for="font-family">Font Family</label>
        <select v-model="paragraph.fontFamily" class="w-full" id="font-family">
          <option value="Mangueira-Semibold">Mangueira Regular</option>
          <option value="Mangueira-Medium">Mangueira Bold</option>
          <option value="Myriad-Medium">Myriad Medium</option>
        </select>
      </div>
  
      <button @click="addParagraph" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" type="button">+ Add Paragraph</button>
    </div>
  </template>
  
  <script>
  export default {
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
        // Convert mm to pixels for position and box width
        newParagraph.xPos *= 3.779528
        newParagraph.yPos *= 3.779528
        newParagraph.boxWidth *= 3.779528
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