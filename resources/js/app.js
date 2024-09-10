import './bootstrap';
import { createApp } from 'vue';
import ImageUploader from './components/ImageUploader.vue';

// Importa o Axios
import axios from 'axios';

// Configura o baseURL para todas as requisições Axios
axios.defaults.baseURL = 'http://127.0.0.1:8000';

const app = createApp({});

app.component('image-uploader', ImageUploader);
app.mount('#app');
