#!/usr/bin/env bash
set -eo pipefail

# Inputs from the action
FILES_INPUT="$1"
OLD_VERSION="$2"
NEW_VERSION="$3"
CURRENT_GIT_TAG="$4"

# Validate inputs
if [[ -z "$OLD_VERSION" ]] || [[ -z "$NEW_VERSION" ]]; then
  echo "❌ ERROR: OLD_VERSION and NEW_VERSION must be provided" >&2
  exit 1
fi

echo "=== Version Sync & Update ==="
echo "Current Git tag: $CURRENT_GIT_TAG"
echo "New version: $NEW_VERSION"
echo "Files: $FILES_INPUT"
echo ""

# Build file array from patterns (supports brace expansion and comma-separated)
shopt -s nullglob globstar extglob
FILE_ARRAY=()
if [[ "$FILES_INPUT" == *"{"*"}"* ]]; then
  # Brace expansion requires eval (FILES_INPUT comes from trusted workflow files)
  eval "for file in $FILES_INPUT; do [[ -f \"\$file\" ]] && FILE_ARRAY+=(\"\$file\"); done"
else
  # Comma-separated patterns
  IFS=',' read -ra patterns <<<"$FILES_INPUT"
  for pattern in "${patterns[@]}"; do
    pattern="${pattern#"${pattern%%[![:space:]]*}"}"
    pattern="${pattern%"${pattern##*[![:space:]]}"}"
    for file in $pattern; do
      [[ -f "$file" ]] && FILE_ARRAY+=("$file")
    done
  done
fi
shopt -u nullglob globstar extglob

if [[ ${#FILE_ARRAY[@]} -eq 0 ]]; then
  echo "⚠️  No files found, skipping."
  exit 0
fi

echo "Found ${#FILE_ARRAY[@]} file(s)"

# Regex to match semver, avoiding IPs like 127.0.0.1
VERSION_REGEX='(^|[^0-9.])[0-9]+\.[0-9]+\.[0-9]+([^0-9.]|$)'

# Extract version from file (JSON or text)
extract_version() {
  local file="$1"
  if [[ "$file" =~ \.json$ ]] && jq -e '.version' "$file" >/dev/null 2>&1; then
    jq -r '.version' "$file"
  elif grep -qE "$VERSION_REGEX" "$file" 2>/dev/null; then
    grep -oE "$VERSION_REGEX" "$file" | grep -oE '[0-9]+\.[0-9]+\.[0-9]+' | sort -V | tail -1
  fi
}

# Self-healing: if file version > current tag and no release exists, create it
CURRENT_VERSION="${CURRENT_GIT_TAG#v}"
for file in "${FILE_ARRAY[@]}"; do
  FILE_VERSION=$(extract_version "$file")
  FILE_VERSION="${FILE_VERSION#v}" # Strip v prefix if present
  if [[ -n "$FILE_VERSION" && -n "$CURRENT_VERSION" ]]; then
    HIGHER=$(printf "%s\n%s" "$FILE_VERSION" "$CURRENT_VERSION" | sort -V | tail -1)
    if [[ "$HIGHER" == "$FILE_VERSION" && "$FILE_VERSION" != "$CURRENT_VERSION" ]]; then
      FILE_TAG="v$FILE_VERSION"
      if ! gh release view "$FILE_TAG" >/dev/null 2>&1; then
        echo "⚠️  Self-healing: Creating missing release $FILE_TAG"
        gh release create "$FILE_TAG" --generate-notes --title "$FILE_TAG"
        echo "✅ Created release $FILE_TAG"
        # Update OLD_VERSION so file replacements work correctly
        OLD_VERSION="$FILE_VERSION"
      fi
    fi
  fi
done

# Safe file update: only replace if temp file is valid
safe_update() {
  local src="$1" dst="$2"
  if [[ -s "$src" ]]; then
    cat "$src" >"$dst" && rm "$src"
  else
    echo "  ⚠️  Update failed (empty output), keeping original"
    rm -f "$src"
    return 1
  fi
}

# Update versions in files
echo "=== Updating Files ==="
for file in "${FILE_ARRAY[@]}"; do
  echo "- $file"
  if [[ "$file" =~ \.json$ ]] && jq -e '.version' "$file" >/dev/null 2>&1; then
    if [[ "$(basename "$file")" == "package-lock.json" ]]; then
      jq --arg v "$NEW_VERSION" '(.version=$v)|(.packages[""].version=$v)' "$file" >"$file.tmp" && safe_update "$file.tmp" "$file"
    else
      jq --arg v "$NEW_VERSION" '.version=$v' "$file" >"$file.tmp" && safe_update "$file.tmp" "$file"
    fi
  elif grep -qF "$OLD_VERSION" "$file"; then
    perl -pe "s/\Q$OLD_VERSION\E/$NEW_VERSION/g" "$file" >"$file.tmp" && safe_update "$file.tmp" "$file"
  fi
done

echo "✅ Done."
