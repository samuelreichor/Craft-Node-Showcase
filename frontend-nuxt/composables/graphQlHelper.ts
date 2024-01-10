const getData = async (query, variables) => {
  const route = useRoute();
  const { token } = route.query;

  if (token) {
    const endpointUrl = useRuntimeConfig().public.gqlEndpoint;
    const { data, error, refresh } = await useFetch(endpointUrl, {
      params: { query, token },
    });
    await refresh();

    if (error.value) {
      throw error.value;
    }
    return data.value.data;
  }

  const queryCode = gql`
    ${query}
  `;

  const { onResult, onError, loading } = useQuery(queryCode, variables);

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
