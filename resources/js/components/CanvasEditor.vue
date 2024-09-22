<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
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
  watch: {
    currentPage() {
      this.renderPage()
    },
    pages: {
      deep: true,
      handler() {
        this.renderPage()
      }
    }
  },
  mounted() {
    this.renderPage()
  },
  methods: {
    renderPage() {
      const canvas = this.$refs.canvas
      const ctx = canvas.getContext('2d')
      
      // Verificar se há páginas antes de tentar renderizar
      if (this.pages.length === 0 || !this.pages[this.currentPage]) {
        ctx.clearRect(0, 0, canvas.width, canvas.height)
        ctx.fillStyle = 'gray'
        ctx.fillText('No page data available', 10, 50)
        return
      }

      const page = this.pages[this.currentPage]

      // Limpar o canvas
      ctx.clearRect(0, 0, canvas.width, canvas.height)

      // Renderizar o background
      if (page.backgroundImage) {
        const img = new Image()
        img.onload = () => {
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height)
          this.renderObjects(ctx, page.objects)
        }
        img.src = page.backgroundImage
      } else {
        this.renderObjects(ctx, page.objects)
      }
    },
    renderObjects(ctx, objects) {
      if (!objects) return
      objects.forEach(obj => {
        ctx.font = `${obj.fontSize}px ${obj.fontFamily}`
        ctx.fillStyle = obj.fontColor
        ctx.textAlign = obj.textAlign || 'left'
        ctx.fillText(obj.text, obj.xPos, obj.yPos)
      })
    }
  }
}
</script>