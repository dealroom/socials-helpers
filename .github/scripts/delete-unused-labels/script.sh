#!/bin/bash

LABELS=$(gh label list -R "$REPO_NAME" --json name --jq '.[].name')
echo "Existing labels: $LABELS"
WANT_LABELS=(
  "update-major"
  "update-minor"
  "update-patch"
  "update-digest"
  "dependencies"
  "force-lint"
  "low-risk"
  "sync"
  "force-lint"
  "auto-docs"
  "do-not-merge"
  "stale"
  "pre-release"
)
IFS=$'\n' read -r -d '' -a label_array <<<"$LABELS"
for label in "${label_array[@]}"; do
  echo "Checking label: \"$label\""
  if [[ ! "${WANT_LABELS[*]}" =~ $label ]]; then
    echo "!!! Deleting label: \"$label\""
    gh label delete "$label" -R "$REPO_NAME" --yes || true
  fi
done
