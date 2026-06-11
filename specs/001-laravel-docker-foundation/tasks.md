---
description: "Task list for the Laravel Docker Foundation feature"
---

# Tasks: Laravel Docker Foundation

**Input**: Design documents from `/specs/001-laravel-docker-foundation/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/runtime-stack.md
**Tests**: Mandatory for this feature. Use PHPUnit and container-level verification to support the TDD constitution.
**Organization**: Tasks are grouped by user story to enable independent implementation and testing.

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Create the repository scaffold and baseline application/runtime configuration.

- [X] T001 [P] Create the feature scaffolding folders in `docker/`, `deploy/`, `resources/views/`, `routes/`, `tests/Feature/`, and `tests/Unit/` to support FR-001, FR-008, and FR-009.
- [X] T002 [P] Initialize the Laravel application baseline in `composer.json`, `bootstrap/app.php`, `config/app.php`, `config/database.php`, and `routes/web.php` to support FR-002 and FR-004.
- [X] T003 [P] Add the required runtime and build dependencies in `composer.json`, `package.json`, and `phpunit.xml` for Laravel 11.x, Blade, Livewire 3, mongodb/laravel-mongodb, nginx, Vite, and PHPUnit to satisfy FR-002 through FR-006.
- [X] T004 [P] Create the initial Blade application shell and Livewire entry point in `resources/views/layouts/app.blade.php`, `resources/views/welcome.blade.php`, and `app/Livewire/HomePage.php` to establish the first webapp foundation for FR-002 and FR-003.

---

## Phase 2: Foundational Infrastructure (Blocking Prerequisites)

**Purpose**: Build the Docker topology and support assets that every story depends on.

- [X] T005 [P] Define the base Docker Compose stack in `docker-compose.yml` with `app`, `nginx`, and `mongodb` services, shared networks, persistent volumes, and environment wiring for FR-001, FR-004, FR-005, and FR-011.
- [X] T006 [P] Build the PHP-FPM application image and runtime bootstrap in `docker/php/Dockerfile`, `docker/php/php.ini`, and `docker/php/entrypoint.sh` so Laravel can run inside the container stack for FR-002 and FR-006.
- [X] T007 [P] Configure nginx as the reverse proxy in `docker/nginx/default.conf` and `docker/nginx/Dockerfile` so the webapp is served through nginx for FR-005.
- [X] T008 [P] Configure MongoDB persistence and initialization in `docker/mongodb/Dockerfile` and `docker/mongodb/init.js` so the database backend is available for FR-004 and FR-011.
- [X] T009 [P] Add healthcheck wiring for the stack in `docker-compose.yml` and `routes/web.php` so the required services expose automatic health signals for FR-007 and FR-011.
- [X] T010 [P] Create the environment-separated deployment support roots in `deploy/specs/local-dev/`, `deploy/specs/staging/`, `deploy/specs/production/`, `deploy/scripts/local-dev/`, `deploy/scripts/staging/`, and `deploy/scripts/production/` for FR-008 and FR-009.

**Checkpoint**: The container topology, runtime configs, and deployment folders exist before any story-specific work begins.

---

## Phase 3: User Story 1 - Local Docker Foundation (Priority: P1)

**Goal**: Run the complete foundation stack locally through Docker with healthy services and web access through nginx.

**Independent Test**: From a clean checkout, start the stack and verify the app, nginx, and MongoDB come up with healthy status and the webapp is reachable through the reverse proxy.

### Tests for User Story 1

- [X] T011 [P] [US1] Write failing stack startup and reachability tests in `tests/Feature/Infrastructure/LocalStackTest.php` to verify FR-001, FR-005, FR-011, and SC-001.
- [X] T012 [P] [US1] Write failing healthcheck tests in `tests/Feature/Infrastructure/HealthcheckTest.php` to verify FR-007, FR-011, and SC-002.

### Implementation for User Story 1

- [X] T013 [US1] Implement the end-to-end local stack composition in `docker-compose.yml`, `docker/php/Dockerfile`, `docker/nginx/default.conf`, and `docker/mongodb/*` so `docker compose up --build` starts the foundation for FR-001, FR-004, and FR-005.
- [X] T014 [US1] Add application and reverse-proxy health endpoints in `routes/web.php` and `app/Http/Controllers/HealthController.php` to satisfy FR-007 and give the smoke tests a stable probe target.
- [X] T015 [US1] Wire container startup dependencies and service healthchecks in `docker-compose.yml` so the webapp waits on MongoDB and the required services report ready/healthy state for FR-007 and FR-011.

**Checkpoint**: The local development stack is runnable and observable through healthchecks.

---

## Phase 4: User Story 2 - Laravel Webapp Foundation (Priority: P1)

**Goal**: Provide the base Laravel webapp shell with Blade and Livewire so feature work can start on top of a functional UI foundation.

**Independent Test**: Open the application and confirm the base Laravel page renders through Blade and Livewire with dependencies installed.

### Tests for User Story 2

- [X] T016 [P] [US2] Write failing webapp rendering tests in `tests/Feature/Webapp/WelcomePageTest.php` and `tests/Feature/Livewire/HomePageTest.php` to verify FR-002, FR-003, FR-006, and FR-012.

### Implementation for User Story 2

- [X] T017 [P] [US2] Build the Blade layout and welcome view in `resources/views/layouts/app.blade.php`, `resources/views/welcome.blade.php`, and `resources/views/components/home-page.blade.php` for FR-003 and FR-012.
- [X] T018 [P] [US2] Create the root Livewire component in `app/Livewire/HomePage.php` and mount it from `routes/web.php` so the application shell is rendered by Blade + Livewire for FR-002 and FR-003.
- [X] T019 [US2] Configure Laravel MongoDB integration and frontend asset wiring in `config/database.php`, `.env.example`, `resources/js/app.js`, and `resources/css/app.css` to support FR-004 and FR-006.
- [X] T020 [US2] Lock the application dependencies and scripts in `composer.json` and `package.json` so PHPUnit, Livewire, MongoDB, and Vite are available for FR-002 through FR-006.

**Checkpoint**: The Laravel webapp foundation renders and is ready for feature work.

---

## Phase 5: User Story 3 - Environment-Specific Deployment Setup (Priority: P2)

**Goal**: Prepare separated deployment support for local-dev, staging, and production, including a local MongoDB client path for inspection.

**Independent Test**: Inspect the repository and run the local-dev variant to confirm the environment-specific support files exist and the MongoDB client is available.

### Tests for User Story 3

- [X] T021 [P] [US3] Write failing deployment asset presence tests in `tests/Feature/Deployment/DeploymentSupportTest.php` to verify FR-008, FR-009, and SC-004.

### Implementation for User Story 3

- [X] T022 [P] [US3] Create local-dev deployment specs and scripts in `deploy/specs/local-dev/` and `deploy/scripts/local-dev/` so the dev environment is explicitly documented and runnable for FR-008, FR-009, and FR-010.
- [X] T023 [P] [US3] Create staging deployment specs and scripts in `deploy/specs/staging/` and `deploy/scripts/staging/` so staging is separately prepared for FR-008 and FR-009.
- [X] T024 [P] [US3] Create production deployment specs and scripts in `deploy/specs/production/` and `deploy/scripts/production/` so production is separately prepared for FR-008 and FR-009.
- [X] T025 [US3] Add the local-dev MongoDB client profile or `docker-compose.override.yml` entry in `docker-compose.override.yml` or `docker-compose.local-dev.yml` plus `docker/mongo-client/Dockerfile` to satisfy FR-010 and SC-003.
- [X] T026 [US3] Document the environment startup and MongoDB inspection flow in `specs/001-laravel-docker-foundation/quickstart.md` so local-dev, staging, and production usage is clear for FR-008 through FR-010.

**Checkpoint**: Environment-specific deployment support exists and local-dev includes MongoDB inspection access.

---

## Phase 6: User Story 4 - Production-Ready Reliability Checks (Priority: P2)

**Goal**: Ensure the foundation has strong runtime reliability checks so the stack is safe to build on.

**Independent Test**: Run health probes against the stack and confirm failures are visible if one required service is unavailable.

### Tests for User Story 4

- [X] T027 [P] [US4] Write failing readiness and failure-mode tests in `tests/Feature/Infrastructure/ReadinessAndFailureTest.php` to verify FR-007, FR-011, and SC-002.

### Implementation for User Story 4

- [X] T028 [US4] Harden the healthcheck implementation in `app/Http/Controllers/HealthController.php`, `routes/web.php`, and `docker-compose.yml` so MongoDB dependency failures are visible for FR-007 and FR-011.
- [X] T029 [US4] Add deployment verification scripts in `deploy/scripts/local-dev/verify.sh`, `deploy/scripts/staging/verify.sh`, and `deploy/scripts/production/verify.sh` to confirm the stack starts and reports healthy state for FR-007, FR-009, and FR-011.

**Checkpoint**: The foundation is observable, failure-aware, and safe to build on.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Close documentation gaps and ensure the prepared foundation is consistent across the repository.

- [ ] T030 [P] Update onboarding references in `README.md` and `specs/001-laravel-docker-foundation/quickstart.md` so the startup path, environment differences, and verification steps match the implemented stack for SC-001 through SC-005.
- [ ] T031 [P] Reconcile any test, contract, and documentation mismatches in `tests/Feature/**` and `specs/001-laravel-docker-foundation/contracts/runtime-stack.md` so the runtime contract stays aligned with FR-001 through FR-012.

---

## Dependencies

- Phase 1 must complete before any Docker, Laravel, or deployment work starts.
- Phase 2 is a blocking prerequisite for all user stories.
- User Story 1 depends on Phase 2 and should be implemented first as the foundation MVP.
- User Story 2 depends on User Story 1 because the webapp shell must run inside the Docker stack.
- User Story 3 depends on the base stack and can proceed after the foundation is stable.
- User Story 4 depends on the healthcheck and service wiring introduced in User Story 1.
- Phase 7 depends on all prior stories being implemented and verified.

## Parallel Execution Examples

### User Story 1
- `T011` and `T012` can run in parallel because they write separate tests in different files.
- `T013` and `T014` can proceed in sequence after the tests are red.

### User Story 2
- `T016` can run before implementation begins.
- `T017` and `T018` can be split across Blade and Livewire work if the stack is already up.

### User Story 3
- `T022`, `T023`, and `T024` can be done in parallel because they target separate environment folders.
- `T025` depends on the local-dev support shape but is independent of staging and production scripts.

### User Story 4
- `T027` can be written while the existing stack is still being refined.
- `T028` and `T029` can be split between health endpoint hardening and environment verification scripting.

## Implementation Strategy

- Deliver the foundation MVP first by completing Phases 1 and 2, then User Story 1.
- Add the Laravel webapp shell next so the application has a real UI foundation for future work.
- Prepare environment-specific deployment support after the base stack is proven locally.
- Finish with reliability hardening and final documentation alignment so the stack is ready for the next feature epic.
