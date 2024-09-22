<template>
    <div class="w-full max-w-md">
      <Combobox v-model="selectedCertificate">
        <div class="relative mt-1">
          <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
            <ComboboxInput
              class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
              :displayValue="(certificate) => certificate?.title"
              @change="query = $event.target.value"
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
  
            <ComboboxOption
              v-for="certificate in filteredCertificates"
              :key="certificate.id"
              :value="certificate"
              as="template"
              v-slot="{ selected, active }"
            >
              <li
                class="relative cursor-default select-none py-2 px-4"
                :class="{
                  'bg-teal-600 text-white': active,
                  'text-gray-900': !active,
                }"
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
              </li>
            </ComboboxOption>
          </div>
        </div>
      </Combobox>
    </div>
  </template>
  
  <script>
  import { Combobox, ComboboxInput, ComboboxButton, ComboboxOption } from '@headlessui/vue'
  import { ChevronUpDownIcon } from '@heroicons/vue/20/solid'
  import axios from 'axios'
  
  export default {
    components: {
      Combobox,
      ComboboxInput,
      ComboboxButton,
      ComboboxOption,
      ChevronUpDownIcon
    },
    props: {
      certificates: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        selectedCertificate: null,
        query: ''
      }
    },
    computed: {
      filteredCertificates() {
        return this.query === ''
          ? this.certificates
          : this.certificates.filter((certificate) =>
              certificate.title
                .toLowerCase()
                .replace(/\s+/g, '')
                .includes(this.query.toLowerCase().replace(/\s+/g, ''))
            )
      }
    },
    methods: {
      viewCertificate(certificate) {
        window.location.href = `/certificates/${certificate.id}`
      },
      editCertificate(certificate) {
        window.location.href = `/certificates/${certificate.id}/edit`
      },
      async deleteCertificate(certificate) {
        if (confirm(`Are you sure you want to delete "${certificate.title}"?`)) {
          try {
            await axios.delete(`/certificates/${certificate.id}`, {
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            })
            // Recarrega a página após a exclusão bem-sucedida
            window.location.reload()
          } catch (error) {
            console.error('Error deleting certificate:', error)
            alert('Failed to delete certificate. Please try again.')
          }
        }
      }
    }
  }
  </script>