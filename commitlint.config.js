// Auto-synced file, managed by dealroom/core-mothership
// The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

module.exports = {
  // The configuration is based on the Conventional Commits specification (https://www.conventionalcommits.org)
  helpUrl: 'https://dealroom.slite.com/app/docs/q89vt4ocr_mZPC',
  parserPreset: {
    parserOpts: {
      headerPattern: /^(\w*)(?:\((.*)\))?!?: (.*)$/,
      breakingHeaderPattern: /^(\w*)(?:\((.*)\))?!: (.*)$/,
      headerCorrespondence: ['type', 'scope', 'subject'],
      noteKeywords: ['BREAKING CHANGE', 'BREAKING-CHANGE'],
      revertPattern:
        /^(?:Revert|revert:)\s"?([\s\S]+?)"?\s*This reverts commit (\w*)\./i,
      revertCorrespondence: ['header', 'hash']
    }
  },
  plugins: [
    {
      rules: {
        'jira-reference': commit => {
          // Allow commits without Jira reference using deliberate exceptions
          // Jira might not stay here forever, or we might switch to another system in the future
          // What is more important is that the commit message is clear and concise
          const allowed = ['DRP-', '(DEV)', '(auto)', '(deps)']
          const hasException = allowed.some(exception =>
            commit.raw.includes(exception)
          )
          if (!hasException) {
            return [
              false,
              'Jira ticket reference (DRP-1234) is missing. Example: "feat(DRP-1234): implement authentication". \n' +
                'Alternatively, use "DEV" scope to skip this check. Example: "chore(DEV): bump dependencies"'
            ]
          }
          return [true]
        }
      }
    }
  ],
  rules: {
    'jira-reference': [2, 'always'], // Enforce the presence of a Jira ticket reference
    'body-leading-blank': [1, 'always'], // Enforce a blank line between the subject and body
    'body-max-line-length': [2, 'always', 100], // Enforce the maximum line length for the body
    'footer-leading-blank': [1, 'always'], // Enforce a blank line between the body and footer
    'footer-max-line-length': [2, 'always', 100], // Enforce the maximum line length for the footer
    'header-max-length': [2, 'always', 100], // Enforce the maximum line length for the header
    'subject-case': [
      // Enforce the case of the subject
      2,
      'never',
      ['sentence-case', 'start-case', 'pascal-case', 'upper-case']
    ],
    'subject-empty': [2, 'never'], // Enforce the presence of the subject
    'subject-full-stop': [2, 'never', '.'], // Enforce the absence of a period at the end of the subject
    'type-case': [2, 'always', 'lower-case'], // Enforce the case of the type
    'type-empty': [2, 'never'], // Enforce the presence of the type
    'type-enum': [
      // Enforce the type to be one of the allowed values
      // @see https://github.com/conventional-changelog/commitlint/tree/master/%40commitlint/config-conventional#type-enum
      2,
      'always',
      [
        'build',
        'chore',
        'ci',
        'docs',
        'feat',
        'fix',
        'perf',
        'refactor',
        'revert',
        'style',
        'test'
      ]
    ]
  }
}
