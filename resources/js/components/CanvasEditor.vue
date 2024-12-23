<!-- CanvasEditor.vue -->
<template>
  <div class="canvas-editor overflow-y p-4">
    <canvas class="w-full h-auto shadow-xl bg-stone-100" ref="canvas"></canvas>
  </div>
</template>

<script>
import { loadFonts, getFontWithFallback } from '@/services/fontService'

export default {
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    pages: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      ctx: null,
      canvasWidth: 303.02 * 3.779528,
      canvasHeight: 215.98 * 3.779528,
      fontsLoaded: false
    }
  },
  async mounted() {
    await this.initCanvas()
  },
  watch: {
    currentPage: {
      handler() {
        this.redrawCanvas()
      },
      immediate: true
    },
    pages: {
      deep: true,
      handler() {
        this.redrawCanvas()
      }
    }
  },
  methods: {
    async initCanvas() {
      const canvas = this.$refs.canvas
      canvas.width = this.canvasWidth
      canvas.height = this.canvasHeight
      this.ctx = canvas.getContext('2d')
      
      // Carrega as fontes antes de desenhar
      if (!this.fontsLoaded) {
        await loadFonts()
        this.fontsLoaded = true
      }
      
      this.redrawCanvas()
    },
    redrawCanvas() {
      if (!this.ctx || !this.fontsLoaded) return
      
      this.ctx.clearRect(0, 0, this.canvasWidth, this.canvasHeight)
      const currentPageData = this.pages[this.currentPage]
      
      if (currentPageData && currentPageData.backgroundImage) {
        this.drawBackgroundImage(currentPageData.backgroundImage)
      }
      
      if (currentPageData && currentPageData.objects) {
        this.drawObjects(currentPageData.objects)
      }
    },
    drawBackgroundImage(imageUrl) {
      const img = new Image()
      img.onload = () => {
        this.ctx.drawImage(img, 0, 0, this.canvasWidth, this.canvasHeight)
        this.drawObjects(this.pages[this.currentPage].objects)
      }
      img.src = imageUrl
    },
    drawObjects(objects) {
      if (!objects) return
      objects.forEach(obj => this.drawObject(obj))
    },
    drawObject(obj) {
      const ptToPx = (pt) => pt * 1.3333
      const fontSizePx = ptToPx(obj.fontSize)
      
      // Usa a função getFontWithFallback para garantir fallback apropriado
      this.ctx.font = `${fontSizePx}px ${getFontWithFallback(obj.fontFamily)}`
      this.ctx.fillStyle = obj.fontColor
      this.ctx.textAlign = obj.textAlign || 'left'

      const words = obj.text.split(' ')
      let line = ''
      let y = obj.yPos

      for (let word of words) {
        const testLine = line + word + ' '
        const metrics = this.ctx.measureText(testLine)
        const testWidth = metrics.width

        if (testWidth > obj.boxWidth && line !== '' && obj.boxWidth > 0) {
          this.drawAlignedText(line, obj.xPos, y, obj)
          line = word + ' '
          y += fontSizePx * 1.2
        } else {
          line = testLine
        }
      }
      this.drawAlignedText(line, obj.xPos, y, obj)
    },
    drawAlignedText(text, x, y, obj) {
      let adjustedX = x
      if (obj.textAlign === 'center') {
        adjustedX = x + obj.boxWidth / 2
      } else if (obj.textAlign === 'right') {
        adjustedX = x + obj.boxWidth
      }
      this.ctx.fillText(text, adjustedX, y)
    }
  }
}
</script>