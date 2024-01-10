type Handles = 'auto' | 'landscape' | 'square' | 'portrait';

const createImageFragment = (handle: Handles) => `
  fragment Image${
    handle.charAt(0).toUpperCase() + handle.slice(1)
  } on images_Asset {
    id
    title
    alt
    caption
    url
    filename
    mimeType
    width
    height
    focalPoint
    url
    srcset: url @imagerSrcset(handle: "${handle}")
  }
`;

export const ImageAuto = createImageFragment('auto');
export const ImageLandscape = createImageFragment('landscape');
export const ImageSquare = createImageFragment('square');
export const ImagePortrait = createImageFragment('portrait');
