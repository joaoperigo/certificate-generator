<template>
  <div class="edit-paragraph w-full">
    <h2 class="text-base text-stone-200 font-bold">Edit object</h2>
    <div class="mb-4">
      <input v-model="editedParagraph.objectName" @input="emitUpdate" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-stone-700" type="text" id="object-name" placeholder="Name of the object">
    </div>
    <div class="mb-4 position-relative">
      <textarea v-model="editedParagraph.text" @input="emitUpdate" class="shadow appearance-none rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline border border-b-4 border-stone-400 bg-stone-700" id="text" placeholder="Describe yourself here..."></textarea>
    </div>

    <div class="mb-4">
      <select v-model="editedParagraph.fontFamily" @change="emitUpdate" class="w-full bg-stone-700 text-stone-200 rounded-xl border border-b-4 border-stone-300" id="font-family">
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
        <input v-model.number="editedParagraph.fontSize" @input="emitUpdate" class="w-full bg-stone-700 rounded-xl" type="number" id="font-size" placeholder="font size">
      </div>
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <div class="mx-2">color</div>
        <input v-model="editedParagraph.fontColor" @input="emitUpdate" class="w-full bg-stone-700 rounded-xl h-10" type="color" id="font-color">
      </div>
    </div>

    <div class="columns-2 mb-4">
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
         <PhArrowsHorizontal :size="20" class="mx-2" />
        <input v-model.number="editedParagraph.xPos" @input="emitUpdate" class="w-full bg-stone-700 rounded-xl" type="number" id="x-pos" step="any">
      </div>
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <PhArrowsVertical :size="20" class="mx-2" />
        <input v-model.number="editedParagraph.yPos" @input="emitUpdate" class="w-full bg-stone-700 rounded-xl" type="number" id="y-pos">
      </div>        
    </div>

    <div class="columns-2 mb-4">
      <div class="flex content-between items-center text-stone-200 border border-b-4 rounded-xl">
        <div class="mx-2">
          <span class="border-s border-e text-sm border-stone-200 text-stone-200 px-1">A</span>
        </div>
        <input v-model="editedParagraph.letterSpacing" @input="emitUpdate" class="w-full bg-stone-700 rounded-xl h-10" type="number" id="letter-spacing" step="0.1">
      </div>
      <div>
        <div class="flex justify-between">
          <button
            @click="setTextAlign('left')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', editedParagraph.textAlign === 'left' ? 'bg-stone-700' : 'bg-transparent']"
          >
            <PhTextAlignLeft :size="20" class="text-stone-200" />
          </button>
          <button
            @click="setTextAlign('center')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', editedParagraph.textAlign === 'center' ? 'bg-stone-700' : 'bg-transparent']"
          >
            <PhTextAlignCenter :size="20" class="text-stone-200" />
          </button>
          <button
            @click="setTextAlign('right')"
            :class="['p-2 rounded border border-b-4 border-stone-400 bg-stone-700', editedParagraph.textAlign === 'right' ? 'bg-stone-700' : 'bg-transparent']"
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
          v-model="editedParagraph.haveTextBox"
          @change="emitUpdate"
          :class="[
            editedParagraph.haveTextBox ? 'bg-stone-600' : 'bg-stone-700',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-stone-600 focus:ring-offset-2 rotate-90'
          ]"
        >
          <span
            :class="[
              editedParagraph.haveTextBox ? 'translate-x-5 rotate-[270deg]' : 'translate-x-0',
              'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out rotate-[270deg]'
            ]"
          >
            <span
              :class="[
                editedParagraph.haveTextBox ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in',
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
                editedParagraph.haveTextBox ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out',
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
          editedParagraph.haveTextBox ? 'border-b-4 border-stone-300 bg-stone-700' : 'border-stone-500 bg-stone-800 opacity-50'
        ]"
      >
        <div class="mx-2">
          <span class="border border-stone-200 text-stone-200 px-1 text-sm">A</span>
        </div>
        <input 
          v-model.number="editedParagraph.boxWidth" 
          @input="emitUpdate"
          class="w-full bg-stone-700 rounded-xl focus:outline-none" 
          type="number" 
          id="box-width"
          :disabled="!editedParagraph.haveTextBox"
        >
      </div>
    </div>

    <div class="grid grid-flow-col justify-stretch">
      <button @click="saveParagraph" class="bg-stone-500 hover:bg-stone-700 text-white font-bold py-3 ps-0 pe-4 mb-6 rounded mt-4 inline-flex justify-center items-center gap-3" type="button">
        <PhCheck :size="24" />
        Save 
      </button>
      <button @click="cancel" class="bg-stone-700 hover:bg-stone-600 text-white font-bold py-3 ps-0 pe-4 mb-6 rounded mt-4 ml-2 inline-flex justify-center items-center gap-3" type="button">
        <PhX :size="24" />
        Cancel
      </button>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { Switch } from '@headlessui/vue'
import { PhArrowsHorizontal, PhArrowsVertical, PhTextAlignLeft, PhTextAlignCenter, PhTextAlignRight, PhX, PhCheck } from '@phosphor-icons/vue';

export default {
  components: {
    Switch,
    PhArrowsVertical,
    PhArrowsHorizontal,
    PhTextAlignLeft,
    PhTextAlignCenter,
    PhTextAlignRight,
    PhX,
    PhCheck
  },
  props: {
    paragraph: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const editedParagraph = ref({
      ...props.paragraph,
      xPos: props.paragraph.xPos,
      yPos: props.paragraph.yPos,
      boxWidth: props.paragraph.boxWidth ? props.paragraph.boxWidth : undefined
    })

    function emitUpdate() {
      emit('update-paragraph', { ...editedParagraph.value })
    }

    function saveParagraph() {
      const savedParagraph = {
        ...editedParagraph.value,
        xPos: editedParagraph.value.xPos,
        yPos: editedParagraph.value.yPos,
        boxWidth: editedParagraph.value.boxWidth ? editedParagraph.value.boxWidth : undefined
      }
      emit('save-paragraph', savedParagraph)
    }

    function cancel() {
      emit('cancel-edit')
    }

    function setTextAlign(alignment) {
      editedParagraph.value.textAlign = alignment;
      emitUpdate();
    }

    return {
      editedParagraph,
      emitUpdate,
      saveParagraph,
      cancel,
      setTextAlign
    }
  }
}
</script>