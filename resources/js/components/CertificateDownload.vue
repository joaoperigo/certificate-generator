<template>
  <button @click="downloadCertificate" :class="buttonClasses">
    <PhDownload :size="40" class="mx-auto" />
    Download Certificate
  </button>
</template>

<script>
import { jsPDF } from "jspdf"
import { addFontsToPDF } from '@/services/fontService'
import { PhDownload } from "@phosphor-icons/vue"

export default {
  name: 'CertificateDownload',
  components: {
    PhDownload
  },
  props: {
    certificateData: {
      type: Object,
      required: true
    },
    buttonClasses: {  
      type: String,
      default: ''  
    }
  },
  methods: {
    // Em CertificateDownload.vue
async downloadCertificate() {
    const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [303.02, 215.98]
    });

    // Adiciona fontes ao PDF
    addFontsToPDF(doc);

    // Para cada página
    for (let i = 0; i < this.certificateData.pages.length; i++) {
        const page = this.certificateData.pages[i];
        if (i > 0) doc.addPage();

        // Se houver imagem de fundo, carrega ela
        if (page.backgroundImage) {
            try {
                // Faz o download da imagem
                const response = await fetch(page.backgroundImage);
                const blob = await response.blob();
                
                // Converte para base64
                const reader = new FileReader();
                const base64data = await new Promise(resolve => {
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(blob);
                });

                // Adiciona ao PDF
                doc.addImage(base64data, 'JPEG', 0, 0, 303.02, 215.98);
            } catch (error) {
                console.error('Error loading background image:', error);
            }
        }

        // Adiciona os objetos de texto
        if (page.objects?.length) {
            page.objects.forEach(obj => {
                try {
                    doc.setFont(obj.fontFamily || "helvetica", "normal");
                    doc.setFontSize(obj.fontSize || 12);
                    doc.setTextColor(obj.fontColor || "#333333");
                    
                    const xPos = obj.xPos;
                    const yPos = obj.yPos;
                    const boxWidth = obj.boxWidth ? obj.boxWidth : undefined;

                    doc.text(obj.text, xPos, yPos, {
                        align: obj.textAlign || 'left',
                        maxWidth: boxWidth,
                        charSpace: obj.letterSpacing
                    });
                } catch (error) {
                    console.error('Error rendering text:', obj.text, error);
                }
            });
        }
    }

    doc.save(`${this.certificateData.title || 'certificate'}.pdf`);
}
  }
}
</script>