<!--
SYNC IMPACT REPORT
==================
Version change: (unversioned template) → 1.0.0
Bump rationale: MINOR — initial population of all principles and sections from template.

Modified principles:
  - [PRINCIPLE_1_NAME] → I. Test-Driven Development (NON-NEGOTIABLE)
  - [PRINCIPLE_2_NAME] → II. Cloud-Native / Docker-First
  - [PRINCIPLE_3_NAME] → III. Modular Architecture
  - [PRINCIPLE_4_NAME] → IV. Frontend Monolith
  - [PRINCIPLE_5_NAME] → V. Open Source Standards

Added sections:
  - Infrastructure & Runtime Constraints
  - Development Workflow

Removed sections: none

Templates requiring updates:
  ✅ .specify/templates/plan-template.md — Constitution Check section is dynamic; no hardcoded references to change.
  ✅ .specify/templates/spec-template.md — User story independent-testability guidance aligns with Modular + TDD principles.
  ⚠  .specify/templates/tasks-template.md — Line reads "Tests are OPTIONAL - only include them if explicitly requested."
      This conflicts with Principle I (TDD NON-NEGOTIABLE). Manual update recommended: change note to reflect
      that tests are MANDATORY per constitution unless the feature is purely infrastructure/config.

Follow-up TODOs:
  - None; all placeholders resolved.
-->

# UCOF Constitution

## Core Principles

### I. Test-Driven Development (NON-NEGOTIABLE)

TDD MUST be applied to every feature without exception:

- Tests MUST be written and reviewed before any implementation code is produced.
- The Red-Green-Refactor cycle is strictly enforced: write a failing test → implement the minimum
  code to pass → refactor.
- Unit tests, integration tests, and contract tests are all first-class deliverables, not
  afterthoughts.
- A feature MUST NOT be considered "done" unless all its acceptance scenarios are covered by
  automated tests that pass in CI.
- Test coverage gates MUST be enforced in CI; reductions in coverage MUST be explicitly justified
  and approved.

**Rationale**: Test-first discipline eliminates ambiguity in requirements, enables safe refactoring,
and ensures the codebase remains trustworthy as the open-source contributor base grows.

### II. Cloud-Native / Docker-First

All runtime and development operations MUST run inside containers:

- Every service, task runner, migration, linter, and test suite MUST be executable via Docker
  Compose or an equivalent container orchestration manifest — no host-installed tooling is required
  beyond Docker itself.
- Images MUST be built from minimal, pinned base images (e.g., `alpine`, `distroless`) to reduce
  attack surface.
- Configuration MUST be injected through environment variables (12-Factor App, Factor III).
- Services MUST expose structured health-check endpoints consumable by container orchestrators.
- Secrets MUST NOT be baked into images; they MUST be mounted at runtime via secrets management.

**Rationale**: Container-first development eliminates "works on my machine" problems, simplifies
onboarding for open-source contributors, and guarantees parity between local and production
environments.

### III. Modular Architecture

The backend MUST be composed of independently deployable modules:

- Each module MUST own its domain: data models, business logic, API surface, and tests.
- Modules MUST communicate through well-defined contracts (e.g., REST, gRPC, or async events);
  direct cross-module imports of internal implementation details are forbidden.
- A module MUST be deployable and testable in isolation without requiring the full system to be
  running.
- New capabilities MUST be introduced as new modules rather than expanding existing module
  boundaries beyond their declared domain.
- Shared infrastructure (auth, logging, observability) MUST live in dedicated cross-cutting
  modules, not scattered across domain modules.

**Rationale**: Modularity enables parallel development across contributors, clear ownership, and
incremental delivery — each module can be a standalone MVP increment.

### IV. Frontend as Monolith

The frontend MUST be delivered as a single, cohesive deployable unit:

- All UI concerns (routing, state, components, styles) MUST reside in one frontend application.
- The frontend MUST NOT be split into micro-frontends or separately deployed UI fragments.
- The frontend MUST communicate with backend modules exclusively through their public API contracts;
  it MUST NOT have knowledge of backend internal structure.
- The frontend build artifact MUST be a self-contained bundle, servable from a container image or
  a static CDN.
- Feature flags or runtime configuration MUST be used to manage rollout of new UI capabilities
  without requiring separate deployments.

**Rationale**: A monolithic frontend maximises UI coherence, simplifies state management, and
reduces operational overhead — appropriate for a project where backend modularity already provides
the scalability benefits.

### V. Open Source Standards

All contributions MUST meet open-source quality and transparency expectations:

- Public API changes MUST follow Semantic Versioning (MAJOR.MINOR.PATCH).
- Every module and public interface MUST have documentation sufficient for a new contributor to
  understand and use it without consulting the original author.
- Breaking changes MUST be communicated in a CHANGELOG entry and, where possible, gated behind a
  deprecation period.
- Security vulnerabilities disclosed responsibly MUST be patched before public disclosure; a
  SECURITY.md file MUST be maintained.
- All dependencies MUST have OSI-approved licenses compatible with the project's own license.

**Rationale**: Open-source sustainability depends on contributor trust, discoverable documentation,
and predictable versioning.

## Infrastructure & Runtime Constraints

- The primary container runtime is Docker; Docker Compose MUST be the local development entrypoint.
- CI/CD pipelines MUST run the full test suite inside containers matching the production image.
- Database migrations MUST be versioned and reversible; they MUST run as an explicit container step,
  not automatically on application startup.
- Observability (structured logs, metrics, distributed traces) MUST be implemented from the first
  production-bound service, not retrofitted later.
- All HTTP endpoints MUST be protected by appropriate authentication/authorization; unauthenticated
  endpoints MUST be explicitly declared and reviewed.

## Development Workflow

- All work MUST begin with a feature branch branched from `main`.
- Pull requests MUST pass CI (lint + full test suite) before review.
- Constitution Check is a mandatory gate in every implementation plan; violations MUST be
  documented and approved before work proceeds.
- Code reviews MUST verify compliance with the five Core Principles above.
- Complexity that violates any principle MUST be justified in writing in the plan or PR description.

## Governance

This constitution supersedes all other development practices. Where conflict exists, the
constitution wins.

Amendments require:
1. A written proposal describing the change, motivation, and migration impact.
2. Consensus from active maintainers (defined as contributors with ≥2 merged PRs in the last 90
   days).
3. A version bump in this document following the semantic versioning rules defined in Principle V.
4. An update to any affected templates or dependent artifacts within the same PR.

All PRs and code reviews MUST verify compliance with this constitution. Exceptions require explicit
written justification in the PR and MUST be tracked as technical-debt items.

**Version**: 1.0.0 | **Ratified**: 2026-06-10 | **Last Amended**: 2026-06-10
