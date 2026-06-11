# Research: Laravel Docker Foundation

## Decisions

### 1. Use PHP 8.3 and Laravel 12.x

- **Decision**: Standardize the webapp foundation on PHP 8.3 and Laravel 12.x.
- **Rationale**: This is a stable, modern baseline with broad ecosystem compatibility for Livewire and the MongoDB Laravel integration package.
- **Alternatives considered**:
  - Laravel 12.x: likely viable, but adds upgrade risk without meaningful benefit for a foundation epic.
  - Older Laravel/PHP combinations: rejected because they reduce longevity and supportability.

### 2. Use Blade + Livewire for the frontend

- **Decision**: Build the frontend foundation with Blade and Livewire inside the Laravel app.
- **Rationale**: This satisfies the monolithic frontend requirement, keeps the initial UI simple, and avoids a separate SPA architecture before business features exist.
- **Alternatives considered**:
  - Vue/React SPA: rejected because the request explicitly asked for Blade + Livewire.
  - Inertia.js: rejected because it introduces a front-end coupling pattern not needed for the first version.

### 3. Use MongoDB with the Laravel MongoDB integration package

- **Decision**: Use MongoDB as the data store and integrate through the Laravel MongoDB package.
- **Rationale**: This matches the explicit database requirement while preserving Laravel-style models and queries.
- **Alternatives considered**:
  - PostgreSQL/MySQL: rejected because the feature request explicitly requires MongoDB.
  - Direct MongoDB driver access only: rejected because it increases boilerplate and reduces Laravel ergonomics.

### 4. Use nginx in front of PHP-FPM

- **Decision**: Use nginx as the reverse proxy and PHP-FPM as the Laravel runtime backend.
- **Rationale**: This is the simplest production-aligned container topology and keeps HTTP serving separate from application execution.
- **Alternatives considered**:
  - Apache: rejected because nginx is specifically requested.
  - Laravel Octane: rejected because the foundation does not yet need an alternative runtime model.

### 5. Use PHPUnit for testing

- **Decision**: Use PHPUnit as the primary test runner.
- **Rationale**: The requirement explicitly calls out PHPUnit and Laravel has first-class PHPUnit support.
- **Alternatives considered**:
  - Pest: rejected because it would add another testing style before the foundation is stable.

### 6. Provide local-dev MongoDB access through a profile or override

- **Decision**: Implement a local-dev profile or `docker-compose.override.yml` that adds a MongoDB web client service (`mongo-express`) exposed by host port.
- **Rationale**: This satisfies the environment-specific inspection requirement through a browser-accessible UI without affecting staging or production stacks.
- **Alternatives considered**:
  - Host-installed MongoDB tools: rejected because the constitution prefers container-first workflows.
  - Always-on web client in all environments: rejected because it would leak dev-only tooling into staging/production.

### 7. Separate deployment support by environment

- **Decision**: Organize deployment specs and scripts under `deploy/specs/{local-dev,staging,production}` and `deploy/scripts/{local-dev,staging,production}`.
- **Rationale**: The user requested separated support for multiple environments, and this layout keeps configuration and execution concerns clear.
- **Alternatives considered**:
  - One shared deployment folder: rejected because it makes environment-specific changes harder to audit.
