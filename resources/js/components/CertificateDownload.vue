<!-- CertificateDownload.vue -->
<template>
    <button @click="downloadCertificate" class="bg-stone-500 hover:bg-stone-700 text-white font-bold pt-2 px-4 pb-3 rounded-xl">
        <DocumentArrowDownIcon class="h-7 w-7 text-blue-100 mx-auto"/>
        Download Preview
    </button>
  </template>
  
  <script>

import { DocumentArrowDownIcon } from '@heroicons/vue/24/solid'

  import { jsPDF } from "jspdf";
  
  export default {
    components: {
        DocumentArrowDownIcon
    },
    name: 'CertificateDownload',
    props: {
      certificateData: {
        type: Object,
        required: true
      }
    },
    methods: {
      downloadCertificate() {
        if (!this.certificateData || !this.certificateData.pages) {
          console.error('Certificate data is not available');
          return;
        }
  
        const doc = new jsPDF({
          unit: 'mm',
          orientation: 'landscape',
          format: [303.02, 215.98]
        });
  
        this.certificateData.pages.forEach((page, index) => {
          if (index > 0) {
            doc.addPage();
          }
  
          // Adicionar imagem de fundo
          if (page.backgroundImage) {
            doc.addImage(page.backgroundImage, 'JPEG', 0, 0, 303.02, 215.98);
          }
  
          // Adicionar parágrafos
          if (page.objects && Array.isArray(page.objects)) {
            page.objects.forEach(obj => {
              doc.setFont(obj.fontFamily || 'Helvetica');
              doc.setFontSize(obj.fontSize || 12);
              doc.setTextColor(obj.fontColor || '#000000');
  
              const lines = doc.splitTextToSize(obj.text, obj.boxWidth || 100);
              doc.text(lines, obj.xPos/3.779528 || 0, obj.yPos/3.779528 || 0, {
                align: obj.textAlign || 'left',
                maxWidth: obj.boxWidth || 100
              });
            });
          }
        });
  
        doc.save(this.certificateData.title || "certificate.pdf");
      }
    }
  }
  </script>