#!/bin/bash

LAST_TAG="$1"
SHORT_REF_NAME="$2"
DEFAULT_BRANCH="$3"
FULL_REF_NAME="$4"

if [[ "$LAST_TAG" != "" ]]; then
  # For beta releases, compare against last beta tag
  # For prod releases, compare against last prod tag (not beta)
  if [[ "$SHORT_REF_NAME" == *"beta"* ]]; then
    # Find previous beta tag (skip current one)
    BETA_COUNT=$(git tag | grep -c "beta")
    if [[ "$BETA_COUNT" -gt 1 ]]; then
      TAG=$(git tag --sort=-creatordate | grep "beta" | head -n 2 | tail -n 1)
      echo "BASE=$TAG" >>"$GITHUB_OUTPUT"
    else
      # First beta release - compare against initial commit
      INITIAL_COMMIT=$(git rev-list --max-parents=0 --reverse HEAD | head -n1)
      echo "BASE=$INITIAL_COMMIT" >>"$GITHUB_OUTPUT"
    fi
  else
    # For prod releases, find last prod tag (skip beta tags)
    PROD_COUNT=$(git tag | grep -vc "beta")
    if [[ "$PROD_COUNT" -gt 1 ]]; then
      TAG=$(git tag --sort=-creatordate | grep -v "beta" | head -n 2 | tail -n 1)
      echo "BASE=$TAG" >>"$GITHUB_OUTPUT"
    else
      # First prod release - compare against initial commit
      INITIAL_COMMIT=$(git rev-list --max-parents=0 --reverse HEAD | head -n1)
      echo "BASE=$INITIAL_COMMIT" >>"$GITHUB_OUTPUT"
    fi
  fi
  echo "REF=$SHORT_REF_NAME" >>"$GITHUB_OUTPUT"
else
  echo "BASE=$DEFAULT_BRANCH" >>"$GITHUB_OUTPUT"
  echo "REF=$FULL_REF_NAME" >>"$GITHUB_OUTPUT"
fi
