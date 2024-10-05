import { ref } from 'vue';
import { useFetch } from '#app';
import type { Ref } from 'vue';

// Common query parameters shared by all element types, including allowed default methods
interface CommonQueryParams {
  id?: number;
  one?: string;
  all?: string;
  limit?: number;
  status?: string;
  offset?: number;
  orderBy?: string;
}

// Specific query parameters for each element type
interface AddressQueryParams extends CommonQueryParams {
  addressLine1?: string;
  addressLine2?: string;
  addressLine3?: string;
  locality?: string;
  organization?: string;
  fullName?: string;
}

interface AssetQueryParams extends CommonQueryParams {
  volume?: string;
  kind?: string;
  filename?: string;
}

interface EntryQueryParams extends CommonQueryParams {
  slug?: string;
  section?: string;
  postDate?: string;
}

interface UserQueryParams extends CommonQueryParams {
  group?: string;
  groupId?: string;
  authorOf?: string;
  email?: string;
  fullName?: string;
  hasPhoto?: boolean;
}

// Mapping from ElementType to its specific QueryParams
interface QueryParamsMap {
  addresses: AddressQueryParams;
  assets: AssetQueryParams;
  entries: EntryQueryParams;
  users: UserQueryParams;
}

// Definition of the return type of useFetch (adjust based on your actual API response)
interface FetchResult {
  data: Ref;
  error: Ref;
}

// Common query methods shared by all element types, including allowed default methods
interface CommonQueryBuilder {
  id: (id: number) => this;
  limit: (limit: number) => this;
  status: (status: string) => this;
  offset: (offset: number) => this;
  orderBy: (orderBy: string) => this;
  one: () => Promise<FetchResult>;
  all: () => Promise<FetchResult>;
}

// Element-specific query builder methods
interface AddressQueryBuilder extends CommonQueryBuilder {
  addressLine1: (value: string) => this;
  addressLine2: (value: string) => this;
  addressLine3: (value: string) => this;
  locality: (value: string) => this;
  organization: (value: string) => this;
  fullName: (value: string) => this;
}

interface AssetQueryBuilder extends CommonQueryBuilder {
  volume: (value: string) => this;
  kind: (value: string) => this;
  filename: (value: string) => this;
}

interface EntryQueryBuilder extends CommonQueryBuilder {
  slug: (value: string) => this;
  section: (value: string) => this;
  postDate: (value: string) => this;
}

interface UserQueryBuilder extends CommonQueryBuilder {
  group: (value: string) => this;
  groupId: (value: string) => this;
  authorOf: (value: string) => this;
  email: (value: string) => this;
  fullName: (value: string) => this;
  hasPhoto: (value: boolean) => this;
}

// Mapping from ElementType to its specific QueryBuilder
interface QueryBuilderMap {
  addresses: AddressQueryBuilder;
  assets: AssetQueryBuilder;
  entries: EntryQueryBuilder;
  users: UserQueryBuilder;
}

type ElementType = 'addresses' | 'assets' | 'entries' | 'users';

// Define function overloads for each ElementType
export function useQueryBuilder(elementType: 'addresses'): AddressQueryBuilder;
export function useQueryBuilder(elementType: 'assets'): AssetQueryBuilder;
export function useQueryBuilder(elementType: 'entries'): EntryQueryBuilder;
export function useQueryBuilder(elementType: 'users'): UserQueryBuilder;

// Generic implementation of the function
export function useQueryBuilder<T extends ElementType>(
  elementType: T,
): QueryBuilderMap[T] {
  // Use the specific QueryParams type based on T
  const params = ref<QueryParamsMap[T]>({} as QueryParamsMap[T]);

  params.value.elementType = elementType;

  const executeFetch = async (): Promise<FetchResult> => {
    const queryParams = Object.fromEntries(
      Object.entries(params.value)
        .filter(([_, value]) => value !== undefined)
        .map(([key, value]) => [key, String(value)]),
    );

    const queryString = new URLSearchParams(queryParams).toString();
    console.log(queryString);

    const { data, error } = await useFetch(
      `http://127.0.0.1:64317/v1/api/customQuery?${queryString}`,
    );

    return { data, error };
  };

  // Common methods shared by all element types, including allowed default methods
  const commonBuilder = {
    id(id: number) {
      params.value.id = id;
      return this;
    },
    limit(limit: number) {
      params.value.limit = limit;
      return this;
    },
    status(status: string) {
      params.value.status = status;
      return this;
    },
    offset(offset: number) {
      params.value.offset = offset;
      return this;
    },
    orderBy(orderBy: string) {
      params.value.orderBy = orderBy;
      return this;
    },
    async one() {
      params.value.one = '1';
      params.value.all = undefined;
      return executeFetch();
    },
    async all() {
      params.value.all = '1';
      params.value.one = undefined;
      return executeFetch();
    },
  };

  // Element-specific methods based on elementType
  if (elementType === 'addresses') {
    return {
      ...commonBuilder,
      addressLine1(value: string) {
        params.value.addressLine1 = value;
        return this;
      },
      addressLine2(value: string) {
        params.value.addressLine2 = value;
        return this;
      },
      addressLine3(value: string) {
        params.value.addressLine3 = value;
        return this;
      },
      locality(value: string) {
        params.value.locality = value;
        return this;
      },
      organization(value: string) {
        params.value.organization = value;
        return this;
      },
      fullName(value: string) {
        params.value.fullName = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'assets') {
    return {
      ...commonBuilder,
      volume(value: string) {
        params.value.volume = value;
        return this;
      },
      kind(value: string) {
        params.value.kind = value;
        return this;
      },
      filename(value: string) {
        params.value.filename = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'entries') {
    return {
      ...commonBuilder,
      slug(value: string) {
        params.value.slug = value;
        return this;
      },
      section(value: string) {
        params.value.section = value;
        return this;
      },
      postDate(value: string) {
        params.value.postDate = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'users') {
    return {
      ...commonBuilder,
      group(value: string) {
        params.value.group = value;
        return this;
      },
      groupId(value: string) {
        params.value.groupId = value;
        return this;
      },
      authorOf(value: string) {
        params.value.authorOf = value;
        return this;
      },
      email(value: string) {
        params.value.email = value;
        return this;
      },
      fullName(value: string) {
        params.value.fullName = value;
        return this;
      },
      hasPhoto(value: boolean) {
        params.value.hasPhoto = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  throw new Error(`Unsupported element type: ${elementType}`);
}
