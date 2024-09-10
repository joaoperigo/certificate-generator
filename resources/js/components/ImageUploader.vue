<template>
  <div>
    <label class="block text-gray-700 text-base font-bold mb-2" for="image-upload">Select image to upload</label>
    <!-- Input para selecionar arquivo -->
    <input class="pb-4" id="image-upload" type="file" @change="handleFileChange" />
    <!-- Botão para upload de imagem -->
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click.prevent="uploadImage">Upload Image</button>

    <!-- Lista de Imagens Disponíveis -->
    <label class="block text-gray-700 text-base font-bold mt-6 pb-4" for="bg-image">Select page's background image</label>
    <select v-model="selectedImage" id="bg-image" class="w-full mb-4">
      <option class="w-full" v-for="image in images" :key="image.id" :value="getFullImageUrl(image.file_path)">
        {{ getFullImageUrl(image.file_path) }}
      </option>
    </select>
  </div>
</template>

<script>
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000';

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
    async uploadImage(event) {
      event.preventDefault(); // Previne a propagação do evento
      if (!this.selectedFile) {
        alert('Please select a file first');
        return;
      }

      const formData = new FormData();
      formData.append('image', this.selectedFile);

      try {
        const response = await axios.post('/images/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        this.images = response.data.images;
        
        // Seleciona automaticamente a imagem recém-enviada
        if (this.images.length > 0) {
          const lastUploadedImage = this.images[this.images.length - 1];
          this.selectedImage = this.getFullImageUrl(lastUploadedImage.file_path);
        }

        // Limpa o input de arquivo
        this.selectedFile = null;
        // Se estiver usando ref para o input de arquivo, você pode limpar assim:
        // this.$refs.fileInput.value = '';

      } catch (error) {
        console.error(error.response ? error.response.data : error);
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
      return `http://127.0.0.1:8000/storage/${filePath}`;
    }
  },
  mounted() {
    this.fetchImages();
  },
};
</script>