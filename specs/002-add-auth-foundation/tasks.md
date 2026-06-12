# Tasks: Authorization and Access Foundation

**Input**: Design documents from `/specs/002-add-auth-foundation/`
**Prerequisites**: plan.md (required), spec.md (required), research.md, data-model.md, contracts/auth-interfaces.md, quickstart.md

**Tests**: Tests are mandatory for this feature per constitution (TDD non-negotiable) and are included per user story.

**Organization**: Tasks are grouped by user story to enable independent implementation, validation, and delivery.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no incomplete dependencies)
- **[Story]**: User story label (`[US1]`, `[US2]`, etc.)
- Every task includes an exact file path

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Create missing auth-related project structure and baseline test organization.

- [ ] T001 Create auth domain directories in `app/Models/`, `app/Livewire/Auth/`, `app/Http/Controllers/Auth/`, `app/Http/Middleware/`, `resources/views/livewire/auth/`, `resources/views/livewire/dashboard/`, and `tests/Feature/Auth/`
- [ ] T002 Create migration scaffold directory and baseline migration support in `database/migrations/` and ensure migration path is executable from `artisan`
- [ ] T003 [P] Create auth feature test base setup in `tests/Feature/Auth/AuthFeatureTestCase.php`
- [ ] T004 [P] Add auth quickstart reference section to root docs in `README.md` and align docs index in `docs/README.md`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core auth identity model and shared services required by all user stories.

**⚠️ CRITICAL**: No user story work begins until this phase is complete.

- [ ] T005 Create `UserAccount` model in `app/Models/UserAccount.php`
- [ ] T006 [P] Create `CredentialResetRequest` model in `app/Models/CredentialResetRequest.php`
- [ ] T007 [P] Create `AuthSession` model in `app/Models/AuthSession.php`
- [ ] T008 Create identity collection migration in `database/migrations/2026_06_11_000001_create_auth_identity_collections.php`
- [ ] T009 [P] Create unique/index definitions for identity and reset token lookups in `database/migrations/2026_06_11_000002_add_auth_indexes.php`
- [ ] T010 Implement shared auth service for credential validation/session lifecycle in `app/Services/Auth/AuthService.php`
- [ ] T011 [P] Implement shared password policy + token utility service in `app/Services/Auth/PasswordResetService.php`
- [ ] T012 [P] Implement lockout/rate-limit middleware for sign-in attempts in `app/Http/Middleware/AuthRateLimitMiddleware.php`
- [ ] T013 Wire foundational auth route groups and middleware aliases in `bootstrap/app.php` and `routes/web.php`

**Checkpoint**: Foundation complete; user stories can be implemented independently.

---

## Phase 3: User Story 1 - Secure Sign-In Access (Priority: P1) 🎯 MVP

**Goal**: Users can sign in/out securely, invalid credentials are denied, and lockout behavior is enforced.

**Independent Test**: Create active account, sign in successfully, verify invalid sign-in failure + lockout handling, then sign out and confirm protected route denial.

### Tests for User Story 1 (write first)

- [ ] T014 [P] [US1] Add sign-in success/failure feature tests in `tests/Feature/Auth/SignInTest.php`
- [ ] T015 [P] [US1] Add sign-out and session invalidation feature tests in `tests/Feature/Auth/SignOutTest.php`
- [ ] T016 [P] [US1] Add rate-limit/lockout behavior tests in `tests/Feature/Auth/SignInLockoutTest.php`

### Implementation for User Story 1

- [ ] T017 [US1] Implement sign-in page Livewire component in `app/Livewire/Auth/SignInPage.php`
- [ ] T018 [P] [US1] Implement sign-in UI view in `resources/views/livewire/auth/sign-in-page.blade.php`
- [ ] T019 [US1] Implement sign-out endpoint controller in `app/Http/Controllers/Auth/SessionController.php`
- [ ] T020 [US1] Add sign-in/sign-out routes and middleware bindings in `routes/web.php`
- [ ] T021 [US1] Persist auth session tracking on login/logout in `app/Services/Auth/AuthService.php`

**Checkpoint**: US1 works independently and is fully testable.

---

## Phase 4: User Story 2 - Password Reset Recovery (Priority: P1)

**Goal**: Users can request and complete password reset using single-use time-bound tokens without account enumeration leakage.

**Independent Test**: Request reset for existing/unknown account, complete reset with valid token, verify expired/used token rejection, and confirm old password fails.

### Tests for User Story 2 (write first)

- [ ] T022 [P] [US2] Add reset request behavior tests (including generic response) in `tests/Feature/Auth/PasswordResetRequestTest.php`
- [ ] T023 [P] [US2] Add reset completion/token lifecycle tests in `tests/Feature/Auth/PasswordResetCompleteTest.php`
- [ ] T024 [P] [US2] Add token expiry and single-use invalidation tests in `tests/Feature/Auth/PasswordResetTokenLifecycleTest.php`

### Implementation for User Story 2

- [ ] T025 [US2] Implement reset request page Livewire component in `app/Livewire/Auth/PasswordResetRequestPage.php`
- [ ] T026 [P] [US2] Implement reset request UI view in `resources/views/livewire/auth/password-reset-request-page.blade.php`
- [ ] T027 [US2] Implement reset completion page Livewire component in `app/Livewire/Auth/PasswordResetCompletePage.php`
- [ ] T028 [P] [US2] Implement reset completion UI view in `resources/views/livewire/auth/password-reset-complete-page.blade.php`
- [ ] T029 [US2] Implement reset token creation/verification/invalidation in `app/Services/Auth/PasswordResetService.php`
- [ ] T030 [US2] Add password reset routes and token parameter handling in `routes/web.php`

**Checkpoint**: US2 works independently and is fully testable.

---

## Phase 5: User Story 3 - Authorized Initial Dashboard (Priority: P2)

**Goal**: Authenticated users access a dashboard landing page; unauthenticated users are redirected to sign-in.

**Independent Test**: Access dashboard while signed out (redirect) and signed in (render expected page and user context).

### Tests for User Story 3 (write first)

- [ ] T031 [P] [US3] Add dashboard authorization access tests in `tests/Feature/Auth/DashboardAccessTest.php`
- [ ] T032 [P] [US3] Add dashboard render/content tests in `tests/Feature/Auth/DashboardRenderTest.php`

### Implementation for User Story 3

- [ ] T033 [US3] Implement dashboard page Livewire component in `app/Livewire/Dashboard/InitialDashboardPage.php`
- [ ] T034 [P] [US3] Implement dashboard UI view in `resources/views/livewire/dashboard/initial-dashboard-page.blade.php`
- [ ] T035 [US3] Add protected dashboard route and redirect rules in `routes/web.php`

**Checkpoint**: US3 works independently and is fully testable.

---

## Phase 6: User Story 4 - Identity Data and Admin Bootstrap (Priority: P2)

**Goal**: Operators can initialize identity storage and provision users via deploy scripts safely and idempotently.

**Independent Test**: Run migration/bootstrap and add-user script in clean env; created user can sign in; duplicate/missing input fails safely.

### Tests for User Story 4 (write first)

- [ ] T036 [P] [US4] Add provisioning command validation tests in `tests/Feature/Auth/UserProvisioningCommandTest.php`
- [ ] T037 [P] [US4] Add duplicate identity and weak credential tests in `tests/Feature/Auth/UserProvisioningValidationTest.php`
- [ ] T038 [P] [US4] Add identity migration idempotency tests in `tests/Feature/Auth/AuthMigrationIdempotencyTest.php`

### Implementation for User Story 4

- [ ] T039 [US4] Implement add-user artisan command in `app/Console/Commands/AddAuthUserCommand.php`
- [ ] T040 [US4] Register and wire add-user command in `routes/console.php`
- [ ] T041 [P] [US4] Add local-dev provisioning script in `deploy/scripts/local-dev/add-user.sh`
- [ ] T042 [P] [US4] Add staging provisioning script in `deploy/scripts/staging/add-user.sh`
- [ ] T043 [P] [US4] Add production provisioning script in `deploy/scripts/production/add-user.sh`
- [ ] T044 [US4] Add script usage and expected outcomes in `specs/002-add-auth-foundation/quickstart.md`

**Checkpoint**: US4 works independently and is fully testable.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Complete cross-story hardening, docs, and final validation.

- [ ] T045 [P] Add auth security and operational notes to `README.md` and `docs/README.md`
- [ ] T046 Run full auth test suite and resolve failures in `tests/Feature/Auth/`
- [ ] T047 Validate quickstart end-to-end using container-first commands in `specs/002-add-auth-foundation/quickstart.md`
- [ ] T048 [P] Update feature docs index and task references in `specs/002-add-auth-foundation/plan.md` and `specs/002-add-auth-foundation/tasks.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1 (Setup)**: starts immediately.
- **Phase 2 (Foundational)**: depends on Phase 1 and blocks all user stories.
- **Phases 3-6 (User Stories)**: depend on Phase 2 completion.
- **Phase 7 (Polish)**: depends on selected user stories being complete.

### User Story Dependencies

- **US1 (P1)**: starts immediately after foundational phase.
- **US2 (P1)**: starts after foundational phase; independent of US1 except shared services.
- **US3 (P2)**: depends on authentication/session behavior from US1.
- **US4 (P2)**: depends on foundational entities/migrations and can run in parallel with US3.

### Within Each User Story

- Tests must be written first and fail before implementation.
- Core model/service updates precede route/page wiring.
- Routes/controllers/components precede final story integration checks.

---

## Parallel Execution Examples

### User Story 1

- Run T014, T015, and T016 in parallel (separate test files).
- Run T018 in parallel with T019 after T017 starts (view and controller in different files).

### User Story 2

- Run T022, T023, and T024 in parallel.
- Run T026 and T028 in parallel while T025 and T027 component logic is being developed.

### User Story 4

- Run T041, T042, and T043 in parallel (environment-specific scripts).
- Run T036 and T038 in parallel (different test targets).

---

## Implementation Strategy

### MVP First (US1)

1. Complete Phase 1 and Phase 2.
2. Deliver Phase 3 (US1) fully.
3. Validate sign-in/sign-out and lockout behavior before expanding scope.

### Incremental Delivery

1. Add US2 for account recovery.
2. Add US3 for authenticated landing workflow.
3. Add US4 for operational provisioning.
4. Finish with Phase 7 hardening and full suite validation.

### Team Parallelization

1. Team aligns on Phase 1 and Phase 2 together.
2. After checkpoint:
   - Developer A: US1 + US3 path
   - Developer B: US2 path
   - Developer C: US4 operational path
3. Integrate and run complete auth suite before polish completion.

---

## Notes

- `[P]` tasks touch separate files and can proceed concurrently.
- User-story labels map each task to an independently testable increment.
- This file is execution-ready for `/speckit.implement` after review.
