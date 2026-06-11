# Runtime Stack Contract

This contract defines the runtime interface for the Laravel Docker foundation.

## Services

### webapp

- Laravel application running on PHP-FPM
- Exposed only through nginx
- Must provide a health endpoint

### nginx

- Reverse proxy for all inbound web traffic
- Routes application traffic to the webapp service
- Serves as the public entrypoint for the stack

### mongodb

- Primary database service
- Internal-only network access for the application stack
- Must be reachable from the webapp and inspection tooling

### mongo-web-client

- Dev-only web client service enabled via the local-dev profile or override
- Exposes a browser-accessible MongoDB UI in local development

## Healthcheck Contract

- Each required service must expose a healthcheck that can be executed by container tooling.
- Healthcheck failures must be visible without inspecting service logs manually.
- The webapp healthcheck should confirm application readiness, not just process uptime.

## Deployment Contract

- `local-dev` includes the MongoDB web client service.
- `staging` and `production` exclude dev-only web-client tooling.
- Deployment scripts and specs must be stored separately by environment.
- Service names should remain stable across environments to avoid configuration drift.
