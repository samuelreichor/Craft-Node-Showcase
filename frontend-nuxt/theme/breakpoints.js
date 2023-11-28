/*
 |--------------------------------------------------------------------------
 |  Breakpoint Settings (Tailwind)
 |--------------------------------------------------------------------------
 */

const breakPointMin = 320;
const breakPointSm = 600;
const breakPointMd = 800;
const breakPointLg = 1200;
const breakPointXl = 1600;

module.exports = {
    plain: {
        min: breakPointMin,
        sm: breakPointSm,
        md: breakPointMd,
        lg: breakPointLg,
        xl: breakPointXl,
    },
    pixel: {
        min: `${breakPointMin}px`,
        sm: `${breakPointSm}px`,
        md: `${breakPointMd}px`,
        lg: `${breakPointLg}px`,
        xl: `${breakPointXl}px`,
    },
};
