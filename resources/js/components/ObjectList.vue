<template>
  <div class="object-list pb-[600px]" id="objects-list">
    <h2 class="mt-8 font-bold mb-2 text-stone-200 text-base">Edit Objects</h2>
    <ul>
      <li v-for="(object, index) in objects" :key="index" class="mb-4 p-4 border rounded bg-stone-900 text-stone-300">
        <div v-if="editingIndex !== index">
          <h3 class="font-bold text-xl">{{ object.objectName }}</h3>
          <p>{{ object.text }}</p>
          <div class="mt-2 flex">
            <button @click="startEdit(index)" class="bg-stone-500 hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded-full mr-2 flex align-between items-center gap-2">
              <PhPencilSimple :size="20" />
              Edit
            </button>
            <button @click="deleteObject(index)" class="border bg-transparent hover:bg-stone-700 text-white font-bold py-3 ps-3 pe-5 rounded-full mr-2 flex align-between items-center gap-2">
              <PhPencilSimple :size="20" />
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

import { PhPencilSimple } from '@phosphor-icons/vue';

export default {
  components: {
    EditParagraph,
    AddParagraph,
    PhPencilSimple
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