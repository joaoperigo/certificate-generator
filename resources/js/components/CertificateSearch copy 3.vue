<template>
    <div class="w-full max-w-md">
      <Combobox v-model="selectedCertificate">
        <div class="relative mt-1">
          <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
            <ComboboxInput
              class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
              :displayValue="(certificate) => displayValue"
              @change="updateQuery"
              @blur="onBlur"
            />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
              <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </ComboboxButton>
          </div>
          <div class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            <div
              v-if="filteredCertificates.length === 0"
              class="relative cursor-default select-none py-2 px-4 text-gray-700"
            >
              Nothing found.
            </div>
  
            <div
              v-for="certificate in filteredCertificates"
              :key="certificate.id"
              class="relative cursor-default select-none py-2 px-4"
              :class="{
                'bg-teal-600 text-white': selectedCertificate === certificate,
                'text-gray-900': selectedCertificate !== certificate,
              }"
              @click="selectCertificate(certificate)"
            >
              <div class="flex justify-between items-center">
                <span class="block truncate">
                  {{ certificate.title }}
                </span>
                <div class="flex space-x-2">
                  <button
                    @click.stop="viewCertificate(certificate)"
                    class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full hover:bg-blue-200"
                  >
                    View
                  </button>
                  <button
                    @click.stop="editCertificate(certificate)"
                    class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full hover:bg-green-200"
                  >
                    Edit
                  </button>
                  <button
                    @click.stop="deleteCertificate(certificate)"
                    class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full hover:bg-red-200"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Combobox>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import { Combobox, ComboboxInput, ComboboxButton } from '@headlessui/vue'
  import { ChevronUpDownIcon } from '@heroicons/vue/20/solid'
  import axios from 'axios'
  
  const props = defineProps({
    certificates: {
      type: Array,
      required: true
    }
  })
  
  const emit = defineEmits(['update:certificates'])
  
  const selectedCertificate = ref(null)
  const query = ref('')
  const displayValue = ref('')
  
  const updateQuery = (event) => {
    query.value = event.target.value
    displayValue.value = event.target.value
  }
  
  const onBlur = () => {
    if (selectedCertificate.value) {
      displayValue.value = selectedCertificate.value.title
    }
  }
  
  const filteredCertificates = computed(() =>
    query.value === ''
      ? props.certificates
      : props.certificates.filter((certificate) =>
          certificate.title
            .toLowerCase()
            .replace(/\s+/g, '')
            .includes(query.value.toLowerCase().replace(/\s+/g, ''))
        )
  )
  
  const selectCertificate = (certificate) => {
    selectedCertificate.value = certificate
  }
  
  const viewCertificate = (certificate) => {
    window.location.href = `/certificates/${certificate.id}`
  }
  
  const editCertificate = (certificate) => {
    window.location.href = `/certificates/${certificate.id}/edit`
  }
  
  const deleteCertificate = async (certificate) => {
    if (confirm(`Are you sure you want to delete "${certificate.title}"?`)) {
      try {
        await axios.delete(`/certificates/${certificate.id}`, {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        const updatedCertificates = props.certificates.filter(c => c.id !== certificate.id)
        emit('update:certificates', updatedCertificates)
      } catch (error) {
        console.error('Error deleting certificate:', error)
        alert('Failed to delete certificate. Please try again.')
      }
    }
  }
  </script>