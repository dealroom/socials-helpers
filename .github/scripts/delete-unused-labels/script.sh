#!/bin/bash

# Auto-synced file, managed by [dealroom/mothership](https://github.com/dealroom/mothership)
# The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

EXISTING_LABELS=$(gh label list -R "$REPO_NAME" --json name --jq '.[].name')
echo "Existing labels: $EXISTING_LABELS"

KEEP_LABELS=(
  "update-major"
  "update-minor"
  "update-patch"
  "dependencies"
  "low-risk"
  "high-risk"
  "risk-accepted"
  "sync"
  "force-lint"
  "auto-docs"
  "do-not-merge"
  "stale"
  "pre-release"
  "security"
  "preview"
)
IFS=$'\n' read -r -d '' -a label_array <<<"$EXISTING_LABELS"
for label in "${label_array[@]}"; do
  echo "Checking label: \"$label\""
  if [[ ! "${KEEP_LABELS[*]}" =~ $label ]]; then
    echo "!!! Deleting label: \"$label\""
    gh label delete "$label" -R "$REPO_NAME" --yes || true
  fi
done
