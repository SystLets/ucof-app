# Tasks: Authorization and Access Foundation

**Input**: Design documents from `/specs/002-add-auth-foundation/`
**Prerequisites**: plan.md (required), spec.md (required), research.md, data-model.md, contracts/, quickstart.md

**Tests**: Tests are mandatory for this feature per constitution (TDD non-negotiable).

**Organization**: Tasks are grouped by user story to enable independent implementation and testing.

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Prepare repository structure and shared scaffolding for auth work.

- [ ] T001 Create auth folders in `app/Models/`, `app/Services/Auth/`, `app/Http/Controllers/Auth/`, `app/Http/Middleware/`, `app/Livewire/Auth/`, `app/Livewire/Dashboard/`, `resources/views/livewire/auth/`, `resources/views/livewire/dashboard/`, `tests/Feature/Auth/`, and `database/migrations/`
- [ ] T002 Create auth feature test base helper in `tests/Feature/Auth/AuthFeatureTestCase.php`
- [ ] T003 [P] Add auth docs anchors in `README.md` and `docs/README.md`

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Implement identity model and shared auth infrastructure required by all user stories.

**⚠️ CRITICAL**: No user story work starts before this phase completes.

- [ ] T004 Create `UserAccount` model in `app/Models/UserAccount.php`
- [ ] T005 [P] Create `CredentialResetRequest` model in `app/Models/CredentialResetRequest.php`
- [ ] T006 [P] Create `AuthSession` model in `app/Models/AuthSession.php`
- [ ] T007 [P] Create `AccessRole` model in `app/Models/AccessRole.php`
- [ ] T008 Create identity collections migration in `database/migrations/2026_06_11_000001_create_auth_identity_collections.php`
- [ ] T009 [P] Create auth indexes migration in `database/migrations/2026_06_11_000002_add_auth_indexes.php`
- [ ] T010 Implement auth/session service in `app/Services/Auth/AuthService.php`
- [ ] T011 [P] Implement password reset/token service in `app/Services/Auth/PasswordResetService.php`
- [ ] T012 [P] Implement sign-in lockout middleware in `app/Http/Middleware/AuthRateLimitMiddleware.php`
- [ ] T013 Wire middleware aliases and foundational route groups in `bootstrap/app.php` and `routes/web.php`

**Checkpoint**: Foundation complete; user stories can proceed.

---

## Phase 3: User Story 1 - Secure Sign-In Access (Priority: P1) 🎯 MVP

**Goal**: Users can sign in and sign out securely with lockout protection and non-leaky failures.

**Independent Test**: Create active account, sign in successfully, fail invalid sign-in attempts, verify lockout behavior, sign out, and confirm protected routes are blocked after sign-out.

### Tests for User Story 1

- [ ] T014 [P] [US1] Add sign-in success and invalid-credential tests in `tests/Feature/Auth/SignInTest.php`
- [ ] T015 [P] [US1] Add sign-out/session invalidation tests in `tests/Feature/Auth/SignOutTest.php`
- [ ] T016 [P] [US1] Add lockout/rate-limit tests in `tests/Feature/Auth/SignInLockoutTest.php`

### Implementation for User Story 1

- [ ] T017 [US1] Implement sign-in Livewire page in `app/Livewire/Auth/SignInPage.php`
- [ ] T018 [P] [US1] Implement sign-in view in `resources/views/livewire/auth/sign-in-page.blade.php`
- [ ] T019 [US1] Implement session controller for sign-out in `app/Http/Controllers/Auth/SessionController.php`
- [ ] T020 [US1] Add sign-in/sign-out routes in `routes/web.php`
- [ ] T021 [US1] Persist session tracking and lockout counters in `app/Services/Auth/AuthService.php`

**Checkpoint**: US1 is independently functional and testable.

---

## Phase 4: User Story 2 - Password Reset Recovery (Priority: P1)

**Goal**: Users can request and complete password reset with single-use, time-bound tokens.

**Independent Test**: Request reset for existing/unknown accounts, complete reset with valid token, reject expired/used token, and verify new password works while old password fails.

### Tests for User Story 2

- [ ] T022 [P] [US2] Add reset-request behavior tests (generic response for unknown account) in `tests/Feature/Auth/PasswordResetRequestTest.php`
- [ ] T023 [P] [US2] Add reset completion tests in `tests/Feature/Auth/PasswordResetCompleteTest.php`
- [ ] T024 [P] [US2] Add token lifecycle tests (single-use/expiry) in `tests/Feature/Auth/PasswordResetTokenLifecycleTest.php`

### Implementation for User Story 2

- [ ] T025 [US2] Implement reset request Livewire page in `app/Livewire/Auth/PasswordResetRequestPage.php`
- [ ] T026 [P] [US2] Implement reset request view in `resources/views/livewire/auth/password-reset-request-page.blade.php`
- [ ] T027 [US2] Implement reset completion Livewire page in `app/Livewire/Auth/PasswordResetCompletePage.php`
- [ ] T028 [P] [US2] Implement reset completion view in `resources/views/livewire/auth/password-reset-complete-page.blade.php`
- [ ] T029 [US2] Implement token issuance/verification/invalidation in `app/Services/Auth/PasswordResetService.php`
- [ ] T030 [US2] Add password reset routes and token handling in `routes/web.php`

**Checkpoint**: US2 is independently functional and testable.

---

## Phase 5: User Story 3 - Authorized Initial Dashboard (Priority: P2)

**Goal**: Authenticated users can reach dashboard; unauthenticated users are redirected to sign-in.

**Independent Test**: Verify signed-out request redirects to sign-in and signed-in request renders dashboard with user context.

### Tests for User Story 3

- [ ] T031 [P] [US3] Add dashboard authorization tests in `tests/Feature/Auth/DashboardAccessTest.php`
- [ ] T032 [P] [US3] Add dashboard render/content tests in `tests/Feature/Auth/DashboardRenderTest.php`

### Implementation for User Story 3

- [ ] T033 [US3] Implement dashboard Livewire page in `app/Livewire/Dashboard/InitialDashboardPage.php`
- [ ] T034 [P] [US3] Implement dashboard view in `resources/views/livewire/dashboard/initial-dashboard-page.blade.php`
- [ ] T035 [US3] Add protected dashboard route rules in `routes/web.php`

**Checkpoint**: US3 is independently functional and testable.

---

## Phase 6: User Story 4 - Identity Data and Admin Bootstrap (Priority: P2)

**Goal**: Operators can initialize identity storage and provision users through deploy scripts.

**Independent Test**: Run migration/bootstrap and add-user script in clean env; new account authenticates; duplicate or invalid input fails safely.

### Tests for User Story 4

- [ ] T036 [P] [US4] Add provisioning command validation tests in `tests/Feature/Auth/UserProvisioningCommandTest.php`
- [ ] T037 [P] [US4] Add duplicate/weak-credential provisioning tests in `tests/Feature/Auth/UserProvisioningValidationTest.php`
- [ ] T038 [P] [US4] Add identity migration idempotency tests in `tests/Feature/Auth/AuthMigrationIdempotencyTest.php`

### Implementation for User Story 4

- [ ] T039 [US4] Implement add-user artisan command in `app/Console/Commands/AddAuthUserCommand.php`
- [ ] T040 [US4] Register add-user command in `routes/console.php`
- [ ] T041 [P] [US4] Add local-dev provisioning script in `deploy/scripts/local-dev/add-user.sh`
- [ ] T042 [P] [US4] Add staging provisioning script in `deploy/scripts/staging/add-user.sh`
- [ ] T043 [P] [US4] Add production provisioning script in `deploy/scripts/production/add-user.sh`
- [ ] T044 [US4] Add provisioning workflow details in `specs/002-add-auth-foundation/quickstart.md`

**Checkpoint**: US4 is independently functional and testable.

---

## Phase 7: Polish & Cross-Cutting Concerns

**Purpose**: Validate measurable outcomes and complete hardening/docs.

- [ ] T045 [P] Add auth operational/security notes in `README.md` and `docs/README.md`
- [ ] T046 Run full auth test suite in `tests/Feature/Auth/`
- [ ] T047 Validate quickstart end-to-end in `specs/002-add-auth-foundation/quickstart.md`
- [ ] T048 Validate SC-001 timing target with scripted measurement in `tests/Feature/Auth/SignInPerformanceTest.php`
- [ ] T049 Validate SC-005 end-to-end provisioning-to-sign-in path in `tests/Feature/Auth/ProvisioningEndToEndTest.php`
- [ ] T050 [P] Update cross-artifact references in `specs/002-add-auth-foundation/plan.md` and `specs/002-add-auth-foundation/tasks.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Phase 1**: No dependencies.
- **Phase 2**: Depends on Phase 1; blocks all user stories.
- **Phases 3-6**: Depend on Phase 2 completion.
- **Phase 7**: Depends on completion of targeted user stories.

### User Story Dependencies

- **US1 (P1)**: Starts after foundational phase.
- **US2 (P1)**: Starts after foundational phase; independent of US1 except shared services.
- **US3 (P2)**: Depends on authenticated session behavior from US1.
- **US4 (P2)**: Depends on foundational entities/migrations; can run in parallel with US3.

### Within Each User Story

- Tests are written first and must fail before implementation.
- Data model/service changes precede route/page wiring.
- Routes/components/controllers precede final story validation.

---

## Parallel Execution Examples

### User Story 1

- T014, T015, and T016 can run in parallel (separate test files).
- T018 can run in parallel with T019 after T017 begins.

### User Story 2

- T022, T023, and T024 can run in parallel.
- T026 and T028 can run in parallel while T025 and T027 logic is developed.

### User Story 4

- T041, T042, and T043 can run in parallel (environment-specific scripts).
- T036 and T038 can run in parallel (different validation scopes).

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1 and Phase 2.
2. Deliver Phase 3 (US1).
3. Validate sign-in/sign-out/lockout independently.

### Incremental Delivery

1. Add US2 for reset recovery.
2. Add US3 for authenticated landing experience.
3. Add US4 for operational bootstrap.
4. Complete Phase 7 for measurable outcome validation and hardening.

### Parallel Team Strategy

1. Complete setup + foundational work together.
2. Split by story after foundational checkpoint:
   - Developer A: US1 + US3
   - Developer B: US2
   - Developer C: US4
3. Rejoin for phase-7 validations and final integration.

---

## Notes

- `[P]` tasks operate on independent files and can be executed concurrently.
- `[US#]` labels maintain traceability from tasks to user stories.
- This task list is ready for `/speckit.implement`.
