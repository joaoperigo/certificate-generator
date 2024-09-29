<template>
  <div class="add-paragraph w-full border-b border-stone-600 px-4 pt-20 sticky top-12" id="add-paragraph">
    <h2 class="text-base text-stone-200 mt-0 font-bold mb-2">Create object</h2>
    <div class="mb-4">
      <input v-model="paragraph.objectName" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-stone-700" type="text" id="object-name" placeholder="Name of the object">
    </div>
    <div class="mb-4 position-relative">
      <textarea v-model="paragraph.text" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-stone-700" id="text" placeholder="Describe yourself here..."></textarea>
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
      <div class="flex items-center justify-between gap-2">
        <label class="text-stone-200 text-end text-sm" for="have-text-box">
          Have text box?
        </label>
        <Switch
          v-model="paragraph.haveTextBox"
          :class="[
            paragraph.haveTextBox ? 'bg-stone-600' : 'bg-stone-700',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-stone-600 focus:ring-offset-2 rotate-90'
          ]"
        >
          <span
            :class="[
              paragraph.haveTextBox ? 'translate-x-5 rotate-[270deg]' : 'translate-x-0',
              'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out rotate-[270deg]'
            ]"
          >
            <span
              :class="[
                paragraph.haveTextBox ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in',
                'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity'
              ]"
              aria-hidden="true"
            >
              <svg class="h-3 w-3 text-stone-400" fill="none" viewBox="0 0 12 12">
                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </span>
            <span
              :class="[
                paragraph.haveTextBox ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out',
                'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity'
              ]"
              aria-hidden="true"
            >
              <svg class="h-3 w-3 text-stone-600" fill="currentColor" viewBox="0 0 12 12">
                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
              </svg>
            </span>
          </span>
        </Switch>
      </div>
      <div 
        :class="[
          'flex items-center text-stone-200 border border-b-4 rounded-xl transition-all duration-300 bg-stone-800',
          paragraph.haveTextBox ? 'border-b-4 border-stone-300 bg-stone-700' : 'border-stone-500 bg-stone-800 opacity-50'
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
      <button @click="addParagraph" class=" border border-b-4 border-stone-600  hover:bg-stone-300 text-stone-900 bg-stone-100 font-bold py-3 ps-2 pe-4 mb-6 rounded-xl mt-6 flex justify-center gap-2 items-center w-full" type="button" id="objects-list">
        <PhCaretDoubleLeft :size="20" class="text-stone-900" />
        Add Paragraph
      </button>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { Switch } from '@headlessui/vue'
import { PhArrowsHorizontal, PhArrowsVertical, PhTextAlignLeft, PhTextAlignCenter, PhTextAlignRight, PhPlusCircle, PhCaretDoubleLeft } from '@phosphor-icons/vue';

export default {
  components: {
    Switch,
    PhArrowsVertical,
    PhArrowsHorizontal,
    PhTextAlignLeft,
    PhTextAlignCenter,
    PhTextAlignRight,
    PhPlusCircle,
    PhCaretDoubleLeft
  },
  setup(props, { emit }) {
    const paragraph = ref({
      objectName: '',
      text: '',
      fontSize: 30,
      fontColor: '#2c2c2c',
      xPos: 19,
      yPos: 89,
      boxWidth: 0,
      haveTextBox: false,
      letterSpacing: 0,
      fontFamily: 'Mangueira-Semibold',
      textAlign: 'left'
    })

    function addParagraph() {
      const newParagraph = { ...paragraph.value }
      newParagraph.xPos *= 3.779528
      newParagraph.yPos *= 3.779528
      if (newParagraph.boxWidth) {
        newParagraph.boxWidth *= 3.779528
      }
      
      emit('add-paragraph', newParagraph)
      // Reset form after adding
      paragraph.value = {
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

    function setTextAlign(alignment) {
      paragraph.value.textAlign = alignment;
    }

    return {
      paragraph,
      addParagraph,
      setTextAlign
    }
  }
}
</script>