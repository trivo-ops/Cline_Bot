---
description: "Generates a detailed implement_plan.md based on an APPROVED task_prompt.md."
author: "Cline"
version: "1.0.0"
tags: ["planning", "prompt", "phase-2-planning"]
globs: ["docs/tasks/**"]
---

<task name="prompt-to-plan">

<task_objective>
Generates a DETAILED implement_plan.md based on an APPROVED task_prompt.md.
</task_objective>

<detailed_sequence_steps>

## GATE — Pre-check for APPROVED task_prompt.md

1.  Read `docs/tasks/<TASK>/task_prompt.md`.
2.  Verify that it contains the exact line `## STATUS: APPROVED`.
3.  If not, **STOP** and instruct the user:
    > "Cannot proceed. `docs/tasks/<TASK>/task_prompt.md` is not yet APPROVED. Please review and change `## STATUS: DRAFT` to `## STATUS: APPROVED`."

---

## Step 1 — Ensure folder structure

1.  Confirm `docs/tasks/_TEMPLATE/` exists with files:
    *   `task_description.md`
    *   `task_prompt.md`
    *   `implement_plan.md`
2.  Create `docs/tasks/<TASK>/` if it does not exist.
3.  Create the following files (empty) if missing:
    *   `docs/tasks/<TASK>/task_description.md`
    *   `docs/tasks/<TASK>/task_prompt.md`
    *   `docs/tasks/<TASK>/implement_plan.md`
    *   `docs/tasks/<TASK>/pr_description.md`

---

## Step 2 — Write implement_plan.md (DETAILED)

Populate `docs/tasks/<TASK>/implement_plan.md` with the following structure, ensuring all sections are filled in with detailed plans based on the `task_prompt.md`:

```
# Implement Plan: <TASK>
## STATUS: DRAFT

## Summary
A concise summary of the task and the proposed solution.

## Files to Create/Modify
List all files that will be created or modified, with their exact paths.
- D:/projects/demo-cline/cline-workflow-demo/src/Controller/Api/<TASK_UPPERCASE>Controller.php
- D:/projects/demo-cline/cline-workflow-demo/src/Model/Table/<TASK_UPPERCASE>Table.php
- D:/projects/demo-cline/cline-workflow-demo/src/Model/Entity/<TASK_SINGULAR>Entity.php
- D:/projects/demo-cline/cline-workflow-demo/tests/TestCase/Controller/Api/<TASK_UPPERCASE>ControllerTest.php

## Ordered Implementation Steps
A step-by-step guide to implement the task, in logical order.

1.  **Create <TASK_SINGULAR>Entity.php:** Define the entity class for `<TASK_SINGULAR>`.
2.  **Create <TASK_UPPERCASE>Table.php:** Define the table class for `<TASK_UPPERCASE>`.
3.  **Create <TASK_UPPERCASE>Controller.php:** Implement the API controller for `<TASK_UPPERCASE>` with CRUD operations.
4.  **Implement CRUD actions:** Add `index`, `view`, `add`, `edit`, `delete` methods to `<TASK_UPPERCASE>Controller`.
5.  **Add routing:** Configure API routes in `config/routes.php` for the new `<TASK_UPPERCASE>` controller.
6.  **Create <TASK_UPPERCASE>ControllerTest.php:** Write comprehensive unit tests for the `<TASK_UPPERCASE>` API controller.
7.  **Run Tests:** Execute `vendor/bin/phpunit` to ensure all tests pass.

## Testing Strategy
Describe how the implementation will be tested, including unit, integration, and manual testing steps.

*   **Unit Tests:** Comprehensive unit tests for the controller actions and model logic.
*   **API Endpoint Testing:** Use `curl` or Postman to test each API endpoint (`GET`, `POST`, `PUT`, `DELETE`).
*   **Database Verification:** Manually verify data persistence in the database.

## Risks/Mitigations
Identify potential risks and proposed mitigation strategies.

*   **Risk: Data Validation Issues:**
    *   **Mitigation:** Implement robust validation rules in the entity and table classes.
*   **Risk: API Security Vulnerabilities:**
    *   **Mitigation:** Ensure proper authentication and authorization checks are in place for all API endpoints.
*   **Risk: Performance Bottlenecks:**
    *   **Mitigation:** Implement pagination for listing endpoints and optimize database queries.
```

---

## Step 3 — Write pr_description.md (skeleton)

Populate `docs/tasks/<TASK>/pr_description.md` with:

```
## What
## Why
## How
## Testing
## Notes/Risks
```

All sections as `_TBD_`.

---

## GATE — STOP HERE ⛔

**Do NOT proceed to implementation.**

Tell the user:
> "Phase 2 (planning) complete. Please review `docs/tasks/<TASK>/implement_plan.md`.
> When satisfied, change `## STATUS: DRAFT` to `## STATUS: APPROVED` and let me know."

</detailed_sequence_steps>

</task>
