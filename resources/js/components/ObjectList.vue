<!-- ObjectList.vue -->
<template>
    <div class="object-list">
      <h2 class="text-xl mt-8 font-bold mb-2">Objects</h2>
      <ul v-if="!editingObject">
        <li v-for="(object, index) in objects" :key="index" class="mb-4 p-4 border rounded">
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
        </li>
      </ul>
      
      <edit-paragraph 
        v-if="editingObject"
        :paragraph="editingObject"
        @update-paragraph="updateParagraph"
        @save-paragraph="saveParagraph"
        @cancel-edit="cancelEdit"
      ></edit-paragraph>
    </div>
  </template>
  
  <script>
  import EditParagraph from './EditParagraph.vue'
  
  export default {
    components: {
      EditParagraph
    },
    props: {
      objects: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        editingObject: null,
        editingIndex: -1
      }
    },
    methods: {
      startEdit(index) {
        this.editingObject = { ...this.objects[index] }
        this.editingIndex = index
      },
      updateParagraph(updatedObject) {
        this.$emit('update-object', this.editingIndex, updatedObject)
      },
      saveParagraph(updatedObject) {
        this.$emit('update-object', this.editingIndex, updatedObject)
        this.editingObject = null
        this.editingIndex = -1
      },
      cancelEdit() {
        this.editingObject = null
        this.editingIndex = -1
      },
      deleteObject(index) {
        this.$emit('delete-object', index)
      }
    }
  }
  </script>