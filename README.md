# UCOF App

UCOF App is the application foundation for the Unified Compliance Ontology Framework (UCOF). This repository contains the first runnable version of the platform: a Docker-native Laravel application with MongoDB, nginx, Blade, Livewire, health checks, environment-specific deployment helpers, and local development tooling for MongoDB inspection.

The broader UCOF initiative is an ontology-driven compliance platform intended to help organizations plan, implement, verify, and maintain compliance across multiple frameworks through a shared compliance model instead of per-standard silos.

## Motivation

Compliance programs frequently duplicate work across standards that use different terminology to describe similar underlying obligations. UCOF addresses that problem by modeling a common compliance grammar centered on requirements, controls, evidence, risk, and review cycles.

The intended result is a platform where:

- one control implementation can satisfy multiple standards when the underlying obligation is the same
- evidence can be reused across frameworks with explicit traceability
- implementers, auditors, and executives each see the same compliance system through role-appropriate views
- new norms can be added as data definitions instead of requiring core platform rewrites

This repository is the application runtime that will eventually host those workflows.

## Objective

The current objective of this codebase is to establish a reliable cloud-native foundation for future UCOF business features. At this stage, the repository focuses on platform readiness rather than completed compliance workflows.

Current foundation goals:

- run the full web stack in Docker without requiring host-installed PHP, Composer, or MongoDB
- provide a Laravel monolith with Blade and Livewire as the frontend model
- use MongoDB as the primary application datastore
- expose health checks for operational verification
- support separated deployment assets for local development, staging, and production
- provide a local MongoDB web client for inspection during development

## Business Scope

The project is designed around three primary roles.

### Implementer

The Implementer works from obligation discovery to control implementation, evidence management, self-assessment, remediation, and audit readiness. The functional requirements currently describe capabilities such as:

- browsing obligations, requirements, and mapped controls
- breaking control implementation into tasks and tracking completion
- uploading and linking evidence artifacts to controls
- monitoring evidence freshness and handling evidence requests
- managing remediation actions and readiness reviews

### Auditor

The Auditor verifies design, evidence quality, operational effectiveness, findings, remediation, and reporting. The requirements define workflows such as:

- audit scoping and plan creation
- requirement-to-control mapping verification
- evidence validation and operational testing
- finding and nonconformity management with rationale
- remediation verification and executive reporting

### Executive

The Executive uses posture, risk, and audit information to make strategic governance decisions. The intended capabilities include:

- posture and control coverage dashboards
- risk exposure monitoring
- exception approval or rejection with rationale
- remediation prioritization and investment decisions
- benchmarking and trend analysis across organizational scopes

## Ontology Context

UCOF is ontology-first. The domain model is meant to be defined formally before UI and workflows are finalized. The upstream ontology description is maintained in the public UCOF repository at https://github.com/eduluz1976/ucof.

The local reference material in `references/ucof/` describes a shared ontology organized around these namespaces:

- `ucof:core`
- `ucof:org`
- `ucof:evidence`
- `ucof:norm`
- `ucof:control`
- `ucof:risk`
- `ucof:workflow`
- `ucof:observe`

Key universal primitives described in the reference ontology include:

- Requirement
- Control
- ControlMapping
- Evidence
- RiskItem
- Asset
- OrganizationalEntity
- ReviewCycle
- NonConformance

That ontology work is the conceptual source for future application modules in this repository.

## Current Technical Scope

This repository currently implements the application foundation, not the full UCOF business domain.

Included today:

- Laravel 12 application baseline
- Blade and Livewire frontend foundation
- MongoDB integration through `mongodb/laravel-mongodb`
- nginx reverse proxy in front of PHP-FPM
- Docker Compose runtime for app, nginx, and MongoDB
- local development MongoDB web client via `mongo-express`
- PHPUnit-based test setup
- health endpoints and service-level health checks
- deployment support structure for `local-dev`, `staging`, and `production`

Not implemented yet:

- production business workflows for obligations, controls, evidence, audits, risks, and executive dashboards
- norm loading engine and ontology-driven persistence model
- full authorization and role-based product behavior for the business actors

## Architecture Overview

The runtime is a containerized monolith.

### Application Layer

- Laravel 12 on PHP 8.3
- Livewire 3 for interactive server-driven UI
- Blade templates for the frontend shell
- Vite for asset bundling

### Data Layer

- MongoDB 7 as the primary database
- Laravel MongoDB integration package for application access
- MongoDB initialization support that templates the init script with environment-provided credentials on first boot

### Edge Layer

- nginx as the only public HTTP entrypoint
- PHP-FPM application container behind nginx

### Local Development Tooling

- `mongo-express` web client under the `local-dev` profile
- isolated deployment scripts per environment under `deploy/scripts/`

## Technology Stack

### Backend

- PHP 8.3
- Laravel 12
- Livewire 3
- MongoDB Laravel integration

### Frontend

- Blade
- Livewire
- Vite 6
- Tailwind CSS tooling

### Infrastructure

- Docker Compose
- nginx
- MongoDB 7
- mongo-express

### Testing

- PHPUnit 11
- Laravel test runner

## Repository Structure

```text
app/                    Laravel application code
bootstrap/              Laravel bootstrap cache/runtime structure
config/                 Laravel configuration
deploy/                 Environment-specific deployment specs and scripts
docker/                 Container build and runtime assets
docs/                   Project requirements and planning material
references/ucof/        Business and ontology reference material
resources/              Blade views and frontend assets
routes/                 HTTP route definitions
specs/                  Feature specs, plans, contracts, and quickstarts
storage/                Laravel runtime storage
tests/                  Automated test suite
docker-compose.yml      Base application stack
docker-compose.override.yml  Local-dev additions, including MongoDB web client
```

## Runtime Services

### `app`

- Laravel application running on PHP-FPM
- mounts the repository into `/var/www/html`
- depends on healthy MongoDB
- exposes an internal container health check that verifies the MongoDB extension is loaded

### `nginx`

- reverse proxy and public HTTP entrypoint
- publishes port `80`
- depends on healthy app container
- exposes an nginx-specific health endpoint used by the container health check

### `mongodb`

- MongoDB database service
- publishes no host port by default
- initializes root credentials from environment variables
- runs a first-boot init flow based on `docker/mongodb/init.js`
- exposes a `mongosh`-based health check

### `mongo-web-client` (`local-dev` only)

- `mongo-express` for browser-based database inspection
- enabled via the `local-dev` profile in `docker-compose.override.yml`
- publishes port `8081`

## Environment Variables

The project currently relies on standard Laravel settings plus MongoDB-specific variables. Important variables include:

- `APP_ENV`
- `APP_DEBUG`
- `APP_URL`
- `DB_CONNECTION`
- `MONGODB_URI`
- `MONGODB_DATABASE`
- `MONGO_INITDB_ROOT_USERNAME`
- `MONGO_INITDB_ROOT_PASSWORD`
- `MONGO_INITDB_DATABASE`

MongoDB bootstrap notes:

- the MongoDB image uses `MONGO_INITDB_ROOT_USERNAME` and `MONGO_INITDB_ROOT_PASSWORD` during first initialization
- the init helper generates a temporary JavaScript file from `docker/mongodb/init.js`
- placeholders inside that template are replaced with environment-provided values before execution
- Mongo init scripts run only on first database initialization, so changing them later usually requires recreating the MongoDB volume

## Getting Started

### Prerequisites

- Docker
- Docker Compose

No host PHP, Composer, Node.js, or MongoDB installation is required to start the stack.

### Start the Base Stack

```bash
docker compose up --build
```

This starts:

- `mongodb`
- `app`
- `nginx`

The application is then available at:

- `http://localhost`

### Start Local Development With MongoDB Web Client

Use the local-dev helper script:

```bash
sh deploy/scripts/local-dev/up.sh -d
```

This is preferred over calling `docker compose --profile local-dev up` manually because the script proactively removes stale local-dev service state that can otherwise retain invalid Docker network references.

MongoDB web client:

- `http://localhost:8081`

### Verify Health

Application health endpoint:

```bash
curl http://localhost/health
```

Expected behavior:

- HTTP `200` when the application and MongoDB are healthy
- HTTP `503` when the application is running but MongoDB is not reachable

The health payload reports:

- application status
- MongoDB status

## Testing

Run the Laravel test suite inside the application container:

```bash
docker compose exec app php artisan test
```

Run PHPUnit directly if needed:

```bash
docker compose exec app vendor/bin/phpunit
```

Current repository tests include deployment support checks and foundation behavior verification.

## Deployment Support

The repository separates deployment preparation by environment.

### Local Development

- specs: `deploy/specs/local-dev/`
- scripts: `deploy/scripts/local-dev/`

Local development includes the MongoDB web client profile and helper startup script.

### Staging

- specs: `deploy/specs/staging/`
- scripts: `deploy/scripts/staging/`

The staging helper composes the base stack with the staging spec overlay.

### Production

- specs: `deploy/specs/production/`
- scripts: `deploy/scripts/production/`

The production helper composes the base stack with the production spec overlay.

## Frontend Foundation

The current UI layer is intentionally minimal. It establishes the monolithic frontend direction requested for the project:

- server-rendered Blade layouts
- Livewire component entrypoint for the home page
- Vite-managed assets for progressive UI evolution

This gives the project a stable starting point for future business-facing workflows without introducing a separate SPA architecture.

## Health and Reliability Design

Reliability was treated as part of the foundation, not a follow-up concern.

- nginx has its own internal health endpoint for container checks
- Laravel exposes `/health` and verifies database connectivity with a MongoDB ping command
- MongoDB has a container-level `mongosh` health check
- service dependencies in Compose wait for upstream health where applicable

## Development Notes

### MongoDB Init Behavior

MongoDB initialization is environment-driven.

- `docker/mongodb/init.js` is treated as a template
- startup logic generates a temporary initialized copy with concrete credentials
- the generated script is executed during first database initialization

If you change initialization behavior and want it to run again:

```bash
docker compose down -v
docker compose up -d mongodb
```

### Local-Dev Profile Caveat

The `mongo-web-client` service is profile-gated. If profile-specific services are started and stopped inconsistently, Docker can retain stale network metadata on old containers. The local-dev startup script exists to reduce that risk.

If you need a full reset:

```bash
docker compose --profile local-dev down --remove-orphans
sh deploy/scripts/local-dev/up.sh -d
```

## Product Direction

This foundation is intended to evolve into an ontology-backed compliance application covering:

- obligation and requirement ingestion
- control mapping and implementation tracking
- evidence collection and freshness management
- self-assessment and audit workflows
- remediation lifecycle management
- risk and residual risk handling
- executive dashboards for posture, trends, and prioritization

The authoritative business intent for those capabilities is currently described in:

- `docs/requirements.md`
- `references/ucof/README.md`
- `references/ucof/compliance-ontology-framework.md`

## Related References

- Public ontology repository: https://github.com/eduluz1976/ucof
- Local ontology file: `references/ucof/ucof.ttl`
- Local framework design notes: `references/ucof/compliance-ontology-framework.md`
- Foundation implementation spec: `specs/001-laravel-docker-foundation/spec.md`

## Status

Project status: foundation in progress.

The repository already provides a runnable containerized platform baseline. The next major work is implementing the actual UCOF domain model, workflows, persistence structure, and role-specific product features on top of that base.