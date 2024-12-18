import './bootstrap';
import { createApp } from 'vue'
import CertificateCreator from './components/CertificateCreator.vue'
import PageSelector from './components/PageSelector.vue'
import ImageUploader from './components/ImageUploader.vue'
import CanvasEditor from './components/CanvasEditor.vue'
import AddParagraph from './components/AddParagraph.vue'
import EditParagraph from './components/EditParagraph.vue'
import ObjectList from './components/ObjectList.vue'
import CertificateDownload from './components/CertificateDownload.vue'
import CertificateViewer from './components/CertificateViewer.vue'
import CertificateSearch from './components/CertificateSearch.vue'
import CertificateList from './components/CertificateList.vue'
import TemplateSelector from './components/TemplateSelector.vue'
import QuickObjectCreator from './components/QuickObjectCreator.vue';

import axios from 'axios';

// Configurar o Axios para incluir o token CSRF em todas as requisições
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Se você estiver usando o Laravel Mix, você pode querer definir a URL base para o Axios
axios.defaults.baseURL = '/';

// Configura o baseURL para todas as requisições Axios
// axios.defaults.baseURL = 'http://127.0.0.1:8000';

const app = createApp({});

// Registrar componentes globalmente
app.component('certificate-creator', CertificateCreator)
app.component('page-selector', PageSelector)
app.component('image-uploader', ImageUploader)
app.component('canvas-editor', CanvasEditor)
app.component('add-paragraph', AddParagraph)
app.component('edit-paragraph', EditParagraph)
app.component('object-list', ObjectList)
app.component('certificate-download', CertificateDownload)
app.component('certificate-viewer', CertificateViewer)
app.component('certificate-search', CertificateSearch)
app.component('certificate-list', CertificateList)
app.component('template-selector', TemplateSelector)
app.component('quick-object-creator', QuickObjectCreator)

app.mount('#app');
