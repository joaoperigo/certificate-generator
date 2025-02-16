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
    },
    currentStudent: {
      type: Object,
      default: null
    }
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      const day = date.getDate();
      const year = date.getFullYear();
      
      const months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
      ];
      const month = months[date.getMonth()];
      
      return `${day} de ${month} de ${year}`;
    },

    replaceStudentData(text) {
      if (!this.currentStudent) return text;
      
      return text
        .replace(/\[name\]/g, this.currentStudent.name || '')
        .replace(/\{name\}/g, this.currentStudent.name || '')
        .replace(/\[cpf\]/g, this.currentStudent.cpf || '')
        .replace(/\{cpf\}/g, this.currentStudent.cpf || '')
        .replace(/\[document\]/g, this.currentStudent.document || '')
        .replace(/\{document\}/g, this.currentStudent.document || '')
        .replace(/\[code\]/g, this.currentStudent.code || '')
        .replace(/\{code\}/g, this.currentStudent.code || '')
        .replace(/\[unit\]/g, this.currentStudent.unit || '')
        .replace(/\{unit\}/g, this.currentStudent.unit || '')
        .replace(/\[start_date\]/g, this.formatDate(this.currentStudent.start_date) || '')
        .replace(/\{start_date\}/g, this.formatDate(this.currentStudent.start_date) || '')
        .replace(/\[end_date\]/g, this.formatDate(this.currentStudent.end_date) || '')
        .replace(/\{end_date\}/g, this.formatDate(this.currentStudent.end_date) || '');
    },

    async downloadCertificate() {
      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [303.02, 215.98]
      });

      // Add fonts to PDF
      addFontsToPDF(doc);

      // For each page
      for (let i = 0; i < this.certificateData.pages.length; i++) {
        const page = this.certificateData.pages[i];
        if (i > 0) doc.addPage();

        // If there's a background image, load it
        if (page.backgroundImage) {
          try {
            const response = await fetch(page.backgroundImage);
            const blob = await response.blob();
            
            const reader = new FileReader();
            const base64data = await new Promise(resolve => {
              reader.onloadend = () => resolve(reader.result);
              reader.readAsDataURL(blob);
            });

            doc.addImage(base64data, 'JPEG', 0, 0, 303.02, 215.98);
          } catch (error) {
            console.error('Error loading background image:', error);
          }
        }

        // Add text objects
        if (page.objects?.length) {
          page.objects.forEach(obj => {
            try {
              doc.setFont(obj.fontFamily || "helvetica", "normal");
              doc.setFontSize(obj.fontSize || 12);
              doc.setTextColor(obj.fontColor || "#333333");
              
              const xPos = obj.xPos;
              const yPos = obj.yPos;
              const boxWidth = obj.boxWidth ? obj.boxWidth : undefined;

              // Replace placeholders with student data
              const text = this.replaceStudentData(obj.text);

              doc.text(text, xPos, yPos, {
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

      const filename = this.currentStudent 
        ? `${this.certificateData.title}_${this.currentStudent.name}` 
        : this.certificateData.title;
      
      doc.save(`${filename}.pdf`);
    }
  }
}
</script>