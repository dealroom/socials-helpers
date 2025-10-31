#!/bin/bash

set -e

echo "Checking action pinning: Valid ONLY IF SHA-pinned AND has a full semver comment (e.g., # vX.Y.Z)..."

# Main parsing regex for 'uses: owner/repo@pin #comment'
# Group 1: Optional leading dash part (e.g., "- ")
# Group 2: Action name (e.g., "actions/checkout")
# Group 3: Pin value (e.g., "abcdef0" or "v1.2.3") - Cannot contain spaces or #
# Group 4: Optional full comment part, including optional leading spaces and the comment itself starting with # (e.g., " # v1.2.3" or "#v1.2.3")
# Group 5: Optional nested comment part, starting with # (e.g., "# v1.2.3")
RE_MAIN='^[[:space:]]*(-[[:space:]]*)?uses:[[:space:]]*([^@#[:space:]]+)@([^[:space:]#]+)([[:space:]]*(#.*))?$'

# Find all lines with "uses: action@target" structure.
raw_findings=$(grep -r -H -n --include='*.yml' --include='*.yaml' -P 'uses:[[:space:]]*[^[:space:]#@]+@[^[:space:]#]+' ./.github || true)

if [[ -z "$raw_findings" ]]; then
  echo "No 'uses: owner/repo@target' lines found to check by the initial grep."
  exit 0
fi

final_lines_to_report=()
declare -A exclusions_map
if [[ -n "$INPUT_EXCLUDED_ACTIONS" ]]; then
  IFS=',' read -ra exclusions_array <<<"$INPUT_EXCLUDED_ACTIONS"
  for exclusion_item in "${exclusions_array[@]}"; do
    trimmed_exclusion_item=$(echo "$exclusion_item" | xargs)
    if [[ -n "$trimmed_exclusion_item" ]]; then
      exclusions_map["$trimmed_exclusion_item"]=1
    fi
  done
fi

while IFS= read -r grep_line; do
  [[ -n "$grep_line" ]] || continue

  filepath=""
  lineno=""
  actual_line_content=""

  # Parse the grep output line: FILENAME:LINE_NUMBER:MATCHED_LINE_TEXT
  if [[ "$grep_line" =~ ^([^:]+):([0-9]+):(.*)$ ]]; then
    filepath="${BASH_REMATCH[1]}"
    lineno="${BASH_REMATCH[2]}"
    actual_line_content="${BASH_REMATCH[3]}"
  else
    echo "Warning: Could not parse grep output line format: $grep_line" >&2
    final_lines_to_report+=("$grep_line (Error: Internal script error, could not parse grep output line)")
    continue
  fi

  # Skip if the 'uses:' line is itself commented out
  # Remove leading whitespace for this check
  trimmed_for_comment_check="${actual_line_content#"${actual_line_content%%[![:space:]]*}"}"
  if [[ "$trimmed_for_comment_check" == '#'* ]]; then
    # Optional: echo an info message if you want to log these skips
    # echo "Info: Skipping fully commented-out uses-line: $filepath:$lineno"
    continue
  fi

  action_name=""
  pin_value=""
  comment_content_raw=""
  main_re_match_group5="" # To store BASH_REMATCH[5] from main RE

  # Regex to parse $actual_line_content:
  # Example: "    - uses: actions/checkout@abcdef0 # v1.2.3"
  # BASH_REMATCH[1]: Optional leading spaces/dash (e.g., "  - ")
  # BASH_REMATCH[2]: Action name (e.g., "actions/checkout")
  # BASH_REMATCH[3]: Pin value (e.g., "abcdef0" or "v1.2.3")
  # BASH_REMATCH[4]: Optional full comment part (e.g., " # v1.2.3")
  # BASH_REMATCH[5]: Optional comment starting with # (e.g., "# v1.2.3")
  if [[ "$actual_line_content" =~ $RE_MAIN ]]; then
    action_name=$(echo "${BASH_REMATCH[2]}" | xargs)
    pin_value=$(echo "${BASH_REMATCH[3]}" | xargs)
    main_re_match_group5="${BASH_REMATCH[5]}" # Store group 5 (comment starting with #)

    if [[ -n "$main_re_match_group5" ]]; then
      # main_re_match_group5 is like "# v1.2.3" or "#v1.2.3"
      # Remove leading '#'
      comment_content_raw="${main_re_match_group5#\#}"
    else
      comment_content_raw="" # No comment starting with # found
    fi
  else
    final_lines_to_report+=("$filepath:$lineno:$actual_line_content (Error: Could not parse 'uses: owner/repo@pin #comment' structure from: '$actual_line_content')")
    continue
  fi

  if [[ -n "$action_name" && -n "${exclusions_map[$action_name]}" ]]; then
    echo "Info: Action '$action_name' in line '$filepath:$lineno' is excluded."
    continue
  fi

  is_sha_pinned_check=false
  if [[ "$pin_value" =~ ^[a-fA-F0-9]{7,40}$ ]]; then
    is_sha_pinned_check=true
  fi

  has_full_semver_comment_check=false
  comment_text_for_reporting="(no comment)"
  comment_delimiter_present=false

  # Check if a comment delimiter # was part of the original actual_line_content
  # This is determined if main_re_match_group5 (e.g., "# v1.2.3") was non-empty
  if [[ -n "$main_re_match_group5" ]]; then
    comment_delimiter_present=true
    comment_content_trimmed=$(echo "$comment_content_raw" | xargs) # comment_content_raw is already without the leading #
    comment_text_for_reporting="'$comment_content_trimmed'"
    # Regex for full semver: vX.Y.Z or X.Y.Z, allowing leading/trailing non-alphanumeric to avoid matching parts of words
    if [[ "$comment_content_trimmed" =~ ^v?[0-9]+\.[0-9]+\.[0-9]+([[:space:]]+.*)?$ ]]; then
      has_full_semver_comment_check=true
    fi
  fi

  is_action_valid_check=false
  if $is_sha_pinned_check && $has_full_semver_comment_check; then
    is_action_valid_check=true
  fi

  if ! $is_action_valid_check; then
    reason_for_flagging=""
    if ! $is_sha_pinned_check; then
      reason_for_flagging="Action is not pinned to a SHA hash (actual pin: '@$pin_value'). Policy requires SHA + full semver comment."
    else                                    # SHA is pinned, so issue is with the comment
      if ! $comment_delimiter_present; then # No comment delimiter '#' was found
        reason_for_flagging="Action is SHA-pinned ('@$pin_value') but is missing the required comment delimiter '#' and full semver version (e.g., '# vX.Y.Z')."
      elif ! $has_full_semver_comment_check; then # Had a comment delimiter, but not a full semver one
        reason_for_flagging="Action is SHA-pinned ('@$pin_value') but the comment ($comment_text_for_reporting) does not contain a full semver (e.g., '# vX.Y.Z')."
      fi # This 'else' implies SHA pinned, comment delimiter present, but not full semver - covered by elif above.
    fi
    final_lines_to_report+=("$filepath:$lineno:$actual_line_content ($reason_for_flagging)")
  fi
done <<<"$raw_findings"

if [[ ${#final_lines_to_report[@]} -gt 0 ]]; then
  echo "Error: Found action usages violating the pinning policy:" >&2
  echo "(Policy: Action is valid ONLY IF pinned to a SHA hash AND has a comment with a full semver string 'vX.Y.Z'.)" >&2
  printf '%s\n' "${final_lines_to_report[@]}" >&2
  exit 1
else
  echo "Check complete. All 'uses: owner/repo@target' lines are compliant with the specified policy or excluded."
  exit 0
fi
