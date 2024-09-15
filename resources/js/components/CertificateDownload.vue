<!-- CertificateDownload.vue -->
<template>
    <button @click="downloadCertificate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
      Download Certificate
    </button>
  </template>
  
  <script>
  import { jsPDF } from "jspdf";
  
  export default {
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
              doc.text(lines, obj.xPos || 0, obj.yPos || 0, {
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