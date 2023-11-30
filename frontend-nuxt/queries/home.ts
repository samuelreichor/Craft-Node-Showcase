const homeQuery = `{
    entry: entry(section: "home") {
      ... on home_home_Entry {
        heroHeadline
      }
    }
  }`;

export default homeQuery;
