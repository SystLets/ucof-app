#!/usr/bin/env sh
set -e

# Profile-gated service can keep stale network metadata if previous runs
# used a different compose lifecycle. Remove only this service before up.
docker compose --profile local-dev rm -sf mongo-web-client >/dev/null 2>&1 || true

docker compose --profile local-dev up --build "$@"
