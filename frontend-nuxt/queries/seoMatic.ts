const seoMaticQuery = `
  query Seomatic($slug: String, $site: String) {
    seomatic (uri: $slug, site: $site, asArray: true) {
      metaTitleContainer
      metaTagContainer
      metaLinkContainer
      metaScriptContainer
      metaJsonLdContainer
    }
  }
  `;

export default seoMaticQuery;
