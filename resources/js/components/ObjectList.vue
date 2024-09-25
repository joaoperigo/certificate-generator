<template>
  <div class="object-list sticky top-0 z-20 bg-stone-800 pt-4 border-t border-stone-600">
    <h2 class="font-bold mb-2 text-stone-200 text-base px-4" >Edit Objects</h2>
    <ul>
      <li v-for="(object, index) in objects" :key="index" class="border-b border-stone-600 rounded  text-stone-300 px-4 py-4">
        <div v-if="editingIndex !== index">
          <h3 class="font-bold text-xl">{{ object.objectName }}</h3>
          <p>{{ object.text }}</p>
          <div class="mt-2 flex">
            <button @click="startEdit(index)" class=" hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
              <PhPencilLine :size="20" />
              Edit
            </button>
            <button @click="deleteObject(index)" class=" hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded me-2 flex align-between items-center gap-2">
              <PhEraser :size="20" />
              Delete
            </button>
          </div>
        </div>
        <edit-paragraph 
          v-else
          :paragraph="object"
          @update-paragraph="updateParagraph($event, index)"
          @save-paragraph="saveParagraph($event, index)"
          @cancel-edit="cancelEdit"
        ></edit-paragraph>
      </li>
    </ul>
    
    <!-- <add-paragraph @add-paragraph="addObject"></add-paragraph> -->
  </div>
</template>

<script>
import EditParagraph from './EditParagraph.vue'
import AddParagraph from './AddParagraph.vue'

import { PhPencilLine, PhEraser } from '@phosphor-icons/vue';

export default {
  components: {
    EditParagraph,
    AddParagraph,
    PhPencilLine,
    PhEraser
  },
  props: {
    objects: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      editingIndex: -1
    }
  },
  methods: {
    startEdit(index) {
      this.editingIndex = index
    },
    updateParagraph(updatedObject, index) {
      this.$emit('update-object', index, updatedObject)
    },
    saveParagraph(updatedObject, index) {
      this.$emit('update-object', index, updatedObject)
      this.editingIndex = -1
    },
    cancelEdit() {
      this.editingIndex = -1
    },
    deleteObject(index) {
      this.$emit('delete-object', index)
    },
    // addObject(newObject) {
    //   this.$emit('add-object', newObject)
    // }
  }
}
</script>