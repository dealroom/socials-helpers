# Auto-synced file, managed by [dealroom/core-mothership](https://github.com/dealroom/core-mothership)
# The changes to this file will be automatically overwritten on the next sync. Do not edit by hand!

// https://github.com/terraform-linters/tflint/blob/master/docs/user-guide/config.md
config {
  module = false
  force = false
}

plugin "terraform" {
  enabled = true
  preset  = "recommended"
}

plugin "aws" {
  enabled = true
  version = "0.36.0"
  source  = "github.com/terraform-linters/tflint-ruleset-aws"
}

plugin "google" {
  enabled = true
  version = "0.30.0"
  source  = "github.com/terraform-linters/tflint-ruleset-google"
}
