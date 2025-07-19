# GitHub Actions Guidelines

Essential patterns and conventions for GitHub Actions in this repository.

## File Structure Conventions

- **Action files**: Place in `.github/actions/[action-name]/action.yml`
- **Workflow files**: Place in `.github/workflows/workflow_name.yml`
- **Auto-sync header**: Always include this exact header in all `.github` files:
  ```yaml
  # Auto-synced file, managed by [dealroom/mothership](https://github.com/dealroom/mothership)
  # The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!
  ```

## Naming Conventions

- **Action directories**: Use `kebab-case` (e.g., `setup-terraform`, `github-app-token`)
- **Workflow files**: Use `snake_case.yml` (e.g., `reusable_terraform.yml`)
- **YAML inputs/outputs**: Use `kebab-case` (e.g., `target-environment`, `github-token`)
- **Step IDs**: Use `kebab-case` (e.g., `app-token`, `terraform-plan`)
- **Environment variables**: Use `UPPER_SNAKE_CASE` (e.g., `TFLINT_VERSION`, `DOCKER_COMPOSE_VERSION`)

## Action Structure Template

- **Use this exact structure** for all composite actions:
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

## Security Implementation

- **GitHub App Token pattern**: Always use this exact pattern for authentication:

  ```yaml
  - name: Get Application Token
    id: app-token
    uses: ./.github/actions/github-app-token
    with:
      application-id: ${{ secrets.DEALROOMBA_APP_ID }}
      application-private-key: ${{ secrets.DEALROOMBA_APP_PRIVATE_KEY }}
      permissions: metadata:read,contents:write,pull_requests:write
  ```

- **Permission patterns**: Use minimal required permissions:

  ```yaml
  permissions: read-all # Workflow level

  jobs:
    job-name:
      permissions:
        contents: read
        pull-requests: write
        issues: write
  ```

## Workflow Formatting Rules

- **Between job steps**: NO empty lines

  ```yaml
  steps:
    - name: First step
      run: echo "first"
    - name: Second step
      run: echo "second"
  ```

- **Between jobs**: Exactly 1 empty line

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

- **Concurrency pattern**: Use this exact pattern to prevent conflicts:
  ```yaml
  concurrency:
    group: ${{ github.workflow }}-${{ github.event.pull_request.number || github.ref }}
    cancel-in-progress: true
  ```

## Version Management

- **Action pinning**: Always pin to full SHA with version comment:

  ```yaml
  uses: actions/checkout@11bd71901bbe5b1630ceea73d27597364c9af683 # v4.2.2
  ```

- **Environment variables for versions**: Use renovate comments for automatic updates:

  ```yaml
  env:
    # renovate: datasource=github-releases depName=docker/compose
    DOCKER_COMPOSE_VERSION: v2.37.3
  ```

- **Conditional logic**: Use this format for complex conditions:
  ```yaml
  if: |
    github.event_name == 'pull_request'
    && contains(github.event.pull_request.labels.*.name, 'label-name')
  ```

## Input/Output Patterns

- **Shell script outputs**: Use this exact format:

  ```bash
  echo "result=value" >> "$GITHUB_OUTPUT"
  echo "multiline<<EOF" >> "$GITHUB_OUTPUT"
  echo "Line 1" >> "$GITHUB_OUTPUT"
  echo "EOF" >> "$GITHUB_OUTPUT"
  ```

- **Boolean handling**: Always use string comparisons:

  ```yaml
  if: ${{ inputs.enable-feature == 'true' }}
  ```

- **Required vs optional inputs**: Structure inputs like this:
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

## Essential Secrets Reference

- **Main GitHub App**: `DEALROOMBA_APP_ID` / `DEALROOMBA_APP_PRIVATE_KEY`
- **Approver App**: `DEALROOM_APPROVER_APP_ID` / `DEALROOM_APPROVER_PRIVATE_KEY`
- **Claude integration**: `ANTHROPIC_API_KEY`

## Caching Implementation

- **Use consistent cache keys** with this pattern:
  ```yaml
  - uses: actions/cache@v4
    with:
      path: ./.venv
      key: ${{ runner.os }}-poetry-${{ hashFiles('poetry.lock') }}
      restore-keys: ${{ runner.os }}-poetry-
  ```

## Available Custom Actions

- **Authentication**: `github-app-token`
- **Terraform**: `terraform-init-validate-plan`, `terraform-full-apply-setup`, `terraform-full-plan-setup`, `terraform-full-release-setup`
- **Environment setup**: `setup-python`, `setup-node`, `setup-go`, `setup-php`
- **Testing**: `test-python`, `test-npm`, `test-docker`, `test-go`, `test-php`
- **Helm**: `helm-deploy`, `helm-diff`, `helm-lint`, `helm-test`, `helm-rollback`
- **Container**: `container-build`, `container-push`
- **Notifications**: `slack-notification`
- **Releases**: `github-release`

## Critical antipatterns to Avoid

- **Never hardcode** secrets or tokens in workflow files
- **Never use relative paths** without proper context
- **Never omit shell type** in composite action steps
- **Never put complex Bash** directly in workflow YAML (extract to scripts)
- **Never pin actions** to moving tags (always use commit SHAs)

## Required Patterns to Follow

- **Always validate inputs** explicitly in composite actions
- **Always provide clear error messages** in failure scenarios
- **Always use conditional logic** for optional features
- **Always implement caching** for dependency installations
- **Always follow principle of least privilege** for permissions
