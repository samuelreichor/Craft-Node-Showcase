import { ref } from 'vue';
import { useFetch } from '#app';
import type { Ref } from 'vue';

type ElementType = 'addresses' | 'assets' | 'entries' | 'users';

// Common query parameters shared by all element types, including allowed default methods
interface CommonQueryParams {
  elementType?: ElementType;
  id?: number;
  one?: string;
  all?: string;
  limit?: number;
  status?: string;
  offset?: number;
  orderBy?: string;
}

// Specific query parameters for each element type
interface AddressQueryParams {
  addressLine1?: string;
  addressLine2?: string;
  addressLine3?: string;
  locality?: string;
  organization?: string;
  fullName?: string;
}

interface AssetQueryParams {
  volume?: string;
  kind?: string;
  filename?: string;
}

interface EntryQueryParams {
  slug?: string;
  uri?: string | string[];
  section?: string;
  postDate?: string;
}

interface UserQueryParams {
  group?: string;
  groupId?: string;
  authorOf?: string;
  email?: string;
  fullName?: string;
  hasPhoto?: boolean;
}

// Merge Queryparams for better dx
type MergedQueryParams = CommonQueryParams & AddressQueryParams & AssetQueryParams & EntryQueryParams & UserQueryParams;

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
  uri: (value: string | string[]) => this;
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

// Define function overloads for each ElementType
export function useQueryBuilder(elementType: 'addresses'): AddressQueryBuilder;
export function useQueryBuilder(elementType: 'assets'): AssetQueryBuilder;
export function useQueryBuilder(elementType: 'entries'): EntryQueryBuilder;
export function useQueryBuilder(elementType: 'users'): UserQueryBuilder;

// Generic implementation of the function
export function useQueryBuilder<T extends ElementType>(
  elementType: T,
): QueryBuilderMap[T] {

  const params = ref<MergedQueryParams>({});

  params.value.elementType = elementType;

  const executeFetch = async (): Promise<FetchResult> => {
    const queryParams = Object.fromEntries(
      Object.entries(params.value)
        .filter(([_, value]) => value !== undefined && value !== null && value != '')
        .map(([key, value]) => [key, String(value)]),
    );

    const queryString = new URLSearchParams(queryParams).toString();
    console.log(queryString);

    const route = useRoute();
    const previewToken = route.query.token;
    const { data, error } = await useFetch(
      `http://127.0.0.1:62603/v1/api/customQuery?${queryString}${previewToken ? '&token=' + previewToken : ''}`,
    );

    return { data, error };
  };

  // Common methods shared by all element types, including allowed default methods
  const commonBuilder = {
    id(id) {
      params.value.id = id;
      return this;
    },
    limit(limit) {
      params.value.limit = limit;
      return this;
    },
    status(status) {
      params.value.status = status;
      return this;
    },
    offset(offset) {
      params.value.offset = offset;
      return this;
    },
    orderBy(orderBy) {
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
  } as QueryBuilderMap[T];;

  // Element-specific methods based on elementType
  if (elementType === 'addresses') {
    return {
      ...commonBuilder,
      addressLine1(value) {
        params.value.addressLine1 = value;
        return this;
      },
      addressLine2(value) {
        params.value.addressLine2 = value;
        return this;
      },
      addressLine3(value) {
        params.value.addressLine3 = value;
        return this;
      },
      locality(value) {
        params.value.locality = value;
        return this;
      },
      organization(value) {
        params.value.organization = value;
        return this;
      },
      fullName(value) {
        params.value.fullName = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'assets') {
    return {
      ...commonBuilder,
      volume(value) {
        params.value.volume = value;
        return this;
      },
      kind(value) {
        params.value.kind = value;
        return this;
      },
      filename(value) {
        params.value.filename = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'entries') {
    return {
      ...commonBuilder,
      slug(value) {
        params.value.slug = value;
        return this;
      },
      uri(value) {
        params.value.uri = Array.isArray(value) ? value.filter(value => value !== '').join('/') : value;
        return this;
      },
      section(value) {
        params.value.section = value;
        return this;
      },
      postDate(value) {
        params.value.postDate = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  if (elementType === 'users') {
    return {
      ...commonBuilder,
      group(value) {
        params.value.group = value;
        return this;
      },
      groupId(value) {
        params.value.groupId = value;
        return this;
      },
      authorOf(value) {
        params.value.authorOf = value;
        return this;
      },
      email(value) {
        params.value.email = value;
        return this;
      },
      fullName(value) {
        params.value.fullName = value;
        return this;
      },
      hasPhoto(value) {
        params.value.hasPhoto = value;
        return this;
      },
    } as QueryBuilderMap[T];
  }

  throw new Error(`Unsupported element type: ${elementType}`);
}
