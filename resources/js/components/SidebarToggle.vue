<template>
    <button
      @click="$emit('toggle')"
      class="z-10 p-2 rounded-xl  hover:bg-stone-700 transition-all duration-300 "
    >
      <ChevronDoubleLeftIcon v-if="!isCollapsed && position === 'left'" class="h-4 w-4 text-stone-100" />
      <ChevronDoubleRightIcon v-if="isCollapsed && position === 'left'" class="h-4 w-4 text-stone-100" />
      <ChevronDoubleRightIcon v-if="!isCollapsed && position === 'right'" class="h-4 w-4 text-stone-100" />
      <ChevronDoubleLeftIcon v-if="isCollapsed && position === 'right'" class="h-4 w-4 text-stone-100" />
    </button>
  </template>
  
  <script>
  import { ChevronDoubleLeftIcon, ChevronDoubleRightIcon } from '@heroicons/vue/24/solid'
  
  export default {
    components: {
      ChevronDoubleLeftIcon,
      ChevronDoubleRightIcon,
    },
    props: {
      position: {
        type: String,
        required: true,
        validator: (value) => ['left', 'right'].includes(value)
      },
      isCollapsed: {
        type: Boolean,
        required: true
      }
    }
  }
  </script>