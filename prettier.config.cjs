// Auto-synced file, managed by dealroom/mothership
// The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

module.exports = {
  arrowParens: "avoid",
  tabWidth: 2,
  useTabs: false,
  // Overrides for specific file types
  overrides: [
    {
      files: ["*.js", "*.jsx", "*.ts", "*.tsx"],
      options: {
        trailingComma: "none",
        semi: true,
        singleQuote: false,
      }
    },
    {
      files: ["**/*.md"],
      options: {
        proseWrap: "preserve"
      }
    },
    {
      files: ["composer.json", "composer.lock"],
      options: {
        tabWidth: 4,
      }
    }
  ]
};
