#!/usr/bin/env sh
set -e

docker compose -f docker-compose.yml -f deploy/specs/production/compose.yml ps
