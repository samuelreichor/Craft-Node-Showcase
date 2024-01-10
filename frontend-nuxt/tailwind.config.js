const breakpointSettings = require('./theme/breakpoints');
const colorSettings = require('./theme/colors');
const fontSettings = require('./theme/fonts');
const fontSizeSettings = require('./theme/fontSizes');
const zIndexSettings = require('./theme/zIndexes');
const designSystemSpacingSettings = require('./theme/designSystemSpacings');
const designSystemSpacingPlugin = require('./theme/plugins/designSystemSpacingPlugin');

module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    colors: colorSettings,
    fontFamily: fontSettings,
    fontSize: fontSizeSettings,
    screens: breakpointSettings.pixel,
    zIndex: zIndexSettings,
    designSystemSpacing: designSystemSpacingSettings,
  },
  plugins: [designSystemSpacingPlugin],
};
