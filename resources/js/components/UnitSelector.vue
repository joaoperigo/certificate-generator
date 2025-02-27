<template>
    <div class="space-y-4">
      <!-- Unit Section -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Unit:</label>
        <div class="mb-2">
          <span v-if="selectedUnit" class="px-3 py-1 bg-purple-500 text-white rounded-full flex items-center gap-2 inline-block">
            {{ selectedUnit.name }}
            <button @click.prevent="removeUnit" type="button" class="hover:text-red-300">
              <PhX :size="16" />
            </button>
          </span>
          <span v-else class="text-gray-500 text-sm">No unit selected</span>
        </div>
        
        <div class="flex gap-2 mb-2">
          <select 
            v-model="selectedUnitId"
            class="flex-1 bg-white text-gray-700 rounded-lg border border-gray-300 p-2"
            :disabled="!!selectedUnit"
          >
            <option value="">Select a unit...</option>
            <option v-for="unit in units" :key="unit.id" :value="unit.id">
              {{ unit.name }}
            </option>
          </select>
          <button 
            @click.prevent="setSelectedUnit"
            type="button"
            class="px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600"
            :disabled="!selectedUnitId || !!selectedUnit"
          >
            Add
          </button>
        </div>
        
        <button 
          @click.prevent="toggleNewUnitForm"
          type="button"
          class="mt-2 text-purple-600 hover:text-purple-800 text-sm flex items-center gap-1"
        >
          <PhPlus :size="16" />
          Create new unit
        </button>
        
        <!-- New Unit Form -->
        <div v-if="showNewUnitForm" class="mt-2 p-3 bg-white rounded-lg shadow" @click.stop>
          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input 
              v-model="newUnit.name"
              type="text"
              placeholder="Unit name"
              class="w-full p-2 mb-2 bg-white text-gray-700 rounded border border-gray-300 unit-name-input"
            >
          </div>
          <div class="mb-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="newUnit.description"
              placeholder="Unit description"
              class="w-full p-2 mb-2 bg-white text-gray-700 rounded border border-gray-300"
              rows="3"
            ></textarea>
          </div>
          <div class="flex justify-end gap-2">
            <button 
              @click.prevent="cancelNewUnit"
              type="button"
              class="px-3 py-1 text-gray-600 hover:text-gray-800"
            >
              Cancel
            </button>
            <button 
              @click.prevent="createUnit"
              type="button"
              class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600"
              :disabled="!newUnit.name"
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
      initialUnitId: {
        type: [Number, String],
        default: null
      }
    },
    
    data() {
      return {
        units: [],
        selectedUnit: null,
        selectedUnitId: '',
        showNewUnitForm: false,
        newUnit: {
          name: '',
          description: ''
        },
        loading: false
      };
    },
    
    async mounted() {
      await this.loadUnits();
      if (this.initialUnitId) {
        this.selectedUnitId = this.initialUnitId;
        this.setSelectedUnit();
      }
    },
    
    methods: {
      async loadUnits() {
        this.loading = true;
        try {
          const response = await axios.get('/api/units');
          this.units = response.data;
        } catch (error) {
          console.error('Error loading units:', error);
        } finally {
          this.loading = false;
        }
      },
      
      setSelectedUnit() {
        if (!this.selectedUnitId) return;
        
        const unit = this.units.find(u => u.id === parseInt(this.selectedUnitId));
        if (unit) {
          this.selectedUnit = unit;
          this.selectedUnitId = '';
          this.$emit('update:unit', unit);
        }
      },
      
      removeUnit() {
        this.selectedUnit = null;
        this.selectedUnitId = '';
        this.$emit('update:unit', null);
      },
      
      async createUnit() {
        if (!this.newUnit.name) return;
        
        try {
          const response = await axios.post('/api/units', this.newUnit);
          this.units.push(response.data);
          this.selectedUnit = response.data;
          this.$emit('update:unit', response.data);
          
          // Reset form
          this.newUnit = { name: '', description: '' };
          this.showNewUnitForm = false;
        } catch (error) {
          console.error('Error creating unit:', error);
          alert('Failed to create unit. Please try again.');
        }
      },
      
      toggleNewUnitForm(e) {
        e.preventDefault();
        this.showNewUnitForm = !this.showNewUnitForm;
        
        // Focus the name input after a short delay to ensure the DOM has updated
        if (this.showNewUnitForm) {
          this.$nextTick(() => {
            const nameInput = document.querySelector('.unit-name-input');
            if (nameInput) nameInput.focus();
          });
        }
      },
      
      cancelNewUnit(e) {
        if (e) e.preventDefault();
        this.newUnit = { name: '', description: '' };
        this.showNewUnitForm = false;
      },
      
      async deleteUnit(unit) {
        if (confirm(`Are you sure you want to delete "${unit.name}"?`)) {
          try {
            await axios.delete(`/api/units/${unit.id}`);
            this.units = this.units.filter(u => u.id !== unit.id);
            
            if (this.selectedUnit && this.selectedUnit.id === unit.id) {
              this.selectedUnit = null;
              this.$emit('update:unit', null);
            }
          } catch (error) {
            console.error('Error deleting unit:', error);
            if (error.response && error.response.status === 422) {
              alert(error.response.data.error || 'This unit cannot be deleted because it is in use.');
            } else {
              alert('Failed to delete unit. Please try again.');
            }
          }
        }
      }
    }
  };
  </script>