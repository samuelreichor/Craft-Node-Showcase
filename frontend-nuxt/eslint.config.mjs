// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs';

export default withNuxt([
  {
    rules: {
      'no-underscore-dangle': ['error', { allow: ['__typename'] }],
      'no-undef': 'off',
      '@typescript-eslint/ban-ts-comment': [
        'error',
        {
          'ts-ignore': 'allow-with-description',
        },
      ],
      'vue/multi-word-component-names': 'off',
    },
  },
]).append({
  ignores: ['ferdi/**/*', 'schemaQuery.ts', '**/*.d.ts'],
});
