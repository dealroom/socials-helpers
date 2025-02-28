// Auto-synced file, managed by dealroom/core-mothership
// The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

module.exports = {
  arrowParens: "avoid",
  printWidth: 180,
  tabWidth: 2,
  useTabs: false,
  // Overrides for specific file types
  overrides: [
    {
      files: ["*.js", "*.jsx", "*.ts", "*.tsx"],
      options: {
        trailingComma: "none",
        semi: false,
        singleQuote: false,
      }
    },
    {
      files: ["**/*.md"],
      options: {
        proseWrap: "preserve"
      }
    }
  ]
};
