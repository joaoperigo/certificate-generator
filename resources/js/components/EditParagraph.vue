<!-- EditParagraph.vue -->
<template>
  <div class="edit-paragraph">
    <h2 class="text-xl mt-8 font-bold mb-2">Edit object</h2>
    <div class="mb-4">
      <label class="block text-gray-700 text-base font-bold mb-2" for="object-name">Name of the object:</label>
      <input v-model="editedParagraph.objectName" @input="emitUpdate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="object-name">
    </div>
    <div class="mb-4">
      <label class="block text-gray-700 text-base font-bold mb-2" for="text">Text:</label>
      <textarea v-model="editedParagraph.text" @input="emitUpdate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="text"></textarea>
    </div>
    
    <div class="columns-2 mb-4">
      <div>
        <label class="w-full" for="font-size">Font Size (px):</label>
        <input v-model.number="editedParagraph.fontSize" @input="emitUpdate" class="w-full" type="number" id="font-size">
      </div>
      <div>
        <label class="w-full" for="font-color">Font Color:</label>
        <input v-model="editedParagraph.fontColor" @input="emitUpdate" class="w-full" type="color" id="font-color">
      </div>
    </div>

    <div class="columns-2 mb-4">
      <div>
        <label class="w-full" for="x-pos">Position X (mm):</label>
        <input v-model.number="editedParagraph.xPos" @input="emitUpdate" class="w-full" type="number" id="x-pos">
      </div>
      <div>
        <label class="w-full" for="y-pos">Position Y (mm):</label>
        <input v-model.number="editedParagraph.yPos" @input="emitUpdate" class="w-full" type="number" id="y-pos">
      </div>        
    </div>

    <div class="columns-2 mb-4">
      <div>
        <label class="w-full" for="letter-spacing">Letter Spacing</label>
        <input v-model="editedParagraph.letterSpacing" @input="emitUpdate" class="w-full" type="text" id="letter-spacing">
      </div>
      <div>
        <label class="w-full" for="text-align">Text Align</label>
        <select v-model="editedParagraph.textAlign" @change="emitUpdate" class="w-full" id="text-align">
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
          <input v-model="editedParagraph.haveTextBox" @change="emitUpdate" type="checkbox" id="have-text-box">
        </div>
      </div>
      <div v-if="editedParagraph.haveTextBox">
        <label class="w-full" for="box-width">Box Width (mm):</label>
        <input v-model.number="editedParagraph.boxWidth" @input="emitUpdate" class="w-full" type="number" id="box-width">
      </div>
    </div>

    <div class="mb-4">
      <label class="w-full" for="font-family">Font Family</label>
      <select v-model="editedParagraph.fontFamily" @change="emitUpdate" class="w-full" id="font-family">
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
export default {
  props: {
    paragraph: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      editedParagraph: { ...this.paragraph }
    }
  },
  methods: {
    emitUpdate() {
      this.$emit('update-paragraph', { ...this.editedParagraph })
    },
    saveParagraph() {
      this.$emit('save-paragraph', { ...this.editedParagraph })
    },
    cancel() {
      this.$emit('cancel-edit')
    }
  }
}
</script>