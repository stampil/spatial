#!/bin/bash
# Gives GitHub what the Publish action needs to send the site to InfinityFree, then runs the action again. The two
# passwords are typed here and go straight to GitHub as secrets: only the owner can set them, nobody reads them back.
set -euo pipefail
REPOSITORY=stampil/spatial
HOSTING_USER=if0_43514686
DB_USER=if0_43464490
DB_HOST=sql101.infinityfree.com
DB_NAME=if0_43464490_xxxx
WORKFLOW=publish.yml
BRANCH=revival

set_variable() {
	if ! gh api --method POST "repos/${REPOSITORY}/actions/variables" -f name="$1" -f value="$2" --silent 2>/dev/null; then
		gh api --method PATCH "repos/${REPOSITORY}/actions/variables/$1" -f name="$1" -f value="$2" --silent
	fi
}

set_secret_from_prompt() {
	local password
	IFS= read -r -s -p "$2: " password
	echo
	printf '%s' "$password" | gh secret set "$1" --repo "$REPOSITORY"
}

main() {
	set_variable DB_HOST "$DB_HOST"
	set_variable DB_NAME "$DB_NAME"
	set_secret_from_prompt HOSTING_PASSWORD "FTP password of the hosting account ${HOSTING_USER}"
	set_secret_from_prompt DB_PASSWORD "MySQL password of ${DB_USER}"
	local run_id
	run_id=$(gh api "repos/${REPOSITORY}/actions/workflows/${WORKFLOW}/runs?branch=${BRANCH}&per_page=1" --jq '.workflow_runs[0].id')
	gh api --method POST "repos/${REPOSITORY}/actions/runs/${run_id}/rerun" --silent
	echo "Publishing: https://github.com/${REPOSITORY}/actions/runs/${run_id}"
}

if [ "${BASH_SOURCE[0]}" = "$0" ]; then
	main
fi
