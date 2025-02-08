<!-- PageSelector.vue -->
<template>
    <div class="page-selector">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-stone-200 text-base font-bold">PDF Pages:</h2>
      </div>
      <div class="grid grid-cols-4 text-center gap-2">
        <div v-for="(page, index) in pages" :key="index" 
             class="relative p-4 border rounded cursor-pointer transition-all duration-200 border-1 border-b-4 border-stone-600"
             :class="{ 'bg-stone-600 border-blue-500': currentPage === index, 'hover:bg-stone-700': currentPage !== index }"
             @click="selectPage(index)">
          <span class="font-medium text-lg text-stone-200"> {{ index + 1 }}</span>
          <button @click.stop="deletePage(index)" 
                  class="absolute top-[-6px] right-1 text-red-500 hover:text-red-700 text-3xl"
                  title="Delete Page">
            &times;
          </button>
        </div>
        <button @click="addPage" class="text-4xl font-bold p-3 rounded bg-stone-200" title="Add Page to Certificate">
          +
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import { PlusCircleIcon } from '@heroicons/vue/24/solid'
  import { PhFilePlus } from '@phosphor-icons/vue'

  export default {
    components: {
        PlusCircleIcon,
        PhFilePlus
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