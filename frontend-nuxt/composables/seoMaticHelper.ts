const seoMaticDataTransformer = (seoMaticData) => {
  if (!seoMaticData) {
    return {};
  }

  const seoData = { ...seoMaticData };

  // delete typename to prevent JSON.parse to fail
  delete seoData.__typename;

  const {
    metaTitleContainer,
    metaTagContainer,
    metaLinkContainer,
    metaScriptContainer,
    metaJsonLdContainer,
  } = Object.entries(seoData).reduce((previousValue, [key, value]) => {
    const acc = previousValue;
    acc[key] = JSON.parse(value);
    return acc;
  }, {});

  const meta = metaTagContainer
    ? Object.values(metaTagContainer).reduce((string, next) => {
        const nextCopy = { ...next };
        if (nextCopy.name === 'description') {
          nextCopy.hid = 'description';
        }
        return string.concat(nextCopy);
      }, [])
    : null;

  const link = metaLinkContainer
    ? Object.values(metaLinkContainer).reduce((string, next) => {
        return string.concat(next);
      }, [])
    : null;

  const metaScripts = metaScriptContainer
    ? Object.values(metaScriptContainer).map(({ script }) => {
        return {
          innerHTML: script,
        };
      })
    : [];

  const jsonLd = metaJsonLdContainer
    ? Object.entries(metaJsonLdContainer).map(([, value]) => {
        return {
          type: 'application/ld+json',
          innerHTML: JSON.stringify(value),
        };
      })
    : [];

  const script = [...metaScripts, ...jsonLd];
  return {
    title: metaTitleContainer.title?.title,
    meta,
    link,
    script,
  };
};

export default seoMaticDataTransformer;
