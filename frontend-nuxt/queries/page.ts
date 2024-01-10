const pageQuery = `
query page($slug: [String]!) {
  entries(slug: $slug, section: "pages") {
    ... on pages_default_Entry {
      id
      title
    }
  }
}`;

export default pageQuery;
