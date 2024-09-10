<template>
    <div>
      <!-- Input para selecionar arquivo -->
      <input type="file" @change="handleFileChange" />
      <!-- Botão para upload de imagem -->
      <button @click="uploadImage">Upload Image</button>
  
      <!-- Lista de Imagens Disponíveis -->
      <select v-model="selectedImage" id="bg-image">
        <option v-for="image in images" :key="image.id" :value="getFullImageUrl(image.file_path)">
          {{ image.file_path }}
        </option>
      </select>
    </div>
  </template>
  
  <script>
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000'; // Certifique-se de que esta linha está no topo do arquivo

export default {
  data() {
    return {
      selectedFile: null,
      images: [],
      selectedImage: null,
    };
  },
  methods: {
    handleFileChange(event) {
      this.selectedFile = event.target.files[0];
    },
    async uploadImage() {
      const formData = new FormData();
      formData.append('image', this.selectedFile);

      try {
        const response = await axios.post('/images/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        this.images = response.data.images; // Atualiza a lista de imagens com a resposta do servidor
      } catch (error) {
        console.error(error.response.data);
        alert('Error uploading image');
      }
    },
    async fetchImages() {
      try {
        const response = await axios.get('/images/list');
        this.images = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    getFullImageUrl(filePath) {
      // Substitua 'http://seu-site.com' pela URL base do seu site
      return `http://127.0.0.1:8000/storage/${filePath}`;
    }
  },
  mounted() {
    this.fetchImages(); // Carrega as imagens disponíveis ao montar o componente
  },
};


  </script>
  