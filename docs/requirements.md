# Requirements


This document lists the requirements for the UCOF app. It is intended to be a living document that will be updated as the project progresses.


## Functional Requirements

### Implementer Requirements: Operational Foundation

#### 1. Obligation and Control Management (UC1, UC2, UC3)
*   **Description:** The system must provide a centralized repository where the Implementer can browse and search for obligations (standards, laws, or internal policies) assigned to their business unit. For each obligation, the user must be able to view the specific requirements and the controls designed to satisfy them. This includes a "Definition of Done" to clarify implementation expectations.
*   **Actor:** Implementer.
*   **Data Entities:** Obligation, Requirement, Standard (Accessed); Control (Accessed/Modified).

#### 2. Implementation Execution (UC4, UC5)
*   **Description:** The system shall allow the Implementer to break down a control implementation into discrete tasks. The user must be able to update the progress of these tasks and transition the overall Control Status (e.g., from "In Progress" to "Implemented"). The "Update Control Status" (UC5) is automatically triggered or manually confirmed upon completion of implementation tasks.
*   **Actor:** Implementer.
*   **Data Entities:** Control, Task (Created/Modified).

#### 3. Evidence Management (UC6, UC7, UC8, UC9)
*   **Description:** The system must provide an "Evidence Vault" where the Implementer can upload files or register links to external artifacts. Each piece of evidence must be explicitly linked to one or more Controls (UC7). The system must track "Evidence Freshness" (UC8) by monitoring expiration dates and validity periods, alerting the user when a refresh is required. Additionally, the user must have a workflow to receive and respond to specific evidence requests (UC9) from auditors.
*   **Actor:** Implementer.
*   **Data Entities:** Evidence (Created/Modified), Control (Modified), Evidence Request (Accessed).

#### 4. Assessment and Remediation (UC10, UC11)
*   **Description:** Before formal audits, the system shall allow the Implementer to perform a "Self-Assessment" (UC10) using checklists to flag gaps. If gaps or findings are identified (either by the user or an auditor), the Implementer must be able to create and manage "Remediation Actions" (UC11), defining corrective steps, owners, and deadlines.
*   **Actor:** Implementer.
*   **Data Entities:** Self-Assessment (Created), Remediation Action (Created/Modified), Finding (Accessed).

#### 5. Risk and Change Management (UC12, UC13, UC14, UC15)
*   **Description:** The Implementer must be able to link controls to specific risks to demonstrate mitigation. When a control is implemented, the system should allow for the reassessment of "Residual Risk" (UC13). Furthermore, the system must trigger a "Change Impact" workflow (UC14) when a process or technology changes, forcing a review of all affected controls (UC15).
*   **Actor:** Implementer.
*   **Data Entities:** Risk, Control (Modified); Change Event (Accessed).

#### 6. Audit Readiness and Efficiency (UC16, UC17, UC18, UC19, UC20, UC21)
*   **Description:** The system must provide a "Readiness Dashboard" (UC16/17) to identify gaps before an audit. To ensure efficiency, the system must support the "Reuse" of controls and evidence across multiple standards (UC18/19), so a single implementation can satisfy ISO, NIST, and other frameworks simultaneously. Finally, collaboration tools must allow for escalating blockers (UC21) to management.
*   **Actor:** Implementer.
*   **Data Entities:** Control, Evidence, Standard (Modified/Linked); Escalation (Created).

---

### Auditor Requirements: Verification and Findings

#### 1. Audit Planning and Scoping (UC1, UC2)
*   **Description:** The system must allow the Auditor to define the boundaries of an audit by selecting specific entities, processes, or standards. The Auditor must be able to build a structured Audit Plan that outlines the focus areas, sampling logic, and the timeline for the review.
*   **Actor:** Auditor.
*   **Data Entities:** Audit Plan, Audit Scope (Created/Modified); Standard, Control (Accessed).

#### 2. Design and Mapping Verification (UC3, UC7)
*   **Description:** The Auditor requires tools to verify the "Requirement-to-Control Mapping." The system must highlight any requirements not covered by controls. Additionally, the Auditor must be able to perform a "Control Design Test" to evaluate if a control, as defined, is theoretically capable of meeting the requirement before testing its actual operation.
*   **Actor:** Auditor.
*   **Data Entities:** Control, Requirement, Mapping (Accessed); Design Assessment (Created).

#### 3. Evidence Validation and Operational Testing (UC4, UC5, UC6, UC8)
*   **Description:** The system must provide a "Traceability View" to examine the entire chain from requirement to evidence. The Auditor must be able to validate evidence quality—accepting, rejecting, or requesting additional artifacts (UC6). Finally, the Auditor must record the results of "Operational Testing" (UC8) to confirm the control is functioning in practice, not just on paper.
*   **Actor:** Auditor.
*   **Data Entities:** Evidence, Evidence Request (Modified); Control, Activity Log (Accessed).

#### 4. Findings, Nonconformities, and Rationale (UC9, UC10, UC11, UC22)
*   **Description:** When a gap is identified, the Auditor must be able to register a "Finding" or "Nonconformity." Each finding must be linked to a severity classification (UC11) and the specific requirement it violates. Crucially, the system must provide a field for the "Audit Rationale" (UC22) to document the professional judgment and logic behind the conclusion.
*   **Actor:** Auditor.
*   **Data Entities:** Finding, Nonconformity (Created/Modified); Audit Rationale (Created); Requirement, Control (Accessed).

#### 5. Remediation Oversight (UC12, UC13, UC14, UC15)
*   **Description:** The Auditor must be able to review the remediation actions proposed by the Implementer. The system shall allow the Auditor to verify the effectiveness of these fixes (UC13) before formally closing an issue. If a fix is insufficient, the Auditor must have the authority to "Reopen" the issue (UC15) for further work.
*   **Actor:** Auditor.
*   **Data Entities:** Remediation Action (Modified); Finding (Modified); Evidence (Accessed).

#### 6. Governance Review and Reporting (UC16, UC17, UC18, UC19, UC20, UC21)
*   **Description:** The system must enable the Auditor to perform cross-framework audits (UC18) and assess the overall "Risk Governance" (UC19). The Auditor must be able to generate comprehensive "Audit Outputs" (UC20) and a high-level "Audit Summary" (UC21) for management review, ensuring verified data flows into executive reports.
*   **Actor:** Auditor.
*   **Data Entities:** Audit Report, Audit Summary (Created); Posture Data, Risk Assessment (Accessed).

---

### Executive Requirements: Posture and Decision Making

#### 1. Compliance Posture and Coverage Monitoring (UC1, UC2, UC3, UC6)
*   **Description:** The system must provide high-level dashboards showing the "Overall Compliance Posture" through simplified scoring (e.g., Red/Yellow/Green). Executives must be able to toggle these views by Entity, Framework, or Domain. Additionally, the system must visualize "Control Coverage" (UC6) to highlight which percentage of the organization’s obligations are currently implemented or verified.
*   **Actor:** Executive.
*   **Data Entities:** Posture Score, Control Status (Accessed); Entity, Framework (Accessed).

#### 2. Risk Exposure and Strategic Initiatives (UC4, UC5, UC9)
*   **Description:** The system shall allow Executives to monitor the organization's "Risk Exposure," specifically focusing on "Top Risks" and untreated vulnerabilities. It must also provide a tracking module for "Strategic Compliance Initiatives" (UC9), such as a new ISO certification project, showing progress against major milestones and funding needs.
*   **Actor:** Executive.
*   **Data Entities:** Risk, Residual Risk (Accessed); Strategic Initiative, Milestone (Accessed).

#### 3. Exception Management and Decision Rationale (UC11, UC12, UC13)
*   **Description:** The system must provide a workflow for reviewing "Exceptions and Decisions." When a business unit cannot meet a requirement, the Executive must be able to Approve (UC12) or Reject (UC13) the risk acceptance request. The system must mandate that a rationale be recorded for every executive decision to maintain a formal governance record.
*   **Actor:** Executive.
*   **Data Entities:** Exception Request (Modified); Decision Rationale (Created); Risk (Accessed).

#### 4. Audit Outcomes and Remediation Prioritization (UC7, UC8, UC14)
*   **Description:** Executives require a summarized view of "Audit Outcomes" and "Open Findings." Based on this data, the system must allow the Executive to "Prioritize Remediation Investment" (UC14), allocating budget or resources to the gaps that represent the highest material risk to the business.
*   **Actor:** Executive.
*   **Data Entities:** Finding, Audit Summary (Accessed); Remediation Budget/Priority (Modified).

#### 5. Benchmarking and Trend Analysis (UC16, UC17, UC18)
*   **Description:** The system shall provide benchmarking tools to compare the compliance posture of different Business Units or Products (UC16/17). Furthermore, it must generate "Trend Lines" (UC18) to show whether the organization’s governance performance is improving or decaying over time, allowing for data-driven management interventions.
*   **Actor:** Executive.
*   **Data Entities:** Posture Data, Trend Metric (Accessed); Business Unit, Product (Accessed).

#### 6. Accountability and Business Alignment (UC15, UC19, UC21, UC22)
*   **Description:** The system must visualize "Accountability" (UC19) by showing ownership distribution across controls and risks. It should help the Executive evaluate "Certification Readiness" (UC15) and align compliance data with business priorities (UC21), ensuring that governance performance (UC22) enables rather than constrains business growth.
*   **Actor:** Executive.
*   **Data Entities:** Owner, Governance Metric (Accessed); Business Priority (Accessed/Linked).

---
# Flows


## Implementer Flow: From Obligation to Evidence

The Implementer's journey begins with **Scoping**, moves into **Technical Execution and Mapping**, and concludes with **Evidence Collection and Maintenance**. This ensures that the technical state of the server is always synchronized with the compliance state in the software.

### Phase 1: Setup and Scoping
1.  **Obligation Discovery (Outside):** Meet with legal and compliance teams to identify which regulations (ISO 27001, GDPR, etc.) apply to the current server environment.
2.  **Norm Ingestion (Inside):** Add the identified standards into the system, creating the baseline of requirements.
3.  **Initial Scoping (Inside - UC1):** Browse the ingested requirements to understand the specific obligations assigned to the technical scope.
4.  **Control Inventory (Outside):** Identify existing technical and administrative practices already in place on the server (e.g., existing backup routines, firewall rules).

### Phase 2: Implementation and Mapping
5.  **Control Registry (Inside - UC2):** Register the identified practices as "Controls" within the platform, assigning owners and descriptions.
6.  **Gap Mapping (Inside - UC18):** Link the requirements of the new norm to the registered controls. Identify requirements that currently have no mapped controls (Gaps).
7.  **Work Planning (Inside - UC3):** For every identified gap, create a plan and break it down into actionable tasks with deadlines.
8.  **Technical Execution (Outside - UC4):** Perform the actual technical work on the server (e.g., configuring MFA, hardening the OS, setting up encryption).
9.  **Status Synchronization (Inside - UC5):** Update the status of the tasks and controls as the technical implementation advances.

### Phase 3: Evidence and Validation
10. **Evidence Generation (Outside - UC6):** Generate proof of the implementation, such as exporting configuration logs, taking screenshots of security settings, or saving policy PDFs.
11. **Evidence Attachment (Inside - UC7):** Upload the generated artifacts to the "Evidence Vault" and link them to the corresponding controls.
12. **Self-Assessment (Inside - UC10):** Run through internal checklists to verify if the implementation truly meets the "Definition of Done" before an external review.

### Phase 4: Maintenance and Readiness
13. **Change Monitoring (Outside - UC14):** Monitor the server for any changes in technology or process that might affect the control's effectiveness.
14. **Evidence Refresh (Inside - UC8):** Periodically replace aging artifacts with fresh evidence to ensure the system is always "Audit-Ready."
15. **Pre-Audit Review (Inside - UC16):** Use the readiness dashboard to close any remaining documentation gaps before the Auditor begins their cycle.


## Auditor Flow: From Audit Planning to Findings

The requirements for the **Auditor** role are centered on the principles of **verification, traceability, and independence**. While the Implementer focuses on making the controls work, the Auditor focuses on proving that they work as intended and documenting the professional judgment used to reach that conclusion.

The Auditor requirements focus on **Verification and Accountability**. The primary data entities involved are `Audit`, `Finding`, `Audit_Rationale`, and `Remediation_Action`. These ensure that every compliance claim is backed by a verified evidence chain and a defensible auditor conclusion.


### 1. Audit Planning and Scoping (UC1, UC2)
*   **Description:** The system must allow the Auditor to define the boundaries of a specific verification event. This includes selecting the entities, business processes, or specific standards (Norms) to be reviewed. The Auditor must be able to generate a structured Audit Plan that outlines focus areas, the timeline, and the sampling logic to be applied during the review.
*   **Actor:** Auditor.
*   **Data Entities:** Audit (Created/Modified); Standard, Requirement, Control (Accessed).

### 2. Mapping and Design Verification (UC3, UC7)
*   **Description:** Before testing operational data, the system shall provide tools for the Auditor to verify the "Requirement-to-Control Mapping." The Auditor must confirm that the internal controls are logically sufficient to satisfy the external requirements. This includes performing a "Control Design Test" (UC7) to evaluate if the control, as defined, is theoretically capable of meeting the objective.
*   **Actor:** Auditor.
*   **Data Entities:** Mapping, Requirement, Control (Accessed).

### 3. Evidence Examination and Operational Testing (UC4, UC5, UC6, UC8)
*   **Description:** The system must provide a "Traceability View" that allows the Auditor to follow the chain from a Requirement to its mapped Control and finally to the attached Evidence. The Auditor must be able to validate the quality of the evidence (UC5)—accepting it, rejecting it with comments, or requesting additional proof (UC6). Finally, the Auditor must record the results of "Operational Testing" (UC8) to confirm the control is functioning in practice over a period of time.
*   **Actor:** Auditor.
*   **Data Entities:** Evidence, Artifact (Accessed); Finding (Created); Evidence Request (Created/Modified).

### 4. Findings, Nonconformities, and Rationale (UC9, UC10, UC11, UC22)
*   **Description:** When a gap is identified, the system must allow the Auditor to register a "Finding" or "Nonconformity." Each finding must be linked to a severity level (UC11) and the specific requirement it violates. Crucially, the system must provide a dedicated "Audit Rationale" (UC22) field where the Auditor documents the professional logic, sampling details, and judgment used to reach the conclusion, ensuring the audit is defensible.
*   **Actor:** Auditor.
*   **Data Entities:** Finding (Created/Modified); Audit_Rationale (Created); Control, Requirement (Accessed).

### 5. Remediation Oversight (UC12, UC13, UC14, UC15)
*   **Description:** The Auditor must be able to review the "Remediation Actions" proposed by the Implementer to fix findings. The system shall allow the Auditor to track the progress of these actions and formally verify their effectiveness (UC13) before closing the finding. If the remediation is insufficient, the Auditor must have the ability to "Reopen" the issue (UC15).
*   **Actor:** Auditor.
*   **Data Entities:** Remediation_Action (Accessed/Modified); Finding (Modified).

### 6. Reporting and Governance Review (UC16, UC17, UC18, UC19, UC20, UC21)
*   **Description:** The system must enable the Auditor to perform cross-framework audits (UC18) and assess the overall "Risk Governance" (UC19). The Auditor must be able to generate comprehensive "Audit Outputs" (UC20) and a high-level "Audit Summary" (UC21) that aggregates verified conclusions for executive consumption.
*   **Actor:** Auditor.
*   **Data Entities:** Audit (Modified); Finding, Control (Accessed).


## Executive Flow: From Posture to Prioritization

The chronological sequence for an **Executive** focuses on establishing governance, monitoring the implementation progress, and making high-level risk decisions based on the data provided by Implementers and Auditors.

The Executive journey moves from **Setting Strategy** to **Monitoring Progress**, **Managing Exceptions**, and finally **Evaluating Performance**. This ensures the Executive remains a decision-maker rather than a task-manager.

### Phase 1: Governance Setup & Risk Appetite
1.  **Strategic Alignment (Outside):** Define the business priorities and which certifications (e.g., ISO 27001) are required for market growth.
2.  **Risk Tolerance Definition (Inside - UC4/UC21):** Set the thresholds for risk exposure and inherent risk scores within the platform to align with business priorities.
3.  **Accountability Assignment (Inside - UC19):** Assign high-level owners to the primary control families and risk domains.

### Phase 2: Monitoring & Resource Allocation
4.  **Posture Monitoring (Inside - UC1/UC6):** Review initial dashboards to identify "Red" areas where control coverage is low or implementation has not started.
5.  **Strategic Initiative Tracking (Inside - UC9):** Monitor the progress of the compliance program against major project milestones and deadlines.
6.  **Investment Prioritization (Outside/Inside - UC14):** Based on the gaps identified, decide where to allocate budget or headcount to accelerate remediation.

### Phase 3: Decision Making & Exception Handling
7.  **Exception Review (Inside - UC11):** Receive formal requests for risk acceptance or control deviations from the Implementers.
8.  **Executive Approval/Rejection (Inside - UC12/UC13):** Formally approve or reject exceptions, providing a **Decision Rationale** for accountability.
9.  **Audit Outcome Review (Inside - UC7/UC8):** Review the summaries of findings and nonconformities provided by the Auditors after a verification cycle.

### Phase 4: Performance Review & Certification
10. **Benchmarking (Inside - UC16/UC17):** Compare the performance of different business units or products to identify systemic laggards or best practices.
11. **Trend Analysis (Inside - UC18):** Review historical trend lines to determine if the organization's security posture is improving over time.
12. **Readiness Evaluation (Inside - UC15):** Make the final "Go/No-Go" decision for formal certification or board reporting based on the verified readiness data.
13. **Governance Performance Review (Inside - UC22):** Conduct a final review of the GRC program's effectiveness and its impact on business enablement.

