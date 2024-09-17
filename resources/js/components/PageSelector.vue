<!-- PageSelector.vue -->
<template>
    <div class="page-selector">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-gray-700 text-lg font-bold">Pages:</h2>
        <button @click="addPage" class="text-4xl font-bold py-2 px-4 rounded" title="Add Page to Certificate">
          <PlusCircleIcon class="w-7 h-7 text-blue-500 hover:text-blue-900"/>
        </button>
      </div>
      <div class="grid grid-cols-3 text-center gap-4">
        <div v-for="(page, index) in pages" :key="index" 
             class="relative p-4 border rounded cursor-pointer transition-all duration-200"
             :class="{ 'bg-blue-100 border-blue-500': currentPage === index, 'hover:bg-gray-100': currentPage !== index }"
             @click="selectPage(index)">
          <span class="font-medium text-2xl"> {{ index + 1 }}</span>
          <button @click.stop="deletePage(index)" 
                  class="absolute top-[-6px] right-1 text-red-500 hover:text-red-700 text-3xl"
                  title="Delete Page">
            &times;
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { PlusCircleIcon } from '@heroicons/vue/24/solid'

  export default {
    components: {
        PlusCircleIcon
    },
    props: {
      pages: {
        type: Array,
        required: true
      },
      currentPage: {
        type: Number,
        required: true
      }
    },
    methods: {
      selectPage(index) {
        this.$emit('switch-page', index);
      },
      addPage() {
        this.$emit('add-page');
      },
      deletePage(index) {
        if (this.pages.length > 1) {
          this.$emit('delete-page', index);
        } else {
          alert('Não é possível deletar a última página.');
        }
      }
    }
  }
  </script>