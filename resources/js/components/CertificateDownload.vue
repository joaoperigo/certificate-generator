<template>
  <button @click="downloadCertificate" class="border border-b-4 border-stone-300 hover:bg-stone-700 text-white font-bold pt-2 px-4 pb-3 rounded-xl">
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

        // Adicionar objetos (parágrafos, etc.)
        if (page.objects && Array.isArray(page.objects)) {
          page.objects.forEach(obj => {
            doc.setFont(obj.fontFamily || 'Helvetica');
            doc.setFontSize(obj.fontSize || 12);
            doc.setTextColor(obj.fontColor || '#000000');

            let xPos = obj.xPos / 3.779528; // Convertendo de px para mm
            let yPos = obj.yPos / 3.779528;
            let boxWidth = obj.boxWidth ? obj.boxWidth / 3.779528 : undefined;

            const lines = doc.splitTextToSize(obj.text, boxWidth || 100);
            doc.text(lines, xPos, yPos, {
              align: obj.textAlign || 'left',
              maxWidth: boxWidth
            });
          });
        }
      });

      doc.save(this.certificateData.title || "certificate.pdf");
    }
  }
}
</script>