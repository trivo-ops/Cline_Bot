---
description: "Phase 2 — Expand implement_plan.md and write all implementation code, then run tests."
author: "Cline"
version: "1.0.0"
tags: ["implementation", "coding", "phase-2"]
globs: ["docs/tasks/**", "src/**", "tests/**"]
---

<task name="plan-to-implement">

<task_objective>
Expand the DRAFT implement_plan.md into a full step-by-step plan, implement all required code changes following CakePHP 5 conventions, and run tests. Do NOT start until task_prompt.md is APPROVED.
</task_objective>

<detailed_sequence_steps>

## GATE CHECK — Before doing anything ⛔

1. Read `docs/tasks/<TASK>/task_prompt.md`.
2. Confirm it contains the exact line: `## STATUS: APPROVED`
3. If it still says `## STATUS: DRAFT` → **STOP immediately.**
   Tell the user:
   > "`task_prompt.md` is still DRAFT. Please change its STATUS to APPROVED before I can proceed."

---

## Step 1 — Expand implement_plan.md

Update `docs/tasks/<TASK>/implement_plan.md`:
- Keep `## STATUS: DRAFT` (do not change it yet — user must approve).
- Fill in all sections:
  - **Summary:** one-paragraph description of the approach.
  - **Files to change:** table with File | Action | Notes for every file to create or modify.
  - **Step-by-step plan:** numbered list of concrete implementation steps.
  - **Test plan:** list of test scenarios (happy path + error cases) and how to run them.

---

## GATE — STOP HERE ⛔

**Do NOT write any implementation code yet.**

Tell the user:
> "The full implement_plan.md is ready. Please review `docs/tasks/<TASK>/implement_plan.md`.
> When satisfied, change `## STATUS: DRAFT` to `## STATUS: APPROVED` and let me know."

Wait for the user to confirm APPROVED before continuing.

---

## Step 2 — Implement code (only after implement_plan.md is APPROVED)

Re-read `docs/tasks/<TASK>/implement_plan.md` and confirm `## STATUS: APPROVED`.

Execute each step in the plan in order:

### 2a. Database / schema
- Create or update `tests/schema.sql` with the new table DDL.
- If a migration system is in use, create the migration file instead.

### 2b. Model layer
- Create `src/Model/Entity/<Entity>.php` with `$_accessible` fields.
- Create `src/Model/Table/<Table>Table.php` with:
  - `initialize()` — behaviours, associations.
  - `validationDefault()` — all field validation rules.
  - `buildRules()` — uniqueness / FK rules if needed.

### 2c. Controller
- Create `src/Controller/Api/<Name>Controller.php` extending `AppController`.
- Implement actions: `index`, `view`, `add`, `edit`, `delete`.
- Each action must:
  - Return JSON using the standard envelope `{ success, data, message, errors }`.
  - Set the correct HTTP status code.
  - Guard against unauthenticated / unauthorized access (401 / 403).

### 2d. Routes
- Update `config/routes.php` to register the new API resource routes under `/api` prefix.

### 2e. Test fixture
- Create `tests/Fixture/<Name>Fixture.php` with representative seed records.

### 2f. Integration tests
- Create `tests/TestCase/Controller/Api/<Name>ControllerTest.php`.
- Cover: happy paths for all 5 endpoints, validation failures, 404s, 401/403.

---

## Step 3 — Run tests

```bash
vendor/bin/phpunit
# or
docker compose exec app vendor/bin/phpunit
```

- Report the result (pass/fail counts and any failures).
- Fix any failures before proceeding.
- **Never proceed to Phase 3 if tests are failing.**

---

## GATE — STOP HERE ⛔

Tell the user:
> "All tests pass. Ready for Phase 3 (commit-and-pr). Please confirm to proceed."

</detailed_sequence_steps>

</task>
