<template>
  <button @click="downloadCertificate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Download Certificate
  </button>
</template>

<script>
import { jsPDF } from "jspdf";
import { mangueiraRegular } from '@/fonts/mangueiraRegular.js';
import { mangueiraMedium } from '@/fonts/mangueiraMedium.js';
import { myriadRegular } from '@/fonts/myriadRegular.js';

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
      if (!this.certificateData || !this.certificateData.pages || this.certificateData.pages.length === 0) {
        console.error('Certificate data is not available');
        alert('Certificate data is not available for download.');
        return;
      }

      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [215.9, 279.4] // Tamanho de uma folha A4 em paisagem
      });

      // Adicionar fontes personalizadas
      doc.addFileToVFS('Fontspring-DEMO-mangueiraalt-semibold-normal.ttf', mangueiraRegular);
      doc.addFont('Fontspring-DEMO-mangueiraalt-semibold-normal.ttf', 'Mangueira-Semibold', 'normal');

      doc.addFileToVFS('Mangueira-Medium-normal.ttf', mangueiraMedium);
      doc.addFont('Mangueira-Medium-normal.ttf', 'Mangueira-Medium', 'normal');

      doc.addFileToVFS('Myriad-Regular-normal.ttf', myriadRegular);
      doc.addFont('Myriad-Regular-normal.ttf', 'Myriad-Medium', 'normal');

      this.certificateData.pages.forEach((page, index) => {
        if (index > 0) {
          doc.addPage();
        }

        // Adicionar imagem de fundo
        if (page.backgroundImage) {
          doc.addImage(page.backgroundImage, 'JPEG', 0, 0, 279.4, 215.9);
        }

        // Adicionar objetos (textos)
        if (page.objects && Array.isArray(page.objects)) {
          page.objects.forEach(obj => {
            doc.setFont(obj.fontFamily || "helvetica", "normal");
            doc.setFontSize(obj.fontSize || 12);
            doc.setTextColor(obj.fontColor || "#000000");
            
            doc.text(obj.text, obj.xPos, obj.yPos, {
              align: obj.textAlign || 'left',
              maxWidth: obj.boxWidth
            });
          });
        }
      });

      doc.save(`${this.certificateData.title || 'certificate'}.pdf`);
    }
  }
}
</script>