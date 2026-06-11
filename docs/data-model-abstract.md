# Data Modeling - High Level

## Consolidated Data Model

### 1. Standard, Requirement, and Mapping
*   **Description:** This entity set forms the "Ontology" of the system. A **Standard** represents a specific framework (e.g., ISO 27001:2022). Each Standard contains multiple **Requirements** (clauses or controls from the norm). The **Mapping** entity is a many-to-many relationship that links Requirements to the organization's internal **Controls**. This allows one internal process to satisfy multiple external obligations simultaneously.
*   **Actors:** Implementer (Accesses), Auditor (Verifies), Executive (Monitors).
*   **Data Entities:** Standard, Requirement, Mapping (Created/Accessed).

### 2. Control and Task
*   **Description:** The **Control** is the central operational entity. It contains the description of the practice, its owner, and its current implementation status. To support the Implementer's workflow, a Control can be broken down into multiple **Tasks**. Tasks track granular actions, deadlines, and dependencies required to move a control from "In Progress" to "Implemented."
*   **Actors:** Implementer (Creates/Modifies), Auditor (Tests), Executive (Monitors Coverage).
*   **Data Entities:** Control, Task (Created/Modified).

### 3. Evidence and Artifact
*   **Description:** **Evidence** acts as the proof of a control's operation. This entity stores metadata such as collection date, validity period, and source. It points to an **Artifact** (a file upload or a URL). Evidence is linked to a Control through a specific "Evidence Request" or a recurring "Freshness" schedule. It is designed to be reusable across multiple Controls if the proof is applicable to different requirements.
*   **Actors:** Implementer (Creates/Uploads), Auditor (Validates), Executive (Reviews Health).
*   **Data Entities:** Evidence, Artifact (Created/Modified).

### 4. Audit, Finding, and Rationale
*   **Description:** An **Audit** entity defines a verification event with a specific scope and timeline. During an audit, the Auditor generates **Findings** (or Nonconformities) linked to specific Controls. Each Finding must include an **Audit Rationale**, a text entity where the Auditor documents their professional judgment and the sampling logic used to reach a conclusion.
*   **Actors:** Auditor (Creates/Modified), Implementer (Accesses for Remediation), Executive (Reviews Outcomes).
*   **Data Entities:** Audit, Finding, Audit Rationale (Created/Modified).

### 5. Remediation Action and Risk
*   **Description:** **Remediation Actions** are the corrective steps taken to close a Finding. They link back to the original Finding and the affected Control. The **Risk** entity represents the threat being mitigated. Controls are linked to Risks to show "Treatment." When a Remediation Action is completed, it triggers a reassessment of the **Residual Risk** associated with that domain.
*   **Actors:** Implementer (Executes), Auditor (Verifies), Executive (Prioritizes Investment).
*   **Data Entities:** Remediation Action (Created/Modified), Risk (Accessed/Modified).

### 6. Exception and Decision
*   **Description:** The **Exception** entity handles formal deviations from established Controls. It includes the justification for the exception and the proposed "Compensating Controls." The **Decision** entity records the Executive's approval or rejection, including a mandatory rationale field to ensure accountability for risk acceptance.
*   **Actors:** Implementer (Requests), Executive (Approves/Rejects), Auditor (Reviews for Compliance).
*   **Data Entities:** Exception Request, Decision, Decision Rationale (Created/Modified).

---

## Packages

* **Norm_Definition**: Contains the static "Pack" data (Standards and Requirements) that serves as the foundation for compliance.
* **Implementation_Data**: Houses the operational records, including how requirements are mapped to controls, task tracking, and evidence artifacts.
* **Audit_Data**: Stores the results of verification cycles, including findings and the specific rationale provided by the auditor.
* **Executive_Data**: Dedicated to the high-level decision-making process, specifically for risk acceptance and exception management.


