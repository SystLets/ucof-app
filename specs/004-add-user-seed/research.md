# Research: User Provisioning Script for Login

## Decision 1: Reuse existing PasswordPolicyRule directly from Artisan command

- **Decision**: Import and instantiate `App\Rules\PasswordPolicyRule` inside the Artisan command and run validation via `Validator::make()` before any database interaction.
- **Rationale**: Keeps password policy in one tested class; avoids duplication and policy drift between UI and CLI paths.
- **Alternatives considered**:
  - Inline policy checks in command: rejected due to duplication and drift risk.
  - Separate provisioning-only policy: rejected because the spec requires the same policy as login flows.

## Decision 2: Use App\Models\User directly — no new service class

- **Decision**: The Artisan command interacts with `App\Models\User` directly via Eloquent `where/first/forceFill/save`.
- **Rationale**: Provisioning is a single-path operation with a simple conditional (create vs reactivate). A dedicated service class adds indirection without testability or reuse benefit at this scope.
- **Alternatives considered**:
  - Dedicated `ProvisioningService` class: rejected because single-use logic wrapped in one extra layer adds no value here.

## Decision 3: gethostname() is sufficient for container hostname audit metadata

- **Decision**: Call `gethostname()` at command runtime to capture the container hostname and store it alongside `now()` in the user record's provisioning metadata.
- **Rationale**: Available in all PHP environments, stable across container runtimes, and satisfies FR-010 without requiring extra infrastructure.
- **Alternatives considered**:
  - `$_SERVER['HOSTNAME']`: less portable; not always set in all PHP SAPI modes.
  - Operator-supplied `--requester` argument: rejected per clarification (Q3).

## Decision 4: Active duplicate rejected; inactive/disabled reactivated and password updated

- **Decision**: Query by email. If active account found: abort with safe error. If inactive/disabled found: `forceFill(['status' => 'active', 'password' => hash, ...])` and save. If no account: create new.
- **Rationale**: Directly implements clarification Q1 answer. Prevents identity collision while enabling recovery provisioning.
- **Alternatives considered**:
  - Always-reject-on-duplicate: rejected per clarification.
  - Prompt for confirmation before reactivation: rejected because spec requires CLI-arguments-only (clarification Q2).

## Decision 5: Wrapper scripts invoke docker compose exec — no app-level env awareness

- **Decision**: Each `deploy/scripts/<env>/add-user.sh` wrapper executes `docker compose exec app php artisan ucof:provision-user --name="$1" --email="$2" --password="$3"` with positional arguments.
- **Rationale**: Keeps scripts trivially thin, environment differences are handled by Docker Compose service configuration, not script logic.
- **Alternatives considered**:
  - Scripts set env vars before running artisan: rejected because env is already managed by compose.
  - Scripts accept named flags: positional arguments are simpler for wrapper shells.
