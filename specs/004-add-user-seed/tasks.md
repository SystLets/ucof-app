# Tasks: User Provisioning Script for Login

**Input**: Design documents from `/specs/004-add-user-seed/`
**Prerequisites**: `plan.md`, `spec.md`, `research.md`, `data-model.md`, `contracts/provision-user-contract.md`, `quickstart.md`

**Tests**: Required per constitution (TDD NON-NEGOTIABLE).

**Organization**: Tasks grouped by user story for independent implementation and testing.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no incomplete dependencies)
- **[Story]**: `[US1]`, `[US2]`, `[US3]`, `[US4]`

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Scaffold directories, register command, and prepare test structure.

- [X] T001 Create provisioning test directories in tests/Feature/Provisioning/.gitkeep and tests/Unit/Provisioning/.gitkeep
- [X] T002 [P] Verify ProvisionUser command auto-discovery works in Laravel 12 and only register explicitly in bootstrap/app.php if discovery fails
- [X] T003 [P] Create app/Console/Commands/ directory stub if not present

**Checkpoint**: Command discovery scaffolding in place; test directories exist.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core command skeleton and shared validation infrastructure required before story implementation.

**⚠️ CRITICAL**: No user story work should start before this phase completes.

- [X] T004 Implement ProvisionUser Artisan command skeleton with --name, --email, --password options in app/Console/Commands/ProvisionUser.php
- [X] T005 [P] Add foundational unit test asserting command exists and is registered in tests/Unit/Provisioning/ProvisionUserValidationTest.php
- [X] T006 [P] Verify PasswordPolicyRule is accessible from command context in tests/Unit/Provisioning/ProvisionUserValidationTest.php

**Checkpoint**: Command skeleton registered and reachable; password rule verified in command context.

---

## Phase 3: User Story 1 - Provision First Login User (Priority: P1) 🎯 MVP

**Goal**: Running the command with valid inputs creates a new active user that can log in.

**Independent Test**: Execute command with valid name/email/password in a clean environment; confirm user exists and can authenticate via `/login`.

### Tests for User Story 1

- [X] T007 [P] [US1] Add test: valid inputs create active user with provisioned_at and provisioned_from in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T008 [P] [US1] Add test: missing required argument exits with code 1 and safe message in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T009 [P] [US1] Add test: invalid email format exits with code 1 and safe validation message in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T010 [P] [US1] Add test: weak password exits with code 1 and policy guidance message in tests/Feature/Provisioning/ProvisionUserCommandTest.php

### Implementation for User Story 1

- [X] T011 [US1] Implement input validation (required args, valid email, PasswordPolicyRule) in app/Console/Commands/ProvisionUser.php
- [X] T012 [US1] Implement new user creation with status=active, provisioned_at, provisioned_from in app/Console/Commands/ProvisionUser.php
- [X] T013 [US1] Implement safe success output (no password echo) via $this->info() in app/Console/Commands/ProvisionUser.php
- [X] T014 [US1] Implement safe failure output via $this->error() for validation and missing-arg cases in app/Console/Commands/ProvisionUser.php

**Checkpoint**: US1 independently functional — valid provisioning creates a login-ready user.

---

## Phase 4: User Story 2 - Prevent Duplicate Identities / Reactivate Inactive Users (Priority: P1)

**Goal**: Active duplicate email is rejected safely; inactive/disabled account is reactivated with updated password.

**Independent Test**: Run command with active-duplicate email → exit 1; run with disabled-user email → exit 0 and account active.

### Tests for User Story 2

- [X] T015 [P] [US2] Add test: active duplicate email exits code 1 with safe message and no DB modification in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T016 [P] [US2] Add test: locked user with same email is treated as duplicate active and rejected in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T017 [P] [US2] Add test: inactive user with same email is reactivated and password updated in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T018 [P] [US2] Add test: disabled user with same email is reactivated and password updated in tests/Feature/Provisioning/ProvisionUserCommandTest.php

### Implementation for User Story 2

- [X] T019 [US2] Implement email lookup and active/locked duplicate guard in app/Console/Commands/ProvisionUser.php
- [X] T020 [US2] Implement reactivation branch (status=active, password update, provisioned_at/from) for inactive/disabled accounts in app/Console/Commands/ProvisionUser.php

**Checkpoint**: US1 and US2 independently functional.

---

## Phase 5: User Story 3 - Container-First Operational Execution (Priority: P2)

**Goal**: Each environment has a thin shell wrapper that delegates to the Artisan command via docker compose exec.

**Independent Test**: Run `sh deploy/scripts/local-dev/add-user.sh` with valid args; confirm provisioned user can log in via browser.

### Tests for User Story 3

- [X] T021 [P] [US3] Add test: container-invoked command path succeeds with valid CLI args in tests/Feature/Provisioning/ProvisionUserCommandTest.php

### Implementation for User Story 3

- [X] T022 [P] [US3] Implement deploy/scripts/local-dev/add-user.sh wrapper (validate 3 args, docker compose exec delegation)
- [X] T023 [P] [US3] Implement deploy/scripts/staging/add-user.sh wrapper (same pattern)
- [X] T024 [P] [US3] Implement deploy/scripts/production/add-user.sh wrapper (same pattern)

**Checkpoint**: US1–US3 independently functional; container-first execution path validated.

---

## Phase 6: User Story 4 - Safe Operator Feedback (Priority: P2)

**Goal**: All output is actionable and non-sensitive; no password ever appears in command or wrapper output.

**Independent Test**: Run invalid inputs and inspect all output lines to confirm no secret leakage and clear guidance.

### Tests for User Story 4

- [X] T025 [P] [US4] Add test: no output line from command contains the supplied password string in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T026 [P] [US4] Add test: success output contains email confirmation without password in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T027 [P] [US4] Add test: wrapper script usage/error paths do not echo password in deploy/scripts/*/add-user.sh

### Implementation for User Story 4

- [X] T028 [US4] Audit all $this->info() and $this->error() calls in app/Console/Commands/ProvisionUser.php to confirm no password leakage
- [X] T029 [US4] Audit wrapper scripts (all three) to confirm no password echo in deploy/scripts/*/add-user.sh

**Checkpoint**: All four user stories independently functional and safe.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final validation, documentation updates, and end-to-end quickstart verification.

- [X] T030 [P] Add test: provisioning command handles DB connectivity failure with safe error output in tests/Feature/Provisioning/ProvisionUserCommandTest.php
- [X] T031 [P] Update quickstart.md with any corrections found during implementation in specs/004-add-user-seed/quickstart.md
- [X] T032 [P] Add provisioning usage notes to README.md
- [ ] T033 Run full provisioning test suite in containers: docker compose exec app php artisan test tests/Feature/Provisioning tests/Unit/Provisioning

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: No dependencies; starts immediately.
- **Phase 2 (Foundational)**: Requires Phase 1; blocks all user stories.
- **Phases 3–6 (User Stories)**: Require Phase 2; US3 and US4 can parallel US1/US2 after foundational work.
- **Phase 7 (Polish)**: Requires all desired user story phases complete.

### User Story Dependencies

- **US1 (P1)**: Starts after Phase 2; no other story dependency.
- **US2 (P1)**: Starts after Phase 2; shares `ProvisionUser.php` with US1 — complete US1 first within same file.
- **US3 (P2)**: Starts after Phase 2; wrapper scripts are independent of US1/US2 implementation files.
- **US4 (P2)**: Starts after US1/US2 implementation exists to audit.

### Within Each User Story

- Tests written first and must fail before implementation.
- Validation logic before persistence logic.
- Command implementation before wrapper scripts.

### Parallel Opportunities

- T007–T010 (US1 tests) can run in parallel.
- T015–T018 (US2 tests) can run in parallel.
- T022–T024 (wrapper scripts) can run in parallel.
- T025–T027 (US4 output tests) can run in parallel.
- T031–T032 (polish docs) can run in parallel.

---

## Parallel Example: User Story 1

```bash
# Write tests in parallel after T006:
Task T007: ProvisionUserCommandTest.php (valid creation)
Task T008: ProvisionUserCommandTest.php (missing arg)
Task T009: ProvisionUserCommandTest.php (invalid email)
Task T010: ProvisionUserCommandTest.php (weak password)
```

## Parallel Example: User Story 3

```bash
# Write all three wrappers in parallel:
Task T022: deploy/scripts/local-dev/add-user.sh
Task T023: deploy/scripts/staging/add-user.sh
Task T024: deploy/scripts/production/add-user.sh
```

---

## Implementation Strategy

### MVP First (US1 + US2)

1. Complete Phase 1 and Phase 2.
2. Complete Phase 3 (US1) — new user creation path.
3. Complete Phase 4 (US2) — duplicate/reactivation guard.
4. Validate independently: provisioned user logs in; duplicate rejected; disabled user reactivated.

### Incremental Delivery

1. US1 + US2 → operator can provision and guard against duplicates.
2. US3 → container-first wrappers enable standard operational usage.
3. US4 → output safety audit confirms no secret leakage.
4. Phase 7 → documentation and final validation.

---

## Notes

- `[P]` tasks target independent files and can execute concurrently.
- `[USx]` labels map to spec.md user stories for full traceability.
- All test and command execution must use `docker compose exec app php artisan ...`.
- Password must never appear in any output line — this is a security requirement, not just a style preference.
