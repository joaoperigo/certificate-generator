<template>
    <div class="w-full">
      <Combobox v-model="selectedCertificate">
        <ComboboxLabel>Search for the certificate:</ComboboxLabel>
        <div class="relative mt-1">
          <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-purple-300 sm:text-sm z-10 ">
            <ComboboxInput
              class="w-full border border-b-4 border-slate-600  rounded-xl py-4 pl-5 pr-10 text-xl leading-5 text-slate-900 focus:ring-0 focus:border-purple-500"
              :displayValue="(certificate) => certificate?.title"
              @change="query = $event.target.value"
              placeholder="Enter the certificate name here"
            />
            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
              <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </ComboboxButton>
          </div>
          <div class="absolute max-h-96 w-full overflow-auto border border-t-4 border-b-4 mt-[-10px] border-slate-500  rounded-b-lg bg-slate-100 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm z-0 pt-4">
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
                class="relative cursor-default select-none py-2 px-4 list-none text-xl"
                :class="{
                  'bg-purple-500 text-white': active,
                  'text-gray-900': !active,
                }"
              >
                <div class="flex justify-between items-center">
                  <span class="block truncate">
                    <button @click.stop="viewCertificate(certificate)">
                      {{ certificate.title }}
                    </button> 
                  </span>
                  <div class="flex space-x-2">
                    <button
                      @click.stop="viewCertificate(certificate)"
                      class="px-2 py-2 hover:bg-slate-100 hover:text-slate-700 rounded-full"
                      title="View"
                    >
                      <PhFiles class="w-7 h-7" />
                    </button>
                    <button
                      @click.stop="editCertificate(certificate)"
                      class="px-2 py-2 hover:bg-slate-100 hover:text-slate-700 rounded-full"
                      title="Edit"
                    >
                      <PhPencil class="w-7 h-7" />
                    </button>
                    <button
                      @click.stop="deleteCertificate(certificate)"
                      class="px-2 py-2 hover:bg-slate-100 hover:text-slate-700 rounded-full"
                      title="Delete"
                    >
                      <PhTrash class="w-7 h-7" />
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
  import { Combobox, ComboboxInput, ComboboxButton, ComboboxOption, ComboboxLabel } from '@headlessui/vue'
  import { ChevronUpDownIcon } from '@heroicons/vue/20/solid'
  import { PhFiles, PhTrash, PhPencil } from "@phosphor-icons/vue";

  import axios from 'axios'
  
  export default {
    components: {
      Combobox,
      ComboboxLabel,
      ComboboxInput,
      ComboboxButton,
      ComboboxOption,
      ChevronUpDownIcon,
      PhFiles,
      PhPencil,
      PhTrash
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