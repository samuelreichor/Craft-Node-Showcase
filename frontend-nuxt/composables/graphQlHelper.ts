const getData = async (query, variables, token) => {
  if (token) {
    const { data, error, refresh } = await useFetch('https://template-headless.ddev.site/gql', {
      params: { query, token },
    });
    await refresh();

    if (error.value) {
      throw error.value;
    }
    return data.value.data;
  }

  const { onResult, onError, loading } = useQuery(
    gql`
      ${query}
    `,
    variables || {}
  );

  return new Promise((resolve, reject) => {
    onResult((resultData) => {
      if (!loading.value) {
        resolve(resultData.data);
      }
    });

    onError((err) => {
      reject(err);
    });
  });
};

export default getData;

/* Test */
