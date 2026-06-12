# Contract: User Provisioning Command Interface

## Purpose

Define the externally visible interface for the `ucof:provision-user` Artisan command and its per-environment shell wrapper scripts.

## Artisan Command Contract

### Command Signature

```
php artisan ucof:provision-user --name=<name> --email=<email> --password=<password>
```

### Arguments

| Argument | Type | Required | Description |
|----------|------|----------|-------------|
| `--name` | string | yes | Full display name for the user account |
| `--email` | string | yes | Login email address (must be unique among active accounts) |
| `--password` | string | yes | Initial password (must satisfy password policy) |

### Behaviour

- **New user**: Creates an active account with provided inputs and records `provisioned_at` / `provisioned_from`.
- **Inactive/disabled user (same email)**: Reactivates account, updates password and provisioning metadata.
- **Active user (same email)**: Exits with code `1` and a safe duplicate identity message.
- **Invalid inputs**: Exits with code `1` and a safe validation message; no database writes occur.

### Exit Codes

| Code | Meaning |
|------|---------|
| `0` | Provisioning succeeded (created or reactivated) |
| `1` | Provisioning failed (duplicate active account, validation error, or DB error) |

### Output Semantics

- Success output written via `$this->info()`.
- Failure output written via `$this->error()`.
- Supplied password MUST NOT appear in any output line.
- No internal stack traces or MongoDB error details exposed to caller.

## Shell Wrapper Script Contract

### Script Location

- `deploy/scripts/local-dev/add-user.sh`
- `deploy/scripts/staging/add-user.sh`
- `deploy/scripts/production/add-user.sh`

### Invocation

```bash
sh deploy/scripts/<env>/add-user.sh "<name>" "<email>" "<password>"
```

### Wrapper Responsibilities

- Accept three positional arguments: name, email, password.
- Validate that all three arguments are provided; print usage and exit `1` if missing.
- Delegate execution to `docker compose exec app php artisan ucof:provision-user` with arguments forwarded.
- Propagate exit code from Artisan command to the calling shell.

### Wrapper Must Not

- Print the password in any log, echo, or error output.
- Contain provisioning business logic — all logic lives in the Artisan command.

## Testable Contract Assertions

- Command exits `0` when creating a new user with valid inputs.
- Command exits `0` when reactivating an inactive/disabled user.
- Command exits `1` when active duplicate email is supplied.
- Command exits `1` when password fails policy.
- Command exits `1` when required argument is missing.
- No output line from the command contains the supplied password string.
