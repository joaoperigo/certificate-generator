<!-- CanvasEditor.vue -->
<template>
  <div class="canvas-editor overflow-y p-4">
    <canvas class="w-full h-auto shadow-xl bg-stone-100" ref="canvas"></canvas>
  </div>
</template>

<script>
import { loadFonts, getFontWithFallback } from '@/services/fontService'
import { UNITS, mmToPx, pxToMm } from '@/services/units'

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
      canvasWidth: 303.02 * UNITS.MM_TO_PX,
      canvasHeight: 215.98 * UNITS.MM_TO_PX,
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
      const PT_TO_PX = 1.3333;
      const fontSizePx = obj.fontSize * PT_TO_PX;
      
      this.ctx.font = `${fontSizePx}px ${getFontWithFallback(obj.fontFamily)}`;
      this.ctx.fillStyle = obj.fontColor;
      this.ctx.textAlign = obj.textAlign || 'left';

      // Primeiro, divide o texto em linhas usando \n
      const paragraphs = obj.text.split('\n');
      let y = obj.yPos;

      for (let paragraph of paragraphs) {
        if (paragraph === '') {
          // Para linhas vazias, apenas aumenta o y
          y += fontSizePx * 1.2;
          continue;
        }

        const words = paragraph.split(' ');
        let line = '';

        for (let word of words) {
          const testLine = line + word + ' ';
          const metrics = this.ctx.measureText(testLine);
          const testWidth = metrics.width;

          if (testWidth > obj.boxWidth && line !== '' && obj.boxWidth > 0) {
            this.drawAlignedText(line.trim(), obj.xPos, y, obj);
            line = word + ' ';
            y += fontSizePx * 1.2;
          } else {
            line = testLine;
          }
        }

        // Desenha a última linha do parágrafo
        if (line.trim()) {
          this.drawAlignedText(line.trim(), obj.xPos, y, obj);
        }
        
        // Adiciona espaço extra entre parágrafos
        y += fontSizePx * 1.2;
      }
    },

    drawAlignedText(text, x, y, obj) {
      let adjustedX = x;
      const textWidth = this.ctx.measureText(text).width;

      if (!obj.boxWidth) {
        // Sem boxWidth: usa o textAlign nativo do canvas
        this.ctx.textAlign = obj.textAlign || 'left';
        adjustedX = x;
      } else {
        // Com boxWidth: usa a lógica anterior que funcionava
        this.ctx.textAlign = 'left';  // Sempre left quando tem box
        if (obj.textAlign === 'center') {
          adjustedX = x + (obj.boxWidth / 2);
        } else if (obj.textAlign === 'right') {
          adjustedX = x + obj.boxWidth;
        }
      }

      // Debug
      console.log({
        text,
        alignment: obj.textAlign,
        hasBoxWidth: !!obj.boxWidth,
        boxWidth: obj.boxWidth,
        originalX: x,
        adjustedX: adjustedX,
        textWidth
      });

      this.ctx.fillText(text, adjustedX, y);
    }
  }
}
</script>