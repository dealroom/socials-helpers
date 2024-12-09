// Auto-synced file, managed by dealroom/core-mothership
// The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

module.exports = {
  // Overrides for specific file types
  overrides: [
    {
      files: ['*.js'],
      options: {
        trailingComma: 'none',
        semi: false
      }
    },
    {
      files: ['**/*.md'],
      options: {
        proseWrap: 'preserve'
      }
    },
    {
      files: ['**/*.yml', '**/*.yaml'],
      options: {
        // This doesn't work with the current version of prettier
        // @see: https://github.com/prettier/prettier/issues/16037
        quoteProps: 'as-needed'
      }
    }
  ]
}
