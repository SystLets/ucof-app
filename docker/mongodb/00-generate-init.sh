#!/usr/bin/env bash
set -euo pipefail

template_file="/docker-entrypoint-initdb.d/init.js.template"
tmp_file="/tmp/ucof-init.js"
generated_file="/tmp/01-init.generated.js"

if [[ ! -f "$template_file" ]]; then
  echo "Template file not found: $template_file" >&2
  exit 1
fi

cp "$template_file" "$tmp_file"

username="${MONGO_INITDB_ROOT_USERNAME:-root}"
password="${MONGO_INITDB_ROOT_PASSWORD:-root}"

# Escape values for insertion into single-quoted JavaScript strings.
username_js="${username//\\/\\\\}"
username_js="${username_js//\'/\\\'}"
username_js="${username_js//&/\\&}"

password_js="${password//\\/\\\\}"
password_js="${password_js//\'/\\\'}"
password_js="${password_js//&/\\&}"

sed \
  -e "s|DB-ADMIN-USERNAME|$username_js|g" \
  -e "s|DB-ADMIN-USER-PASSWORD|$password_js|g" \
  "$tmp_file" > "$generated_file"

database="${MONGO_INITDB_DATABASE:-ucof}"
auth_db="admin"

mongosh --quiet \
  --username "$username" \
  --password "$password" \
  --authenticationDatabase "$auth_db" \
  "$database" \
  "$generated_file"

