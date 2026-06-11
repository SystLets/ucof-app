# Data Model: Laravel Docker Foundation

This epic defines the operational foundation for the system. The data model is limited to deployment and runtime support entities rather than business-domain records.

## Entities

### DeploymentEnvironment

- **Purpose**: Represents a named runtime target such as local-dev, staging, or production.
- **Fields**:
  - `name`
  - `description`
  - `compose_profile_or_override`
  - `deployment_spec_path`
  - `deployment_script_path`
- **Relationships**:
  - Has many `Service` records.
  - Has many `DeploymentAsset` records.
- **Validation Rules**:
  - `name` must be unique and one of `local-dev`, `staging`, or `production`.
  - Each environment must declare its associated specs and scripts.

### Service

- **Purpose**: Represents a runtime component in the stack.
- **Fields**:
  - `name`
  - `image`
  - `port`
  - `healthcheck_path_or_command`
  - `is_required`
  - `is_dev_only`
- **Relationships**:
  - Belongs to one `DeploymentEnvironment`.
  - Can depend on other services.
- **Validation Rules**:
  - Required services for the foundation are `webapp`, `nginx`, and `mongodb`.
  - The local-dev environment must include a MongoDB web client service (or equivalent override/profile entry) exposed by host port.

### DeploymentAsset

- **Purpose**: Represents a deployment spec file or script used to manage an environment.
- **Fields**:
  - `name`
  - `type` (spec or script)
  - `path`
  - `environment_name`
- **Relationships**:
  - Belongs to one `DeploymentEnvironment`.
- **Validation Rules**:
  - Every supported environment must have at least one spec and one script asset.
  - Asset paths must be stored separately per environment.

### Healthcheck

- **Purpose**: Represents a runtime readiness or liveness signal for a service.
- **Fields**:
  - `service_name`
  - `type` (readiness/liveness)
  - `endpoint_or_command`
  - `interval`
  - `timeout`
  - `retries`
- **Relationships**:
  - Belongs to one `Service`.
- **Validation Rules**:
  - Every required service must expose at least one healthcheck.
  - Healthchecks must fail clearly when dependent services are unavailable.

## Environment Relationships

- `local-dev` includes `webapp`, `nginx`, `mongodb`, and a MongoDB web client service or equivalent override/profile.
- `staging` includes `webapp`, `nginx`, and `mongodb` but excludes dev-only tooling.
- `production` includes `webapp`, `nginx`, and `mongodb` with the same public contract as staging, minus dev-only tooling.

## Runtime Rules

- The webapp must be reachable through nginx rather than directly exposing PHP-FPM.
- Healthchecks must be observable from container tooling.
- Deployment assets must preserve consistent service naming across environments.
- The local-dev MongoDB web client must be optional outside development.
