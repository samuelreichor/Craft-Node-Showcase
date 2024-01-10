const ContentBuilderBlocks = `
  fragment ContentBuilderBlocks on contentBuilder_MatrixField {
    ... on contentBuilder_text_BlockType {
      type: typeHandle
      id
      text
    }
    
    ... on contentBuilder_image_BlockType {
      type: typeHandle
      id
      image {
        ... ImageLandscape
      }
    }
  }
`;
export default ContentBuilderBlocks;
