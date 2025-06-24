# CLAUDE.md - GitHub Actions Guidelines

Essential patterns for GitHub Actions in this repository.

## File Structure

- Actions: `.github/actions/[action-name]/action.yml`
- Workflows: `.github/workflows/workflow_name.yml`
- Auto-sync header required:

```yaml
# Auto-synced file, managed by [dealroom/mothership](https://github.com/dealroom/mothership)
# The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!
```

## Naming Conventions

- Action directories: `kebab-case`
- Workflow files: `snake_case.yml`
- YAML inputs/outputs: `kebab-case`
- Step IDs: `kebab-case`
- Environment variables: `UPPER_SNAKE_CASE`

## Action Structure

```yaml
name: Action Name
description: Brief description
inputs:
  parameter-name:
    description: Parameter description
    required: true
    default: "default-value"
outputs:
  output-name:
    description: Output description
    value: ${{ steps.step-id.outputs.variable }}
runs:
  using: composite
  steps:
    - shell: bash
      run: |
        # Script content
```

## Security Patterns

### GitHub App Token

```yaml
- name: Get Application Token
  id: app-token
  uses: ./.github/actions/github-app-token
  with:
    application-id: ${{ secrets.DEALROOMBA_APP_ID }}
    application-private-key: ${{ secrets.DEALROOMBA_APP_PRIVATE_KEY }}
    permissions: metadata:read,contents:write,pull_requests:write
```

### Permissions

```yaml
permissions: read-all # Workflow level

jobs:
  job-name:
    permissions:
      contents: read
      pull-requests: write
      issues: write
```

## Workflow Patterns

### Spacing Rules

**Between job steps**: NO space

```yaml
steps:
  - name: First step
    run: echo "first"
  - name: Second step
    run: echo "second"
```

**Between individual jobs**: 1 space

```yaml
jobs:
  first-job:
    runs-on: ubuntu-latest
    steps:
      - name: Step
        run: echo "first job"

  second-job:
    runs-on: ubuntu-latest
    steps:
      - name: Step
        run: echo "second job"
```

### Concurrency

```yaml
concurrency:
  group: ${{ github.workflow }}-${{ github.event.pull_request.number || github.ref }}
  cancel-in-progress: true
```

### Version Pinning

```yaml
uses: actions/checkout@11bd71901bbe5b1630ceea73d27597364c9af683 # v4.2.2
```

### Conditional Logic

```yaml
if: |
  github.event_name == 'pull_request'
  && contains(github.event.pull_request.labels.*.name, 'label-name')
```

## Common Inputs/Outputs

### Shell Script Outputs

```bash
echo "result=value" >> "$GITHUB_OUTPUT"
echo "multiline<<EOF" >> "$GITHUB_OUTPUT"
echo "Line 1" >> "$GITHUB_OUTPUT"
echo "EOF" >> "$GITHUB_OUTPUT"
```

### Boolean Handling

```yaml
if: ${{ inputs.enable-feature == 'true' }}
```

## Essential Secrets

- `DEALROOMBA_APP_ID` / `DEALROOMBA_APP_PRIVATE_KEY` - Main GitHub App
- `DEALROOM_APPROVER_APP_ID` / `DEALROOM_APPROVER_PRIVATE_KEY` - Approver App
- `ANTHROPIC_API_KEY` - For Claude integrations

## Caching Patterns

### Consistent Cache Keys

```yaml
- uses: actions/cache@v4
  with:
    path: ./.venv
    key: ${{ runner.os }}-poetry-${{ hashFiles('poetry.lock') }}
    restore-keys: ${{ runner.os }}-poetry-
```

## Input Validation Patterns

### Required vs Optional Inputs

```yaml
inputs:
  required-input:
    description: "This input is required"
    required: true
  optional-input:
    description: "This input has a default"
    required: false
    default: "default-value"
```

### Boolean Input Handling in Reusable Workflows

Always use string comparisons for boolean inputs:

```yaml
- name: Conditional step
  if: ${{ inputs.enable-feature == 'true' }}
```

## Output Handling

### Action Outputs

```yaml
outputs:
  result:
    description: "Result of the operation"
    value: ${{ steps.main-step.outputs.result }}
```

### Step Outputs

```bash
# In shell script
echo "result=success" >> "$GITHUB_OUTPUT"
```

## Common Antipatterns to Avoid

### Don't

- Hardcode secrets or tokens
- Use relative paths without context
- Forget to specify shell type
- Use complex bash in workflow YAML (extract to scripts)
- Pin actions to moving tags (use commit SHAs)

### Do

- Use explicit input validation
- Provide clear error messages
- Use conditional logic for optional features
- Cache appropriately to improve performance
- Follow the principle of least privilege for permissions

## Commit Message Specification

### Format

All commits MUST follow this specification to pass the linter:

```text
<type>(<scope>): <subject>

[optional body]

[optional footer(s)]
```

### Rules

1. **Type**: MUST be one of the following (lowercase):

- `feat`: Adds a new feature
- `fix`: Fixes a bug
- `build`: Changes to build tool or dependencies
- `chore`: Maintenance tasks
- `ci`: Changes to CI configuration
- `docs`: Documentation changes
- `perf`: Performance improvements
- `refactor`: Code refactoring
- `revert`: Reverts a previous commit
- `style`: Code style changes (formatting, etc.)
- `test`: Adding or updating tests

2. **Scope**: MUST be provided in parentheses after the type:

- Use Jira ticket number: `(DRP-1234)`
- Use `(DEV)` for development work without a ticket
- Automatic dependency updates use `(deps)` or `(deps-dev)` (ignored by linter)

3. **Subject**: MUST immediately follow the scope:

- Short description (max 100 characters)
- Use imperative mood ("add" not "added")
- No uppercase first letter
- No period at the end

4. **Body** (optional):

- MUST begin with one empty line after subject
- Max line length: 100 characters
- Provide context for the change

5. **Footer** (optional):

- MUST begin with one empty line after body
- Breaking changes: Start with `BREAKING CHANGE:`
- Max line length: 100 characters

### Examples

```bash
# Good examples
feat(DRP-1234): add user authentication endpoint
fix(DRP-5678): resolve array parsing issue with multiple spaces
chore(DEV): update development dependencies

# Bad examples
feat: add authentication  # Missing scope
Fix(DRP-1234): resolve issue  # Type must be lowercase
feat(DRP-1234): Added new feature.  # Past tense and period
```

### Important Notes

- The linter (`commitlint`) will fail if these rules aren't followed
- Run `room lint` to check commit messages before pushing
- The configuration is in `commitlint.config.cjs`

## Linting and Code Quality

### Linting Commands

Use the `room lint` command to discover and fix linting concerns:

```bash
# Run linting to discover issues
room lint

# Auto-fix issues where possible
room lint --fix
```

Common linting categories: EditorConfig, GITHUB_ACTIONS, JSON_PRETTIER, Markdown, NATURAL_LANGUAGE, RENOVATE

## Custom Actions Available

Key reusable actions in `.github/actions/`:

- `github-app-token` - Generate GitHub App tokens
- `terraform-*` - Terraform operations (plan, apply, validate, etc.)
- `setup-*` - Environment setup (python, node, go, php, etc.)
- `test-*` - Testing frameworks (python, npm, docker, go, php)
- `helm-*` - Helm operations (deploy, diff, lint, test, rollback)
- `container-*` - Container build/push operations
- `slack-notification` - Send Slack notifications
- `github-release` - Create GitHub releases

## Repository Patterns

### Auto-sync Header

All `.github` files use this header - always include when creating new files:

```yaml
# Auto-synced file, managed by [dealroom/mothership](https://github.com/dealroom/mothership)
# The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!
```

### PR Template Structure

PRs should include:

- "What has been done" section (technical description)
- PR Checklist with style guidelines, documentation, and testing confirmation
- See .github/PULL_REQUEST_TEMPLATE.md and fill it in before create a new PR
