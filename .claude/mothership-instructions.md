# User Memory - Coding Preferences & Tooling

## Commit Message Specification

### Format

All commits MUST follow this specification to pass the linter:

```text
<type>(<scope>): <subject>

[optional body]

[optional footer(s)]
```

**IMPORTANT: The entire commit message subject line MUST be lowercase!**

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
- **ALL LOWERCASE** - No uppercase letters anywhere in the subject
- No period at the end

4. **Body** (optional):

- MUST begin with one empty line after subject
- Max line length: 100 characters
- Provide context for the change

5. **Footer** (optional):

- MUST begin with one empty line after body
- Breaking changes: Start with `BREAKING CHANGE:`
- Max line length: 100 characters

6. **Keep commits simple**: Prefer commits without body - include all necessary information in the subject line. You MUST NOT add
   "Generated with" line or "Co-Authored-By" line.

7. **Use existing Jira tickets**: When creating new commits, reuse existing DRP-XXXX ticket numbers from:

   - Branch name
   - PR title
   - PR description
   - Existing commits in the PR
   - Commits between current branch and HEAD

   Priority order (highest to lowest):

   - PR title
   - Branch name
   - PR description
   - Most recent commit with DRP-XXXX ticket
   - Older commits with DRP-XXXX tickets

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

### Room command-line tool

The `room` CLI is our internal tool for managing code quality and linting. It's automatically installed in the Claude workflow.

#### Available Commands

```bash
# Discover and fix linting issues
room lint

# Auto-fix issues where possible
room lint --fix

# Run arbitrary commands inside the linting environment with correct linter versions
room lint exec {command}

# Examples of room lint exec usage
room lint exec eslint --fix src/
room lint exec prettier --write .
room lint exec terraform fmt -recursive
room lint exec golangci-lint run --fix
room lint exec black --check .
room lint exec flake8
```

**IMPORTANT**: Always use `room lint exec` when running linters to ensure:

- Correct linter versions are used (matching CI/CD)
- Proper configuration files are loaded
- Consistent results across different environments
- No version conflicts with locally installed tools

Never run linters directly (e.g., `eslint`, `prettier`, `golangci-lint`) outside the container as this may:

- Use different versions than what CI/CD expects
- Load different or missing configuration files
- Produce inconsistent results
- Cause false positives or miss real issues

#### The `room lint exec` Command

The `room lint exec` command allows you to run any command inside the super-linter container environment. This is crucial for:

1. **Running specific linters**: Execute individual linters with the exact versions used in CI/CD
2. **Custom lint commands**: Run linters with specific flags or on specific files
3. **Debugging linting issues**: Investigate why certain files fail linting
4. **Format code**: Use formatters like prettier, black, or terraform fmt

Usage syntax:

```bash
room lint exec [COMMAND...]
```

The command runs in the `/github/workspace` directory which is mapped to your project root.

#### Benefits

- **Consistent environment**: All linting runs in the same Docker environment with pinned tool versions
- **Autofixing**: Many linting issues can be automatically resolved
- **Comprehensive**: Covers multiple languages and file types
- **Isolated execution**: Commands run in controlled environment preventing version conflicts

#### Common Linting Categories

The tool handles these linting categories:

- EditorConfig
- GITHUB_ACTIONS
- JSON_PRETTIER
- Markdown
- NATURAL_LANGUAGE
- RENOVATE
- And more depending on project languages

### Docker and Docker Compose

Docker and Docker Compose are available in the Claude workflow environment and should be preferred for containerized operations.

#### Example Available Commands

```bash
# Docker commands
docker build -t myapp .
docker run --rm -it myapp
docker ps
docker images

# Docker Compose commands (v2 syntax - preferred)
docker compose up -d
docker compose down
docker compose logs
docker compose exec service-name bash
docker compose ps
```

#### When to Use Docker/Docker Compose

- **Preferred when**: `docker-compose.yml` or `Dockerfile` exists in the project
- **Development environments**: Spin up services for testing or development
- **Consistent environments**: Ensure the same environment across development and CI
- **Service dependencies**: When your application needs databases, caches, or other services
- **Containerized builds**: Building and testing Docker images

#### Best Practices

- Use `docker compose` (space, not hyphen) - modern v2 syntax
- Always use `--rm` flag for temporary containers to avoid cleanup issues
- Use `.dockerignore` to optimize build context
- Prefer official base images and pin specific versions

## Code Style Preferences

### General Principles

- Be concise, direct to the point and actionable in responses
- Always provide specific code solutions when requested
- Treat user as an expert - no high-level explanations unless requested
- Be terse and casual unless otherwise specified
- Suggest solutions that anticipate needs
- Give answers immediately, detailed explanations after if necessary

### Tooling Shortcuts

- Use `room lint` for automatic linting fixes
- Use `room lint exec {cmd}` for commands in linting environment
- Prefer Docker/Docker Compose when containerization files exist
- Use `docker compose` (v2 syntax) instead of `docker-compose`

## PR Requirements

- **Include "What has been done"** technical description section
- **Complete PR checklist** with style guidelines confirmation
- **Reference .github/PULL_REQUEST_TEMPLATE.md** and fill completely before creation
