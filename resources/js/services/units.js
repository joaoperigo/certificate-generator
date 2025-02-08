export const UNITS = {
    MM_TO_PX: 3.779528,  // conversão de mm para pixels
    PT_TO_PX: 1.3333,    // conversão de points para pixels
  }
  
  export function mmToPx(mm) {
    return mm * UNITS.MM_TO_PX;
  }
  
  export function ptToPx(pt) {
    return pt * UNITS.PT_TO_PX;
  }
  
  // Para casos onde precisamos converter de volta
  export function pxToMm(px) {
    return px / UNITS.MM_TO_PX;
  }
  
  export function pxToPt(px) {
    return px / UNITS.PT_TO_PX;
  }