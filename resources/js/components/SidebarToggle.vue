<template>
    <button
      @click="$emit('toggle')"
      class="z-10 p-2 rounded-full bg-gray-200 hover:bg-gray-300 transition-all duration-300"
    >
      <ChevronLeftIcon v-if="!isCollapsed && position === 'left'" class="h-6 w-6" />
      <ChevronRightIcon v-if="isCollapsed && position === 'left'" class="h-6 w-6" />
      <ChevronRightIcon v-if="!isCollapsed && position === 'right'" class="h-6 w-6" />
      <ChevronLeftIcon v-if="isCollapsed && position === 'right'" class="h-6 w-6" />
    </button>
  </template>
  
  <script>
  import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid'
  
  export default {
    components: {
      ChevronLeftIcon,
      ChevronRightIcon
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