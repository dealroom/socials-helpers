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
    }
  ]
}
