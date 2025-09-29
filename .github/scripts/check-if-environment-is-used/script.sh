#!/bin/bash

# Auto-synced file, managed by [dealroom/mothership](https://github.com/dealroom/mothership)
# The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

# Extract environments from helmfile.yaml.gotmpl
# This extracts only the environment names (keys) at the first indentation level under 'environments:'
HELMFILE_ENVIRONMENTS=$(awk '/^environments:/{flag=1;next}/^[^ ]/{flag=0}flag&&/^  [^ ]+:/{sub(/^  /,"");sub(/:.*$/,"");print}' helmfile.yaml.gotmpl | tr '\n' ' ')

# Always output the environments to GitHub output
if [ -n "$GITHUB_OUTPUT" ]; then
  echo "HELMFILE_ENVIRONMENTS=$HELMFILE_ENVIRONMENTS" >>"$GITHUB_OUTPUT"
fi

# If ENVIRONMENT is set, check if it's valid
if [ -n "$ENVIRONMENT" ]; then
  echo "$HELMFILE_ENVIRONMENTS"
  if echo "$HELMFILE_ENVIRONMENTS" | grep -wq "$ENVIRONMENT"; then
    echo "Environment '$ENVIRONMENT' is valid and exists in helmfile. Proceeding with the GitHub Action."
    echo "ENV_FOUND=true" >>"$GITHUB_OUTPUT"
  else
    echo "App doesn't run in the '$ENVIRONMENT' environment. Exiting gracefully."
    echo "ENV_FOUND=false" >>"$GITHUB_OUTPUT"
  fi
else
  # If no ENVIRONMENT specified, just list the environments
  echo "Available environments: $HELMFILE_ENVIRONMENTS"
fi
