<!-- AddParagraph.vue -->
<template>
  <div class="add-paragraph w-full">
    <h2 class="text-base text-stone-200 mt-12 font-bold mb-2">Create object</h2>
    <div class="mb-4">
      <input v-model="paragraph.objectName" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-transparent" type="text" id="object-name" placeholder="Name of the object">
    </div>
    <div class="mb-4 position-relative">
      <textarea v-model="paragraph.text" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-transparent" id="text" placeholder="Describe yourself here..."></textarea>
    </div>

    <div class="mb-4">
      <select v-model="paragraph.fontFamily" class="w-full bg-stone-700 text-stone-200 rounded-xl border border-b-4 border-stone-300" id="font-family">
        <option value="Mangueira-Semibold">Mangueira Regular</option>
        <option value="Mangueira-Medium">Mangueira Bold</option>
        <option value="Myriad-Medium">Myriad Medium</option>
      </select>
    </div>
    
    <div class="columns-2 mb-4">
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <div class="mx-2">
          <span class="border-b border-t border-stone-200 text-stone-200 px-1 text-sm">A</span>
        </div>
        <input v-model.number="paragraph.fontSize" class="w-full bg-stone-700 rounded-xl" type="number" id="font-size" placeholder="font size">
      </div>
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <div class="mx-2">color</div>
        <input v-model="paragraph.fontColor" class="w-full bg-stone-700 rounded-xl h-10" type="color" id="font-color">
      </div>
    </div>

    <div class="columns-2 mb-4">
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
         <PhArrowsVertical :size="20" class="mx-2" />
        <input v-model.number="paragraph.xPos" class="w-full bg-stone-700 rounded-xl" type="number" id="x-pos" step="any">
      </div>
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <PhArrowsHorizontal :size="20" class="mx-2" />
        <input v-model.number="paragraph.yPos" class="w-full bg-stone-700 rounded-xl" type="number" id="y-pos">
      </div>        
    </div>

    <div class="columns-2 mb-4">
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <div class="mx-2">
          <span class="border-s border-e text-sm border-stone-200 text-stone-200 px-1">A</span>
        </div>
        <input v-model="paragraph.letterSpacing" class="w-full bg-stone-700 rounded-xl h-10" type="number" id="letter-spacing" step="0.1">
      </div>
      <div>
        <!-- <label class="w-full block text-stone-200 text-sm font-bold mb-2">Text Align</label> -->
        <div class="flex justify-between">
          <button
            @click="setTextAlign('left')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', paragraph.textAlign === 'left' ? 'bg-stone-700' : 'bg-transparent']"
          >
            <PhTextAlignLeft :size="20" class="text-stone-200" />
          </button>
          <button
            @click="setTextAlign('center')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', paragraph.textAlign === 'center' ? 'bg-stone-700' : 'bg-transparent']"
          >
            <PhTextAlignCenter :size="20" class="text-stone-200" />
          </button>
          <button
            @click="setTextAlign('right')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', paragraph.textAlign === 'right' ? 'bg-stone-700' : 'bg-transparent']"
          >
            <PhTextAlignRight :size="20" class="text-stone-200" />
          </button>
        </div>
      </div>
    </div> 

    <div class="grid grid-cols-2 gap-4 mb-4">
      <div class="flex items-start">
        <label class="text-stone-200 text-end" for="have-text-box">
          Have text <br>box?
        </label>
        <div class="flex justify-center items-center ps-6 h-full">
          <input v-model="paragraph.haveTextBox" type="checkbox" id="have-text-box">
        </div>
      </div>
    <div 
      :class="[
        'flex items-center text-stone-200 border rounded-xl transition-all duration-300 bg-stone-800',
        paragraph.haveTextBox ? 'border-b-4 border-blue-500 bg-stone-700' : 'border-stone-500 bg-stone-800 opacity-50'
      ]"
    >
      <div class="mx-2">
        <span class="border border-stone-200 text-stone-200 px-1 text-sm">A</span>
      </div>
      <input 
        v-model.number="paragraph.boxWidth" 
        class="w-full bg-stone-700 rounded-xl focus:outline-none" 
        type="number" 
        id="box-width"
        :disabled="!paragraph.haveTextBox"
      >
    </div>
  </div>

    <div class="text-start">
      <button @click="addParagraph" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mb-6 rounded-full mt-4" type="button"><< Add Paragraph</button>
    </div>
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
    },
    setTextAlign(alignment) {
      this.paragraph.textAlign = alignment;
    }
  }
}
</script>


