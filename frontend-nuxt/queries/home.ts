import { ImageLandscape } from './fragments/image';
import ContentBuilderBlocks from './fragments/contentbuilder';

const homeQuery = `
  ${ImageLandscape}
  ${ContentBuilderBlocks}
  {
    entry: entry(section: "home") {
      ... on home_home_Entry {
        title
        heroHeadline
        heroImage {
          ... ImageLandscape
        }
        contentBuilder {
          ... ContentBuilderBlocks
        }
      }
    }
  }`;

export default homeQuery;
