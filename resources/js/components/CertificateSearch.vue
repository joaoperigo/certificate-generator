<template>
  <div class="w-full">
    <!-- Filter section -->
    <div class="mb-4">
      <!-- <h3 class="text-lg font-medium mb-3 text-slate-700">Filter Certificates</h3> -->
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Teachers Filter -->
        <div class="relative">
          <button 
            @click="dropdownOpen.teachers = !dropdownOpen.teachers"
            class="w-full flex justify-between items-center px-4 py-2 bg-white border border-b-4 border-stone-600 rounded-lg shadow-sm focus:border-purple-500 z-30 relative"
          >
            <span class="text-sm font-medium text-slate-700">
              Teachers <span class="text-xs text-slate-500">({{ selectedTeachers.length }} selected)</span>
            </span>
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              :class="dropdownOpen.teachers ? 'rotate-180' : ''" 
              class="h-5 w-5 text-slate-500 transition-transform duration-200" 
              viewBox="0 0 20 20" 
              fill="currentColor"
            >
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Dropdown panel -->
          <div 
            v-show="dropdownOpen.teachers" 
            class="absolute z-20 w-full mt-[-8px] bg-white border border-b-4 border-slate-500 rounded-b-lg shadow-lg py-2 pt-4 px-3"
          >
            <div class="flex justify-between items-center mb-2">
              <span class="text-xs font-medium text-slate-500">Select teachers</span>
              <div class="flex space-x-2">
                <button 
                  @click="selectAllTeachers" 
                  class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded hover:bg-purple-200"
                >
                  All
                </button>
                <button 
                  @click="clearTeacherSelection" 
                  class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded hover:bg-slate-200"
                >
                  None
                </button>
              </div>
            </div>
            
            <div class="max-h-40 overflow-y-auto">
              <div v-if="loading.teachers" class="text-center py-2 text-sm text-slate-500">
                Loading teachers...
              </div>
              <div v-else-if="teachers.length === 0" class="text-center py-2 text-sm text-slate-500">
                No teachers available
              </div>
              <div v-else class="space-y-1">
                <label v-for="teacher in sortedTeachers" :key="teacher.id" class="flex items-center p-1 rounded hover:bg-slate-50">
                  <input 
                    type="checkbox" 
                    :value="teacher.id" 
                    v-model="selectedTeachers" 
                    class="form-checkbox h-4 w-4 text-purple-600"
                  />
                  <span class="ml-2 text-sm">{{ teacher.name }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Categories Filter -->
        <div class="relative">
          <button 
            @click="dropdownOpen.categories = !dropdownOpen.categories"
            class="w-full flex justify-between items-center px-4 py-2 bg-white border border-b-4 border-slate-600 rounded-lg shadow-sm focus:border-purple-500 relative z-30"
          >
            <span class="text-sm font-medium text-slate-700">
              Categories <span class="text-xs text-slate-500">({{ selectedCategories.length }} selected)</span>
            </span>
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              :class="dropdownOpen.categories ? 'rotate-180' : ''" 
              class="h-5 w-5 text-slate-500 transition-transform duration-200" 
              viewBox="0 0 20 20" 
              fill="currentColor"
            >
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Dropdown panel -->
          <div 
            v-show="dropdownOpen.categories" 
            class="absolute z-20 w-full mt-[-8px] bg-white border border-b-4 border-slate-500 rounded-b-lg shadow-lg py-2 pt-4 px-3"
          >
            <div class="flex justify-between items-center mb-2">
              <span class="text-xs font-medium text-slate-500">Select categories</span>
              <div class="flex space-x-2">
                <button 
                  @click="selectAllCategories" 
                  class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded hover:bg-purple-200"
                >
                  All
                </button>
                <button 
                  @click="clearCategorySelection" 
                  class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded hover:bg-slate-200"
                >
                  None
                </button>
              </div>
            </div>
            
            <div class="max-h-40 overflow-y-auto">
              <div v-if="loading.categories" class="text-center py-2 text-sm text-slate-500">
                Loading categories...
              </div>
              <div v-else-if="categories.length === 0" class="text-center py-2 text-sm text-slate-500">
                No categories available
              </div>
              <div v-else class="space-y-1">
                <label v-for="category in sortedCategories" :key="category.id" class="flex items-center p-1 rounded hover:bg-slate-50">
                  <input 
                    type="checkbox" 
                    :value="category.id" 
                    v-model="selectedCategories" 
                    class="form-checkbox h-4 w-4 text-purple-600"
                  />
                  <span class="ml-2 text-sm">{{ category.name }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Certificate Search -->
    <Combobox v-model="selectedCertificate">
      <!-- <ComboboxLabel>Search for the certificate:</ComboboxLabel> -->
      <div class="relative mt-1">
        <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75 focus-visible:ring-offset-2 focus-visible:ring-offset-purple-300 sm:text-sm z-10 ">
          <ComboboxInput
            class="w-full border border-b-4 border-slate-600 rounded-xl py-4 pl-5 pr-10 text-xl leading-5 text-slate-900 focus:ring-0 focus:border-purple-500"
            :displayValue="(certificate) => certificate?.title"
            @change="query = $event.target.value"
            placeholder="Enter the certificate name here"
          />
          <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
          </ComboboxButton>
        </div>
        <div class="absolute max-h-96 w-full overflow-auto border border-t-4 border-b-4 mt-[-10px] border-slate-500 rounded-b-lg bg-slate-100 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm z-0 pt-4">
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
                <span class="flex items-center">
                  <button @click.stop="viewCertificate(certificate)" class="truncate font-semibold me-8 w-64 text-start">
                    {{ certificate.title }}
                  </button>
                  <span v-if="certificate.teachers && certificate.teachers.length" class="ml-2 px-2 py-1 rounded-full bg-blue-100 text-slate-900 text-sm">
                     {{ certificate.teachers.map(t => t.name).join(', ') }}
                  </span>
                  <span v-if="certificate.categories && certificate.categories.length" class="ml-2 px-2 py-1 rounded-full bg-green-100 text-slate-900 text-sm">
                     {{ certificate.categories.map(c => c.name).join(', ') }}
                  </span>
                </span>
                <div class="flex items-center">
                  <!-- Certificate metadata -->
                  <div class="mr-4 text-sm">
                    <!-- <span v-if="certificate.teachers && certificate.teachers.length" class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs mr-1">
                      {{ certificate.teachers.length }} teacher(s)
                    </span>
                    <span v-if="certificate.categories && certificate.categories.length" class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs">
                      {{ certificate.categories.length }} category(s)
                    </span> -->
                  </div>
                  <!-- Action buttons -->
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
      query: '',
      teachers: [],
      categories: [],
      selectedTeachers: [],
      selectedCategories: [],
      loading: {
        teachers: false,
        categories: false
      },
      dropdownOpen: {
        teachers: false,
        categories: false
      }
    }
  },
  computed: {
    filteredCertificates() {
      // Start with title filter
      let filtered = this.query === ''
        ? this.certificates
        : this.certificates.filter((certificate) =>
            certificate.title
              .toLowerCase()
              .replace(/\s+/g, '')
              .includes(this.query.toLowerCase().replace(/\s+/g, ''))
          );
      
      // Then filter by selected teachers if needed
      if (this.selectedTeachers.length > 0) {
        filtered = filtered.filter(certificate => {
          // If certificate has no teachers property or it's empty, skip if we're filtering by teachers
          if (!certificate.teachers || certificate.teachers.length === 0) {
            return false;
          }
          
          // Check if any of the certificate's teachers match the selected teachers
          return certificate.teachers.some(teacher => 
            this.selectedTeachers.includes(teacher.id)
          );
        });
      }
      
      // Then filter by selected categories if needed
      if (this.selectedCategories.length > 0) {
        filtered = filtered.filter(certificate => {
          // If certificate has no categories property or it's empty, skip if we're filtering by categories
          if (!certificate.categories || certificate.categories.length === 0) {
            return false;
          }
          
          // Check if any of the certificate's categories match the selected categories
          return certificate.categories.some(category => 
            this.selectedCategories.includes(category.id)
          );
        });
      }
      
      // Sort certificates alphabetically by title
      return filtered.sort((a, b) => a.title.localeCompare(b.title));
    },
    sortedTeachers() {
      return [...this.teachers].sort((a, b) => a.name.localeCompare(b.name));
    },
    sortedCategories() {
      return [...this.categories].sort((a, b) => a.name.localeCompare(b.name));
    }
  },
  created() {
    this.fetchTeachers();
    this.fetchCategories();
  },
  mounted() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', this.closeDropdownOnClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.closeDropdownOnClickOutside);
  },
  methods: {
    async fetchTeachers() {
      this.loading.teachers = true;
      try {
        const response = await axios.get('/api/teachers');
        this.teachers = response.data;
      } catch (error) {
        console.error('Error fetching teachers:', error);
      } finally {
        this.loading.teachers = false;
      }
    },
    async fetchCategories() {
      this.loading.categories = true;
      try {
        const response = await axios.get('/api/categories');
        this.categories = response.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
      } finally {
        this.loading.categories = false;
      }
    },
    selectAllTeachers() {
      this.selectedTeachers = this.teachers.map(teacher => teacher.id);
    },
    clearTeacherSelection() {
      this.selectedTeachers = [];
    },
    selectAllCategories() {
      this.selectedCategories = this.categories.map(category => category.id);
    },
    clearCategorySelection() {
      this.selectedCategories = [];
    },
    viewCertificate(certificate) {
      window.location.href = `/certificates/${certificate.id}`;
    },
    editCertificate(certificate) {
      window.location.href = `/certificates/${certificate.id}/edit`;
    },
    async deleteCertificate(certificate) {
      if (confirm(`Are you sure you want to delete "${certificate.title}"?`)) {
        try {
          await axios.delete(`/certificates/${certificate.id}`, {
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          alert('Certificate deleted successfully');
          // Reload the page after successful deletion
          window.location.reload();
        } catch (error) {
          console.error('Error deleting certificate:', error);
          alert('Failed to delete certificate. Please try again.');
        }
      }
    },
    
    closeDropdownOnClickOutside(event) {
      // Close teachers dropdown if clicking outside
      if (this.dropdownOpen.teachers && !event.target.closest('.relative')) {
        this.dropdownOpen.teachers = false;
      }
      
      // Close categories dropdown if clicking outside
      if (this.dropdownOpen.categories && !event.target.closest('.relative')) {
        this.dropdownOpen.categories = false;
      }
    }
  }
}
</script>