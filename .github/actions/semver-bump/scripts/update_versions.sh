#!/usr/bin/env bash
set -eo pipefail

# Inputs from the action
FILES_INPUT="$1"
OLD_VERSION="$2"
NEW_VERSION="$3"
CURRENT_GIT_TAG="$4"
NEXT_GIT_TAG="$5"

# Validate inputs
if [[ -z "$OLD_VERSION" ]] || [[ -z "$NEW_VERSION" ]]; then
  echo "❌ ERROR: OLD_VERSION and NEW_VERSION must be provided"
  echo "Usage: $0 <files> <old_version> <new_version> <current_tag> <next_tag>"
  exit 1
fi

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

# 2. Check versions in files
FILE_ARRAY=()

# Enable extended globbing for the entire operation
shopt -s nullglob globstar extglob

# Handle the input as a single pattern that may contain commas in braces
# This properly handles patterns like "**/{main.go,README.md}" or "*.json,VERSION"
if [[ "$FILES_INPUT" == *"{"*"}"* ]]; then
  # Pattern contains braces, treat as single pattern
  declare -a expanded_files
  eval "expanded_files=($FILES_INPUT)"
  for file in "${expanded_files[@]}"; do
    if [[ -f "$file" ]]; then
      FILE_ARRAY+=("$file")
    fi
  done
else
  # No braces, safe to split by comma
  IFS=',' read -ra FILE_PATTERNS <<<"$FILES_INPUT"
  for pattern in "${FILE_PATTERNS[@]}"; do
    pattern=$(echo "$pattern" | xargs) # Trim whitespace
    for file in $pattern; do
      if [[ -f "$file" ]]; then
        FILE_ARRAY+=("$file")
      fi
    done
  done
fi

shopt -u nullglob globstar extglob

# If no files found, exit gracefully
if [[ ${#FILE_ARRAY[@]} -eq 0 ]]; then
  echo "⚠️  No files found matching patterns: $FILES_INPUT"
  echo "   Skipping version update."
  exit 0
fi

echo "  Found ${#FILE_ARRAY[@]} file(s) to check"

for file in "${FILE_ARRAY[@]}"; do
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
  fi
done

echo "✅ Version check passed."
echo ""

# 3. Update versions in files
echo "=== Updating Files ==="
for file in "${FILE_ARRAY[@]}"; do
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
  # For other files, replace only the OLD_VERSION with NEW_VERSION
  elif grep -qF "$OLD_VERSION" "$file"; then
    echo "  Replacing $OLD_VERSION with $NEW_VERSION in file"
    # Use perl for reliable version string replacement (handles dots correctly)
    perl -pe "s/\Q$OLD_VERSION\E/$NEW_VERSION/g" "$file" >"$TMP_FILE" && mv "$TMP_FILE" "$file"
  else
    echo "  No version string found to update in $file"
  fi
done

echo ""
echo "✅ All files updated successfully."
