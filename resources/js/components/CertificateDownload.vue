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
    async downloadCertificate() {
      if (!this.certificateData?.pages?.length) {
        console.error('Certificate data is not available')
        alert('Certificate data is not available for download.')
        return
      }

      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [303.02, 215.98]
      })

      // Adiciona todas as fontes ao PDF
      addFontsToPDF(doc)

      this.certificateData.pages.forEach((page, index) => {
        if (index > 0) {
          doc.addPage()
        }

        // Adiciona imagem de fundo
        if (page.backgroundImage) {
          doc.addImage(page.backgroundImage, 'JPEG', 0, 0, 303.02, 215.98)
        }

        // Adiciona objetos (textos)
        if (page.objects?.length) {
          page.objects.forEach(obj => {
            try {
              doc.setFont(obj.fontFamily || "helvetica", "normal")
              doc.setFontSize(obj.fontSize || 12)
              doc.setTextColor(obj.fontColor || "#000000")
              
              // Converte coordenadas de pixels para milímetros
              const xPos = obj.xPos / 3.779528
              const yPos = obj.yPos / 3.779528
              const boxWidth = obj.boxWidth ? obj.boxWidth / 3.779528 : undefined

              doc.text(obj.text, xPos, yPos, {
                align: obj.textAlign || 'left',
                maxWidth: boxWidth
              })
            } catch (error) {
              console.error('Error rendering text:', obj.text, error)
            }
          })
        }
      })

      doc.save(`${this.certificateData.title || 'certificate'}.pdf`)
    }
  }
}
</script>