const breakpointSettings = require('./theme/breakpoints');
const colorSettings = require('./theme/colors');
const fontSettings = require('./theme/fonts');
const fontSizeSettings = require('./theme/fontSizes');

module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    colors: colorSettings,
    fontFamily: fontSettings,
    fontSize: fontSizeSettings,
    screens: breakpointSettings.pixel,
  },
  corePlugins: {
    aspectRatio: false,
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
}
