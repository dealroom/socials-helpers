#!/bin/bash

HELMFILE_ENVIRONMENTS=$(awk '/^environments:/ {flag=1; next} /^[[:space:]]+[a-zA-Z0-9_-]+:/ && flag {print $1; next} /^[^[:space:]]/ {flag=0}' helmfile.yaml | sed 's/://')
echo "$HELMFILE_ENVIRONMENTS"
if echo "$HELMFILE_ENVIRONMENTS" | grep -wq "$ENVIRONMENT"; then
  echo "Environment '$ENVIRONMENT' is valid and exists in helmfile. Proceeding with the GitHub Action."
  echo "ENV_FOUND=true" >>"$GITHUB_OUTPUT"
else
  echo "App doesn't run in the '$ENVIRONMENT' environment. Exiting gracefully."
  echo "ENV_FOUND=false" >>"$GITHUB_OUTPUT"
fi
