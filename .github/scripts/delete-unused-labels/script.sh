#!/bin/bash

EXISTING_LABELS=$(gh label list -R "$REPO_NAME" --json name --jq '.[].name')
echo "Existing labels: $EXISTING_LABELS"

KEEP_LABELS=(
  "update-major"
  "update-minor"
  "update-patch"
  "update-digest"
  "dependencies"
  "low-risk"
  "sync"
  "force-lint"
  "auto-docs"
  "do-not-merge"
  "stale"
  "pre-release"
  "security"
)
IFS=$'\n' read -r -d '' -a label_array <<<"$EXISTING_LABELS"
for label in "${label_array[@]}"; do
  echo "Checking label: \"$label\""
  if [[ ! "${KEEP_LABELS[*]}" =~ $label ]]; then
    echo "!!! Deleting label: \"$label\""
    gh label delete "$label" -R "$REPO_NAME" --yes || true
  fi
done
