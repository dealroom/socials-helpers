#!/usr/bin/env bash
set -eo pipefail

# Inputs from the action
FILES_INPUT="$1"
OLD_VERSION="$2"
NEW_VERSION="$3"
CURRENT_GIT_TAG="$4"
NEXT_GIT_TAG="$5"

# --- Helper functions ---

# Function to compare semver versions (handles v prefix)
# Returns 0 if v1=v2, 1 if v1>v2, 2 if v1<v2
compare_versions() {
  local v1="${1#v}"
  local v2="${2#v}"

  # Handle empty strings to avoid errors
  if [[ -z "$v1" ]]; then
    [[ -z "$v2" ]] && echo 0 || echo 2
    return
  fi
  if [[ -z "$v2" ]]; then
    echo 1
    return
  fi

  if [[ "$v1" == "$v2" ]]; then
    echo 0
    return
  fi
  local sorted
  sorted=$(printf "%s\n%s" "$v1" "$v2" | sort -V)
  if [[ "$(head -n1 <<<"$sorted")" == "$v2" ]]; then
    echo 1
  else
    echo 2
  fi
}

# --- Main script ---

echo "=== Version Sync & Update ==="
echo "Current Git tag: $CURRENT_GIT_TAG"
echo "Next version: $NEXT_GIT_TAG"
echo "Old version string: $OLD_VERSION"
echo "New version string: $NEW_VERSION"
echo "Checking files: $FILES_INPUT"
echo ""

# 1. Check if release exists for current tag
if gh release view "$CURRENT_GIT_TAG" >/dev/null 2>&1; then
  echo "✅ Release exists for current tag $CURRENT_GIT_TAG"
else
  echo "⚠️  Warning: No release found for current tag $CURRENT_GIT_TAG"
  echo "   This might indicate a previous release creation failure."
fi

# 2. Check for higher versions in files before attempting to update
HIGHEST_FILE_VERSION=""
IFS=',' read -ra FILE_ARRAY <<<"$FILES_INPUT"
for file in "${FILE_ARRAY[@]}"; do
  file=$(echo "$file" | xargs) # Trim whitespace
  if [[ ! -f "$file" ]]; then
    continue
  fi

  FILE_VERSION=""
  # Extract version from JSON files with a '.version' key
  if [[ "$file" =~ \.json$ ]] && jq -e '.version' "$file" >/dev/null 2>&1; then
    FILE_VERSION=$(jq -r '.version' "$file")
  # Extract version from any file using a regex for semver
  elif grep -qE '[0-9]+\.[0-9]+\.[0-9]+' "$file"; then
    # This might find multiple versions, we'll check the highest one found in the file
    # and use that for comparison. This is a best-effort approach.
    FILE_VERSION=$(grep -oE '[0-9]+\.[0-9]+\.[0-9]+' "$file" | sort -V | tail -n1)
  fi

  if [[ -n "$FILE_VERSION" ]]; then
    echo "  Found version '$FILE_VERSION' in $file"
    if [[ $(compare_versions "$FILE_VERSION" "$HIGHEST_FILE_VERSION") -eq 1 ]]; then
      HIGHEST_FILE_VERSION="$FILE_VERSION"
    fi
  fi
done

if [[ -n "$HIGHEST_FILE_VERSION" ]]; then
  echo "  Highest version found in files: $HIGHEST_FILE_VERSION"
  echo "  Planned next version: ${NEXT_GIT_TAG#v}"
  if [[ $(compare_versions "$HIGHEST_FILE_VERSION" "${NEXT_GIT_TAG#v}") -eq 1 ]]; then
    echo ""
    echo "❌ ERROR: Files contain version $HIGHEST_FILE_VERSION which is higher than the planned next version $NEXT_GIT_TAG"
    echo ""
    echo "This can happen when:"
    echo "1. A release was created but the Git tag is missing"
    echo "2. Manual version bumps were made without creating releases"
    echo "3. The release workflow was interrupted"
    echo ""
    echo "To fix this, create the missing release(s), for example:"
    echo "  gh release create v$HIGHEST_FILE_VERSION --title \"Release v$HIGHEST_FILE_VERSION\" --notes \"Manual release to sync versions\""
    echo ""
    echo "After creating the release, this action will work normally again."
    exit 1
  fi
fi
echo "✅ Version check passed."
echo ""

# 3. Update versions in files
echo "=== Updating Files ==="
for file in "${FILE_ARRAY[@]}"; do
  file=$(echo "$file" | xargs)
  if [[ ! -f "$file" ]]; then
    echo "- Skipping non-existent file: $file"
    continue
  fi

  echo "- Processing $file"
  # Use a different temp file for each processed file to avoid race conditions
  TMP_FILE=$(mktemp)

  # For JSON files with a .version key, update it directly (self-healing)
  if [[ "$file" =~ \.json$ ]] && jq -e '.version' "$file" >/dev/null 2>&1; then
    echo "  Updating .version key in JSON file"
    # Special handling for package-lock.json to update both version fields at once
    if [[ "$(basename "$file")" == "package-lock.json" ]] && jq -e '.packages[""].version' "$file" >/dev/null 2>&1; then
      jq --arg version "$NEW_VERSION" '(.version = $version) | (.packages[""].version = $version)' "$file" >"$TMP_FILE" && mv "$TMP_FILE" "$file"
    else
      # For other JSON files, just update the root version field
      jq --arg version "$NEW_VERSION" '.version = $version' "$file" >"$TMP_FILE" && mv "$TMP_FILE" "$file"
    fi
  # For other files, do a robust string replacement of all version-like strings.
  # This is a "self-healing" approach that works even if the file is out of sync.
  elif grep -qE "[0-9]+\.[0-9]+\.[0-9]+" "$file"; then
    echo "  Replacing version-like strings in file"
    sed -E "s/[0-9]+\.[0-9]+\.[0-9]+/$NEW_VERSION/g" "$file" >"$TMP_FILE" && mv "$TMP_FILE" "$file"
  else
    echo "  No version string found to update in $file"
  fi
done

echo ""
echo "✅ All files updated successfully."
