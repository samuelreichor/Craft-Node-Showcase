const navigationQuery = `
query navigation {
  navigationNodes(navHandle: "mainNavigation", level: 1) {
    title
    url
    children {
      title
      url
    }
  }
}
`;

export default navigationQuery;
