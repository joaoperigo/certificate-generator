// fontService.js

// Importa base64 para PDF
import { mangueiraRegular } from '@/fonts/mangueiraRegular.js'
import { mangueiraMedium } from '@/fonts/mangueiraMedium.js'
import { mangueiraSemiBold } from '@/fonts/mangueiraSemiBold.js'

// URLs para arquivos TTF
// const FONT_URLS = {
//   'Mangueira-Regular': '/fonts/mangueira-regular.ttf',
//   'Mangueira-Medium': '/fonts/mangueira-medium.ttf',
//   'Mangueira-SemiBold': '/fonts/mangueira-semibold.ttf'
// }

// // Adjust base URL based on environment
// const baseUrl = process.env.NODE_ENV === 'production' 
//   ? 'https://phplaravel-586388-5271634.cloudwaysapps.com'
//   : '';

// // URLs para arquivos TTF
// const FONT_URLS = {
//   'Mangueira-Regular': `${baseUrl}/fonts/mangueira-regular.ttf`,
//   'Mangueira-Medium': `${baseUrl}/fonts/mangueira-medium.ttf`,
//   'Mangueira-SemiBold': `${baseUrl}/fonts/mangueira-semibold.ttf`
// }

const FONT_URLS = {
  'Mangueira-Regular': window.location.origin + '/fonts/mangueira-regular.ttf',
  'Mangueira-Medium': window.location.origin + '/fonts/mangueira-medium.ttf',
  'Mangueira-SemiBold': window.location.origin + '/fonts/mangueira-semibold.ttf'
}

// Configuração das fontes
export const FONTS = {
  'Mangueira-Regular': {
    pdfData: mangueiraRegular,
    pdfFilename: 'mangueira-regular.ttf',
    url: FONT_URLS['Mangueira-Regular'],
    fallback: 'Arial'
  },
  'Mangueira-Medium': {
    pdfData: mangueiraMedium,
    pdfFilename: 'mangueira-medium.ttf',
    url: FONT_URLS['Mangueira-Medium'],
    fallback: 'Arial'
  },
  'Mangueira-SemiBold': {
    pdfData: mangueiraSemiBold,
    pdfFilename: 'mangueira-semibold.ttf',
    url: FONT_URLS['Mangueira-SemiBold'],
    fallback: 'Arial'
  }
}

// Adiciona @font-face ao CSS
const fontFaceCSS = Object.entries(FONTS).map(([family, config]) => `
  @font-face {
    font-family: '${family}';
    src: url('${config.url}') format('truetype');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }
`).join('\n')

const style = document.createElement('style')
style.textContent = fontFaceCSS
document.head.appendChild(style)

let fontsLoaded = false

export async function loadFonts() {
  if (fontsLoaded) return
  
  const fontPromises = Object.entries(FONTS).map(([family, config]) => {
    const fontFace = new FontFace(family, `url(${config.url})`)
    return fontFace.load().then(font => {
      document.fonts.add(font)
      return font
    })
  })

  try {
    await Promise.all(fontPromises)
    fontsLoaded = true
    console.log('Todas as fontes foram carregadas com sucesso')
  } catch (error) {
    console.error('Erro ao carregar fontes:', error)
  }
}

export function getFontWithFallback(fontFamily) {
  return `${fontFamily}, ${FONTS[fontFamily]?.fallback || 'Arial'}`
}

export function addFontsToPDF(doc) {
  Object.entries(FONTS).forEach(([family, config]) => {
    doc.addFileToVFS(config.pdfFilename, config.pdfData)
    doc.addFont(config.pdfFilename, family, 'normal')
  })
}