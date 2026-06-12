# Tasks: Authentication Pages and Profile

**Input**: Design documents from `/specs/003-add-auth-pages/`
**Prerequisites**: `plan.md`, `spec.md`, `research.md`, `data-model.md`, `contracts/auth-pages-interfaces.md`, `quickstart.md`

**Tests**: Tests are required for this feature by constitution and plan (TDD gate).

**Organization**: Tasks are grouped by user story so each story can be implemented and tested independently.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies on incomplete tasks)
- **[Story]**: User story label (`[US1]`, `[US2]`, `[US3]`, `[US4]`)
- Every task includes the target file path

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Prepare feature scaffolding, shared test fixtures, and route placeholders.

- [X] T001 Create auth/profile feature test directory structure in tests/Feature/Auth/.gitkeep
- [X] T002 Create reusable auth test data factory updates in tests/Factories/UserFactory.php
- [X] T003 [P] Add auth/profile route section placeholders in routes/web.php
- [X] T004 [P] Create shared auth/profile view directories in resources/views/auth/.gitkeep
- [X] T005 [P] Create shared profile view directory in resources/views/profile/.gitkeep

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core auth/profile infrastructure required before user-story implementation.

**⚠️ CRITICAL**: No user story work should start before this phase completes.

- [X] T006 Implement shared password policy rule class in app/Rules/PasswordPolicyRule.php
- [X] T007 [P] Implement auth failure message helper for non-enumerating responses in app/Support/AuthMessage.php
- [X] T008 [P] Implement reset token repository service (hash/expiry/used handling) in app/Services/Auth/PasswordResetTokenService.php
- [X] T009 Implement session invalidation service for current/all-other session logic in app/Services/Auth/SessionInvalidationService.php
- [X] T010 Wire shared auth/profile middleware constraints in routes/web.php
- [X] T011 Add foundational unit tests for password policy and token lifecycle in tests/Unit/Auth/AuthFoundationRulesTest.php

**Checkpoint**: Foundation ready - user stories can proceed.

---

## Phase 3: User Story 1 - User Login and Logout (Priority: P1) 🎯 MVP

**Goal**: Users can login with valid credentials and logout to invalidate session access.

**Independent Test**: Valid login redirects to landing page, invalid login returns safe error, logout invalidates protected access.

### Tests for User Story 1

- [X] T012 [P] [US1] Add login success/failure feature tests in tests/Feature/Auth/LoginFlowTest.php
- [X] T013 [P] [US1] Add logout session invalidation feature tests in tests/Feature/Auth/LogoutFlowTest.php

### Implementation for User Story 1

- [X] T014 [P] [US1] Implement login page component class in app/Livewire/Auth/LoginPage.php
- [X] T015 [P] [US1] Implement login page Blade view in resources/views/livewire/auth/login-page.blade.php
- [X] T016 [US1] Implement login authentication action with safe failure messaging in app/Services/Auth/LoginService.php
- [X] T017 [US1] Implement logout action using session invalidation service in app/Http/Controllers/Auth/LogoutController.php
- [X] T018 [US1] Register login/logout routes and middleware rules in routes/web.php
- [X] T019 [US1] Add login/logout integration assertions for redirect behavior in tests/Feature/Auth/LoginLogoutRoutingTest.php

**Checkpoint**: User Story 1 fully functional and independently testable.

---

## Phase 4: User Story 2 - Password Reset Access Recovery (Priority: P1)

**Goal**: Users can request reset and complete password update with single-use token semantics.

**Independent Test**: Reset request is non-enumerating, valid token resets password, expired/used token fails.

### Tests for User Story 2

- [X] T020 [P] [US2] Add reset request privacy tests in tests/Feature/Auth/PasswordResetRequestTest.php
- [X] T021 [P] [US2] Add reset completion token lifecycle tests in tests/Feature/Auth/PasswordResetCompletionTest.php

### Implementation for User Story 2

- [X] T022 [P] [US2] Implement reset request page component in app/Livewire/Auth/PasswordResetRequestPage.php
- [X] T023 [P] [US2] Implement reset request Blade view in resources/views/livewire/auth/password-reset-request-page.blade.php
- [X] T024 [P] [US2] Implement reset completion page component in app/Livewire/Auth/PasswordResetCompletePage.php
- [X] T025 [P] [US2] Implement reset completion Blade view in resources/views/livewire/auth/password-reset-complete-page.blade.php
- [X] T026 [US2] Implement reset request/complete orchestration service in app/Services/Auth/PasswordResetService.php
- [X] T027 [US2] Register password reset routes and token checks in routes/web.php
- [X] T028 [US2] Add token reuse/expiry integration assertions in tests/Feature/Auth/PasswordResetTokenReplayTest.php

**Checkpoint**: User Stories 1 and 2 are independently functional.

---

## Phase 5: User Story 3 - Authenticated Landing Page (Priority: P2)

**Goal**: Authenticated users land on dashboard page after login while guests are redirected to login.

**Independent Test**: Guest access redirects to login; authenticated access returns landing page.

### Tests for User Story 3

- [X] T029 [P] [US3] Add dashboard auth-protection tests in tests/Feature/Auth/LandingAccessTest.php

### Implementation for User Story 3

- [X] T030 [P] [US3] Implement dashboard route handler/controller in app/Http/Controllers/DashboardController.php
- [X] T031 [P] [US3] Implement dashboard Blade view in resources/views/dashboard/index.blade.php
- [X] T032 [US3] Wire post-login redirect target to dashboard route in app/Providers/RouteServiceProvider.php
- [X] T033 [US3] Add guest/auth route guard assertions in tests/Feature/Auth/LandingRoutingTest.php

**Checkpoint**: User Stories 1-3 are independently functional.

---

## Phase 6: User Story 4 - Profile View and Password Change (Priority: P2)

**Goal**: Authenticated users can view name/email and change password with current-password verification.

**Independent Test**: Profile shows authenticated identity; password change requires valid current password and invalidates other sessions.

### Tests for User Story 4

- [X] T034 [P] [US4] Add profile data visibility tests in tests/Feature/Auth/ProfileViewTest.php
- [X] T035 [P] [US4] Add password change validation tests in tests/Feature/Auth/ProfilePasswordChangeTest.php
- [X] T036 [P] [US4] Add multi-session invalidation tests after password change in tests/Feature/Auth/ProfileSessionInvalidationTest.php

### Implementation for User Story 4

- [X] T037 [P] [US4] Implement profile page component in app/Livewire/Profile/ProfilePage.php
- [X] T038 [P] [US4] Implement profile page Blade view (name/email + password form) in resources/views/livewire/profile/profile-page.blade.php
- [X] T039 [US4] Implement profile password change service using shared password policy in app/Services/Auth/ProfilePasswordChangeService.php
- [X] T040 [US4] Register profile and password-change routes with auth middleware in routes/web.php
- [X] T041 [US4] Integrate session invalidation (other sessions only) into password change flow in app/Services/Auth/ProfilePasswordChangeService.php
- [X] T042 [US4] Add profile end-to-end assertions (view + update + session behavior) in tests/Feature/Auth/ProfileEndToEndFlowTest.php

**Checkpoint**: All user stories are independently functional.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Final hardening, docs, and end-to-end verification across stories.

- [X] T043 [P] Update auth/profile feature documentation in docs/README.md
- [X] T044 [P] Update root usage notes for new auth pages and profile flow in README.md
- [X] T045 Run full container-first auth/profile test suite in tests/Feature/Auth/
- [X] T046 Run quickstart validation steps and capture corrections in specs/003-add-auth-pages/quickstart.md
- [X] T047 Security hardening pass for auth/profile error messages and logging in app/Services/Auth/

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: No dependencies, starts immediately.
- **Phase 2 (Foundational)**: Depends on Phase 1 and blocks all user stories.
- **Phases 3-6 (User Stories)**: Depend on Phase 2 completion.
- **Phase 7 (Polish)**: Depends on completion of desired user stories.

### User Story Dependencies

- **US1 (P1)**: Starts after Phase 2; no dependency on other stories.
- **US2 (P1)**: Starts after Phase 2; independent from US1 except shared foundational services.
- **US3 (P2)**: Starts after Phase 2; depends on US1 redirect semantics for final integration.
- **US4 (P2)**: Starts after Phase 2; independent from US2 but shares password policy and session services.

### Within Each User Story

- Tests are written before implementation and must fail first.
- Components/views before orchestration service wiring.
- Route registration after handlers/components exist.
- Story-specific integration tests complete each story checkpoint.

### Parallel Opportunities

- Setup tasks `T003-T005` can run in parallel.
- Foundational tasks `T007-T008` can run in parallel.
- US1 tests `T012-T013` can run in parallel.
- US2 component/view tasks `T022-T025` can run in parallel.
- US4 tests `T034-T036` and component/view tasks `T037-T038` can run in parallel.

---

## Parallel Example: User Story 1

```bash
# Run in parallel after T011:
Task T012: tests/Feature/Auth/LoginFlowTest.php
Task T013: tests/Feature/Auth/LogoutFlowTest.php

# Run in parallel after tests are created:
Task T014: app/Livewire/Auth/LoginPage.php
Task T015: resources/views/livewire/auth/login-page.blade.php
```

## Parallel Example: User Story 2

```bash
# Build reset UI pieces in parallel:
Task T022: app/Livewire/Auth/PasswordResetRequestPage.php
Task T023: resources/views/livewire/auth/password-reset-request-page.blade.php
Task T024: app/Livewire/Auth/PasswordResetCompletePage.php
Task T025: resources/views/livewire/auth/password-reset-complete-page.blade.php
```

## Parallel Example: User Story 4

```bash
# Execute profile tests in parallel:
Task T034: tests/Feature/Auth/ProfileViewTest.php
Task T035: tests/Feature/Auth/ProfilePasswordChangeTest.php
Task T036: tests/Feature/Auth/ProfileSessionInvalidationTest.php
```

---

## Implementation Strategy

### MVP First (US1)

1. Complete Phase 1 and Phase 2.
2. Complete Phase 3 (US1).
3. Validate login/logout independently before adding more stories.

### Incremental Delivery

1. Deliver US1 + US2 to complete core authentication and recovery.
2. Deliver US3 for authenticated landing UX completion.
3. Deliver US4 for profile and password maintenance capabilities.
4. Execute Phase 7 hardening and documentation updates.

### Parallel Team Strategy

1. Team aligns on Phase 1-2.
2. After foundation:
   - Developer A: US1
   - Developer B: US2
   - Developer C: US3
   - Developer D: US4
3. Merge per-story once each story checkpoint passes.

---

## Notes

- `[P]` tasks are isolated by file and can execute concurrently.
- `[USx]` labels preserve traceability to user stories in `spec.md`.
- Keep commits scoped by task or small logical groups.
- Always run container-first commands for testing and validation.
