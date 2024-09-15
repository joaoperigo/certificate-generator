<!-- PageSelector.vue -->
<template>
    <div class="page-selector">
      <label class="block text-gray-700 text-base font-bold mb-2" for="page-select">Certificate Page(s):</label>
      <select id="page-select" v-model="selectedPage" @change="switchPage">
        <option v-for="(page, index) in pages" :key="index" :value="index">
          Page {{ index + 1 }}
        </option>
      </select>
      <button @click="addPage" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
        Add Page
      </button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      pages: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        selectedPage: 0
      }
    },
    methods: {
      switchPage() {
        this.$emit('switch-page', this.selectedPage)
      },
      addPage() {
        this.$emit('add-page')
        this.selectedPage = this.pages.length
      }
    }
  }
  </script>