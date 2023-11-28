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
  'xs': calcModularScale(-2),
  'sm': calcModularScale(-1),
  'base': calcModularScale(0),
  'lg': calcModularScale(1),
  'xl': calcModularScale(2),
  '2xl': calcModularScale(3),
  '3xl': calcModularScale(4),
  '4xl': calcModularScale(5),
  '5xl': calcModularScale(6),
  '6xl': calcModularScale(7),
  '7xl': calcModularScale(8),
  '8xl': calcModularScale(9),
  '9xl': calcModularScale(10),
};

