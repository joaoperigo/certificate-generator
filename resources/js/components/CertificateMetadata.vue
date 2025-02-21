<template>
    <div class="space-y-4">
      <!-- Categories Section -->
      <div>
        <label class="block text-stone-200 text-base font-bold mb-2">Categories:</label>
        <div class="flex flex-wrap gap-2 mb-2">
          <span 
            v-for="category in selectedCategories" 
            :key="category.id"
            class="px-3 py-1 bg-purple-500 text-white rounded-full flex items-center gap-2"
          >
            {{ category.name }}
            <button @click="removeCategory(category)" class="hover:text-red-300">
              <PhX :size="16" />
            </button>
          </span>
        </div>
        <div class="flex gap-2">
          <select 
            v-model="newCategoryId"
            class="flex-1 bg-stone-700 text-stone-200 rounded-lg border border-stone-400 p-2"
          >
            <option value="">Select a category...</option>
            <option v-for="category in availableCategories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <button 
            @click="addSelectedCategory"
            class="px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600"
            :disabled="!newCategoryId"
          >
            Add
          </button>
        </div>
        <button 
          @click="showNewCategoryForm = !showNewCategoryForm"
          class="mt-2 text-purple-400 hover:text-purple-300 text-sm flex items-center gap-1"
        >
          <PhPlus :size="16" />
          Create new category
        </button>
        
        <!-- New Category Form -->
        <div v-if="showNewCategoryForm" class="mt-2 p-3 bg-stone-700 rounded-lg">
          <input 
            v-model="newCategory.name"
            type="text"
            placeholder="Category name"
            class="w-full p-2 mb-2 bg-stone-600 text-stone-200 rounded border border-stone-500"
          >
          <div class="flex justify-end gap-2">
            <button 
              @click="showNewCategoryForm = false"
              class="px-3 py-1 text-stone-300 hover:text-stone-100"
            >
              Cancel
            </button>
            <button 
              @click="createCategory"
              class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600"
              :disabled="!newCategory.name"
            >
              Create
            </button>
          </div>
        </div>
      </div>
  
      <!-- Teachers Section -->
      <div>
        <label class="block text-stone-200 text-base font-bold mb-2">Teachers:</label>
        <div class="flex flex-wrap gap-2 mb-2">
          <span 
            v-for="teacher in selectedTeachers" 
            :key="teacher.id"
            class="px-3 py-1 bg-purple-500 text-white rounded-full flex items-center gap-2"
          >
            {{ teacher.name }}
            <button @click="removeTeacher(teacher)" class="hover:text-red-300">
              <PhX :size="16" />
            </button>
          </span>
        </div>
        <div class="flex gap-2">
          <select 
            v-model="newTeacherId"
            class="flex-1 bg-stone-700 text-stone-200 rounded-lg border border-stone-400 p-2"
          >
            <option value="">Select a teacher...</option>
            <option v-for="teacher in availableTeachers" :key="teacher.id" :value="teacher.id">
              {{ teacher.name }}
            </option>
          </select>
          <button 
            @click="addSelectedTeacher"
            class="px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600"
            :disabled="!newTeacherId"
          >
            Add
          </button>
        </div>
        <button 
          @click="showNewTeacherForm = !showNewTeacherForm"
          class="mt-2 text-purple-400 hover:text-purple-300 text-sm flex items-center gap-1"
        >
          <PhPlus :size="16" />
          Create new teacher
        </button>
        
        <!-- New Teacher Form -->
        <div v-if="showNewTeacherForm" class="mt-2 p-3 bg-stone-700 rounded-lg">
          <input 
            v-model="newTeacher.name"
            type="text"
            placeholder="Teacher name"
            class="w-full p-2 mb-2 bg-stone-600 text-stone-200 rounded border border-stone-500"
          >
          <div class="flex justify-end gap-2">
            <button 
              @click="showNewTeacherForm = false"
              class="px-3 py-1 text-stone-300 hover:text-stone-100"
            >
              Cancel
            </button>
            <button 
              @click="createTeacher"
              class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600"
              :disabled="!newTeacher.name"
            >
              Create
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { PhPlus, PhX } from '@phosphor-icons/vue';
  import axios from 'axios';
  
  export default {
    components: {
      PhPlus,
      PhX
    },
    
    props: {
      initialCategories: {
        type: Array,
        default: () => []
      },
      initialTeachers: {
        type: Array,
        default: () => []
      }
    },
  
    data() {
      return {
        categories: [],
        teachers: [],
        selectedCategories: this.initialCategories,
        selectedTeachers: this.initialTeachers,
        newCategoryId: '',
        newTeacherId: '',
        showNewCategoryForm: false,
        showNewTeacherForm: false,
        newCategory: {
          name: ''
        },
        newTeacher: {
          name: ''
        }
      }
    },
  
    async mounted() {
        await Promise.all([
            this.loadCategories(),
            this.loadTeachers()
        ]);
        
        // Initialize selected items from props
        if (this.initialCategories?.length > 0) {
            this.selectedCategories = [...this.initialCategories];
        }
        if (this.initialTeachers?.length > 0) {
            this.selectedTeachers = [...this.initialTeachers];
        }
    },
    computed: {
      availableCategories() {
        return this.categories.filter(category => 
          !this.selectedCategories.some(selected => selected.id === category.id)
        );
      },
      availableTeachers() {
        return this.teachers.filter(teacher => 
          !this.selectedTeachers.some(selected => selected.id === teacher.id)
        );
      }
    },
  
    async created() {
      await this.loadCategories();
      await this.loadTeachers();
    },

    watch: {
        initialCategories: {
            immediate: true,
            handler(newVal) {
                if (newVal && newVal.length > 0) {
                    this.selectedCategories = [...newVal];
                }
            }
        },
        initialTeachers: {
            immediate: true,
            handler(newVal) {
                if (newVal && newVal.length > 0) {
                    this.selectedTeachers = [...newVal];
                }
            }
        }
    },
  
    methods: {
      async loadCategories() {
        try {
          const response = await axios.get('/api/categories');
          this.categories = response.data;
        } catch (error) {
          console.error('Error loading categories:', error);
        }
      },
  
      async loadTeachers() {
        try {
          const response = await axios.get('/api/teachers');
          this.teachers = response.data;
        } catch (error) {
          console.error('Error loading teachers:', error);
        }
      },
  
      async createCategory() {
        try {
          const response = await axios.post('/api/categories', this.newCategory);
          this.categories.push(response.data);
          this.selectedCategories.push(response.data);
          this.newCategory = { name: '' };
          this.showNewCategoryForm = false;
          this.$emit('update:categories', this.selectedCategories);
        } catch (error) {
          console.error('Error creating category:', error);
        }
      },
  
      async createTeacher() {
        try {
          const response = await axios.post('/api/teachers', this.newTeacher);
          this.teachers.push(response.data);
          this.selectedTeachers.push(response.data);
          this.newTeacher = { name: '' };
          this.showNewTeacherForm = false;
          this.$emit('update:teachers', this.selectedTeachers);
        } catch (error) {
          console.error('Error creating teacher:', error);
        }
      },
  
      addSelectedCategory() {
        if (!this.newCategoryId) return;
        const category = this.categories.find(c => c.id === this.newCategoryId);
        if (category) {
          this.selectedCategories.push(category);
          this.newCategoryId = '';
          this.$emit('update:categories', this.selectedCategories);
        }
      },
  
      addSelectedTeacher() {
        if (!this.newTeacherId) return;
        const teacher = this.teachers.find(t => t.id === this.newTeacherId);
        if (teacher) {
          this.selectedTeachers.push(teacher);
          this.newTeacherId = '';
          this.$emit('update:teachers', this.selectedTeachers);
        }
      },
  
      removeCategory(category) {
        this.selectedCategories = this.selectedCategories.filter(c => c.id !== category.id);
        this.$emit('update:categories', this.selectedCategories);
      },
  
      removeTeacher(teacher) {
        this.selectedTeachers = this.selectedTeachers.filter(t => t.id !== teacher.id);
        this.$emit('update:teachers', this.selectedTeachers);
      }
    }
  }
  </script>