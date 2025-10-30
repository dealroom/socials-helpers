# DEPRECATED: GitHub App Token (v1)

⚠️ **This action is deprecated. Please migrate to `github-app-token-v2`.**

## Why Migrate?

The v1 action uses `peter-murray/workflow-application-token-action`, a third-party action.

We are migrating to the official GitHub Action (`actions/create-github-app-token`) for:

- Better maintenance and security
- Official GitHub support
- Improved IDE support with type validation
- Automatic token revocation

## Migration Guide

### Before (v1 - this action)

```yaml
- id: app-token
  uses: ./.github/actions/github-app-token
  with:
    application-id: ${{ secrets.DEALROOMBA_APP_ID }}
    application-private-key: ${{ secrets.DEALROOMBA_APP_PRIVATE_KEY }}
    permissions: metadata:read,packages:read,contents:read
```

### After (v2 - preferred)

```yaml
- id: app-token
  uses: ./.github/actions/github-app-token-v2
  with:
    application-id: ${{ secrets.DEALROOMBA_APP_ID }}
    application-private-key: ${{ secrets.DEALROOMBA_APP_PRIVATE_KEY }}
    permission-metadata: read
    permission-packages: read
    permission-contents: read
```

## Key Changes

1. **Action path**: `./.github/actions/github-app-token` → `./.github/actions/github-app-token-v2`
2. **Permissions format**: Comma-separated string → Individual inputs
3. **Permission naming**: `metadata:read` → `permission-metadata: read`

## Output Format

The output remains the same:

```yaml
steps.<id>.outputs.token
```

## See Also

- [github-app-token-v2 action](../github-app-token-v2/action.yml)
- [Official GitHub Action](https://github.com/actions/create-github-app-token)
