import { useQueryBuilder } from '~/composables/useCraftQueryBuilder';
import { beforeEach, afterEach, describe, it, expect, vi } from 'vitest';
import { ref } from 'vue';
import { useFetch } from '#app';

// Mocking useFetch from the app
vi.mock('#app', () => ({
  useFetch: vi.fn(),
}));

describe('useQueryBuilder Tests', () => {
  beforeEach(() => {
    useFetch.mockReturnValue({
      data: ref(null),
      error: ref(null),
    });
  });

  afterEach(() => {
    vi.clearAllMocks();
  });

  // 1. Tests for common and specific functions for each ElementType

  // Test for 'addresses'
  describe('useQueryBuilder - addresses', () => {
    it('should execute all commonBuilder functions for addresses', async () => {
      const queryBuilder = useQueryBuilder('addresses');

      // Call all commonBuilder functions
      await queryBuilder.id(1).limit(5).status('active').offset(2).orderBy('name').one();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=addresses&id=1&limit=5&status=active&offset=2&orderBy=name&one=1',
      );
    });

    it('should execute all address-specific functions', async () => {
      const queryBuilder = useQueryBuilder('addresses');

      // Call all address-specific functions
      await queryBuilder
        .addressLine1('123 Main St')
        .locality('Springfield')
        .fullName('John Doe')
        .one();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=addresses&addressLine1=123+Main+St&locality=Springfield&fullName=John+Doe&one=1',
      );
    });
  });

  // Test for 'assets'
  describe('useQueryBuilder - assets', () => {
    it('should execute all commonBuilder functions for assets', async () => {
      const queryBuilder = useQueryBuilder('assets');

      // Call all commonBuilder functions
      await queryBuilder
        .id(1)
        .limit(5)
        .status('active')
        .offset(2)
        .orderBy('name')
        .site('default')
        .siteId(1)
        .all();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=assets&id=1&limit=5&status=active&offset=2&orderBy=name&site=default&siteId=1&all=1',
      );
    });

    it('should execute all asset-specific functions', async () => {
      const queryBuilder = useQueryBuilder('assets');

      // Call all asset-specific functions
      await queryBuilder.volume('images').kind('png').filename('example.png').all();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=assets&volume=images&kind=png&filename=example.png&all=1',
      );
    });
  });

  // Test for 'entries'
  describe('useQueryBuilder - entries', () => {
    it('should execute all commonBuilder functions for entries', async () => {
      const queryBuilder = useQueryBuilder('entries');

      // Call all commonBuilder functions
      await queryBuilder.id(1).limit(5).status('active').offset(2).orderBy('name').one();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=entries&id=1&limit=5&status=active&offset=2&orderBy=name&one=1',
      );
    });

    it('should execute all entry-specific functions', async () => {
      const queryBuilder = useQueryBuilder('entries');

      // Call all entry-specific functions
      await queryBuilder
        .slug('my-slug')
        .uri(['news', '2023'])
        .section('news')
        .postDate('2023-01-01')
        .site('default')
        .siteId(1)
        .one();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=entries&slug=my-slug&uri=news%2F2023&section=news&postDate=2023-01-01&site=default&siteId=1&one=1',
      );
    });
  });

  // Test for 'users'
  describe('useQueryBuilder - users', () => {
    it('should execute all commonBuilder functions for users', async () => {
      const queryBuilder = useQueryBuilder('users');

      // Call all commonBuilder functions
      await queryBuilder.id(1).limit(5).status('active').offset(2).orderBy('name').all();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=users&id=1&limit=5&status=active&offset=2&orderBy=name&all=1',
      );
    });

    it('should execute all user-specific functions', async () => {
      const queryBuilder = useQueryBuilder('users');

      // Call all user-specific functions
      await queryBuilder.group('admins').email('admin@test.com').hasPhoto(true).all();

      // Ensure the correct query is built
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain(
        'elementType=users&group=admins&email=admin%40test.com&hasPhoto=true&all=1',
      );
    });
  });

  // 2. Edge Cases
  describe('useQueryBuilder - edge cases', () => {
    it('should handle null, empty strings, and undefined gracefully', async () => {
      const queryBuilder = useQueryBuilder('entries');

      // Call slug with different edge cases
      await queryBuilder.slug(null).one();
      await queryBuilder.slug('').one();
      await queryBuilder.slug(undefined).one();

      const [nullCalledUrl, emptyCalledUrl, undefinedCalledUrl] = useFetch.mock.calls;

      // Null value
      expect(nullCalledUrl[0]).toContain('elementType=entries&one=1'); // slug should not be in the query
      // Empty string
      expect(emptyCalledUrl[0]).toContain('elementType=entries&one=1'); // slug should not be in the query
      // Undefined value
      expect(undefinedCalledUrl[0]).toContain('elementType=entries&one=1'); // slug should not be in the query
    });

    it('should handle special characters in parameters correctly', async () => {
      const queryBuilder = useQueryBuilder('entries');

      // Call slug with spaces, special characters, and numbers passed as strings
      await queryBuilder.slug('my slug with spaces').one();
      await queryBuilder.slug('my_slug_with_special_chars_!@#$%^&*()').one();

      const [spacesUrl, specialCharsUrl] = useFetch.mock.calls;

      // Check spaces encoding
      expect(spacesUrl[0]).toContain(
        'elementType=entries&slug=my+slug+with+spaces&one=1',
      );
      // Check special characters encoding
      expect(specialCharsUrl[0]).toContain(
        'elementType=entries&slug=my_slug_with_special_chars_%21%40%23%24%25%5E%26*%28%29&one=1',
      );
    });

    it('should handle multiple values for the same parameter (arrays)', async () => {
      const queryBuilder = useQueryBuilder('entries');

      // Call uri with an array of values
      await queryBuilder.uri(['news', '2023', 'sports', '']).one();

      // Ensure the query string correctly joins the array
      const [calledUrl] = useFetch.mock.calls[0];
      expect(calledUrl).toContain('elementType=entries&uri=news%2F2023%2Fsports&one=1');
    });
  });

  // 3. Error Handling for invalid ElementType
  describe('useQueryBuilder - invalid element type', () => {
    it('should throw an error when an invalid elementType is provided', () => {
      expect(() => {
        useQueryBuilder('invalidType' as any); // Cast invalid element type
      }).toThrowError('Unsupported element type: invalidType');
    });
  });
});
