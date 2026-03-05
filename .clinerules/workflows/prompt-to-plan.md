---
description: "Phase 1 — Normalize a raw ticket into task_prompt.md and create a DRAFT implement_plan.md."
author: "Cline"
version: "1.0.0"
tags: ["planning", "prompt", "phase-1"]
globs: ["docs/tasks/**"]
---

<task name="prompt-to-plan">

<task_objective>
Transform a raw ticket or task description into a normalized, structured task_prompt.md (DRAFT) and a skeleton implement_plan.md (DRAFT). Do NOT write any implementation code during this phase.
</task_objective>

<detailed_sequence_steps>

## Step 1 — Ensure folder structure

1. Confirm `docs/tasks/_TEMPLATE/` exists with files:
   - `task_description.md`
   - `task_prompt.md`
   - `implement_plan.md`
2. Create `docs/tasks/<TASK>/` if it does not exist.
3. Create the following files (empty) if missing:
   - `docs/tasks/<TASK>/task_description.md`
   - `docs/tasks/<TASK>/task_prompt.md`
   - `docs/tasks/<TASK>/implement_plan.md`
   - `docs/tasks/<TASK>/pr_description.md`

---

## Step 2 — Write task_description.md

Populate `docs/tasks/<TASK>/task_description.md` with:
- **Ticket (verbatim):** paste the original ticket text unchanged.
- **Assumptions:** inferred from the ticket (max 8).
- **Out-of-scope:** items explicitly excluded.
- **Questions:** up to 5 open questions that must be resolved before or during planning.

---

## Step 3 — Write task_prompt.md (DRAFT)

Populate `docs/tasks/<TASK>/task_prompt.md` using the template structure:

```
# Task Prompt
## STATUS: DRAFT

## Context
## Goal
## Acceptance Criteria
## API Endpoints (draft)
## Data Model (draft)
## Validation & Errors
## Security / Auth
## Response format for admin dashboard (list + detail)
## Constraints / Non-goals
## Expected touch points (best guess)
## Test expectations
```

Rules:
- Acceptance criteria must be specific and testable (include HTTP status codes and payload shapes).
- If auth mechanism is unknown, assume "admin-only" and list it as a question.
- All JSON envelope responses must follow: `{ "success", "data", "message", "errors" }`.

---

## Step 4 — Write implement_plan.md (DRAFT skeleton only)

Populate `docs/tasks/<TASK>/implement_plan.md` with a skeleton:

```
# Implement Plan: <TASK>
## STATUS: DRAFT

## Summary
## Files to change
## Step-by-step plan
## Test plan
```

Do NOT fill in the actual steps — leave all sections as `_TBD_`.

---

## Step 5 — Write pr_description.md (skeleton)

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

**Do NOT proceed to Phase 2 (plan-to-implement).**

Tell the user:
> "Phase 1 complete. Please review `docs/tasks/<TASK>/task_prompt.md`.
> When satisfied, change `## STATUS: DRAFT` to `## STATUS: APPROVED` and let me know."

</detailed_sequence_steps>

</task>
