<template>
  <div class="object-list pb-96">
    <h2 class="text-xl mt-8 font-bold mb-2">Objects</h2>
    <ul>
      <li v-for="(object, index) in objects" :key="index" class="mb-4 p-4 border rounded bg-stone-900 text-stone-300">
        <div v-if="editingIndex !== index">
          <h3 class="font-bold">{{ object.objectName }}</h3>
          <p>{{ object.text }}</p>
          <div class="mt-2">
            <button @click="startEdit(index)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2">
              Edit
            </button>
            <button @click="deleteObject(index)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">
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

export default {
  components: {
    EditParagraph,
    AddParagraph
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