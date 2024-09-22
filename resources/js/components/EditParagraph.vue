<template>
  <div class="edit-paragraph">
    <h2 class="text-xl mt-8 font-bold mb-2 text-stone-200">Edit object</h2>
    <div class="mb-4">
      <label class="block text-stone-200 text-base font-bold mb-2" for="object-name">Name of the object:</label>
      <input v-model="editedParagraph.objectName" @input="emitUpdate" class="shadow appearance-none border rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline bg-stone-700" type="text" id="object-name">
    </div>
    <div class="mb-4">
      <label class="block text-stone-200 text-base font-bold mb-2" for="text">Text:</label>
      <textarea v-model="editedParagraph.text" @input="emitUpdate" class="shadow appearance-none border rounded w-full py-2 px-3 text-stone-200 leading-tight focus:outline-none focus:shadow-outline bg-stone-700" id="text"></textarea>
    </div>
    
    <div class="columns-2 mb-4">
      <div>
        <label class="w-full text-stone-200" for="font-size">Font Size (px):</label>
        <input v-model.number="editedParagraph.fontSize" @input="emitUpdate" class="w-full bg-stone-700 text-stone-200" type="number" id="font-size">
      </div>
      <div>
        <label class="w-full text-stone-200" for="font-color">Font Color:</label>
        <input v-model="editedParagraph.fontColor" @input="emitUpdate" class="w-full bg-stone-700" type="color" id="font-color">
      </div>
    </div>

    <div class="columns-2 mb-4">
      <div>
        <label class="w-full text-stone-200" for="x-pos">Position X (mm):</label>
        <input v-model.number="editedParagraph.xPos" @input="emitUpdate" class="w-full bg-stone-700 text-stone-200" type="number" id="x-pos">
      </div>
      <div>
        <label class="w-full text-stone-200" for="y-pos">Position Y (mm):</label>
        <input v-model.number="editedParagraph.yPos" @input="emitUpdate" class="w-full bg-stone-700 text-stone-200" type="number" id="y-pos">
      </div>        
    </div>

    <div class="columns-2 mb-4">
      <div>
        <label class="w-full text-stone-200" for="letter-spacing">Letter Spacing</label>
        <input v-model="editedParagraph.letterSpacing" @input="emitUpdate" class="w-full bg-stone-700 text-stone-200" type="text" id="letter-spacing">
      </div>
      <div>
        <label class="w-full text-stone-200" for="text-align">Text Align</label>
        <select v-model="editedParagraph.textAlign" @change="emitUpdate" class="w-full bg-stone-700 text-stone-200" id="text-align">
          <option value="left">left</option>
          <option value="center">center</option>
          <option value="right">right</option>
        </select>
      </div>
    </div> 

    <div class="columns-2 mb-4">
      <div class="flex items-center justify-between">
        <label class="text-stone-200" for="have-text-box">Have text box?</label>
        <Switch
          v-model="editedParagraph.haveTextBox"
          @change="emitUpdate"
          :class="[
            editedParagraph.haveTextBox ? 'bg-blue-600' : 'bg-stone-700',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2'
          ]"
        >
          <span
            :class="[
              editedParagraph.haveTextBox ? 'translate-x-5' : 'translate-x-0',
              'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out'
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
              <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 12 12">
                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
              </svg>
            </span>
          </span>
        </Switch>
      </div>
      <div v-if="editedParagraph.haveTextBox">
        <label class="w-full text-stone-200" for="box-width">Box Width (mm):</label>
        <input v-model.number="editedParagraph.boxWidth" @input="emitUpdate" class="w-full bg-stone-700 text-stone-200" type="number" id="box-width">
      </div>
    </div>

    <div class="mb-4">
      <label class="w-full text-stone-200" for="font-family">Font Family</label>
      <select v-model="editedParagraph.fontFamily" @change="emitUpdate" class="w-full bg-stone-700 text-stone-200" id="font-family">
        <option value="Mangueira-Semibold">Mangueira Regular</option>
        <option value="Mangueira-Medium">Mangueira Bold</option>
        <option value="Myriad-Medium">Myriad Medium</option>
      </select>
    </div>

    <button @click="saveParagraph" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6" type="button">Save Changes</button>
    <button @click="cancel" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6 ml-2" type="button">Cancel</button>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { Switch } from '@headlessui/vue'

export default {
  components: {
    Switch
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
      xPos: props.paragraph.xPos / 3.779528,
      yPos: props.paragraph.yPos / 3.779528,
      boxWidth: props.paragraph.boxWidth ? props.paragraph.boxWidth / 3.779528 : undefined
    })

    function emitUpdate() {
      emit('update-paragraph', { ...editedParagraph.value })
    }

    function saveParagraph() {
      const savedParagraph = {
        ...editedParagraph.value,
        xPos: editedParagraph.value.xPos * 3.779528,
        yPos: editedParagraph.value.yPos * 3.779528,
        boxWidth: editedParagraph.value.boxWidth ? editedParagraph.value.boxWidth * 3.779528 : undefined
      }
      emit('save-paragraph', savedParagraph)
    }

    function cancel() {
      emit('cancel-edit')
    }

    return {
      editedParagraph,
      emitUpdate,
      saveParagraph,
      cancel
    }
  }
}
</script>