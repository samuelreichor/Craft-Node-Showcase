/*
 |--------------------------------------------------------------------------
 |  Font Size Settings (Tailwind)
 |--------------------------------------------------------------------------
 */

const breakpoints = require('./breakpoints').plain;

const mobile = {
  fontSize: 16,
  ratio: 1.1,
  breakpoint: breakpoints.sm,
};

const desktop = {
  fontSize: 18,
  ratio: 1.2,
  breakpoint: breakpoints.lg,
};

const calcModularScale = (scale = 0) => {
  const minFontSize = mobile.fontSize * Math.pow(mobile.ratio, scale);
  const maxFontSize = desktop.fontSize * Math.pow(desktop.ratio, scale);
  return `clamp(${minFontSize}px, calc(${minFontSize}px + (${maxFontSize} - ${minFontSize}) * ((100vw - ${mobile.breakpoint}px) / (${desktop.breakpoint} - ${mobile.breakpoint}))), ${maxFontSize}px)`;
};

module.exports = {
  xs: [calcModularScale(-2), 1.4],
  sm: [calcModularScale(-1), 1.4],
  base: [calcModularScale(0), 1.5],
  lg: [calcModularScale(1), 1.5],
  xl: [calcModularScale(2), 1.35],
  '2xl': [calcModularScale(3), 1.25],
  '3xl': [calcModularScale(4), 1.25],
  '4xl': [calcModularScale(5), 1.25],
  '5xl': [calcModularScale(6), 1.25],
  '6xl': [calcModularScale(7), 1],
  '7xl': [calcModularScale(8), 1.1],
  '8xl': [calcModularScale(9), 1.175],
  '9xl': [calcModularScale(10), 1],
};
