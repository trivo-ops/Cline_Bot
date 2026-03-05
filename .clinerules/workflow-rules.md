# Workflow Rules

These rules govern how Cline executes structured workflows stored under `.clinerules/workflows/`.

---

## 1. Workflow execution order

Every task follows this three-phase workflow in strict order:

```
prompt-to-plan  →  plan-to-implement  →  commit-and-pr
```

- **Phase 1 — prompt-to-plan:** Normalize the ticket into `task_prompt.md` and create a DRAFT `implement_plan.md`.
- **Phase 2 — plan-to-implement:** Expand `implement_plan.md` with full steps, implement the code, run tests.
- **Phase 3 — commit-and-pr:** Stage changes, create a commit, open a PR.

---

## 2. Gate: NEVER skip a phase

| Gate | Condition to pass |
|------|-------------------|
| Start Phase 2 | `task_prompt.md` contains `## STATUS: APPROVED` |
| Start Phase 3 | `implement_plan.md` contains `## STATUS: APPROVED` AND all tests pass |

If a gate condition is not met, **STOP** and instruct the user to approve the relevant file.

---

## 3. Human approval points

Cline must pause and explicitly tell the user to take action at these points:

1. After writing `task_prompt.md` (DRAFT) → ask user to review and change STATUS to APPROVED.
2. After writing `implement_plan.md` (expanded) → ask user to review and change STATUS to APPROVED.
3. After tests pass → ask user to confirm before pushing / creating a PR.

---

## 4. Task folder discipline

- All workflow output for a task named `<TASK>` goes under `docs/tasks/<TASK>/`.
- Template files live in `docs/tasks/_TEMPLATE/` — copy them when starting a new task.
- Never write task artifacts to the project root or any other path.

---

## 5. Test command

```bash
# Preferred (local PHP)
vendor/bin/phpunit

# Docker variant
docker compose exec app vendor/bin/phpunit
```

Run tests after every implementation phase. Report results (pass/fail counts) before proceeding to the commit phase.

---

## 6. Secrets — absolute prohibition

- **Never** read, log, output, or commit files matching:
  - `tools/antigravity-manager/antigravity_data/**`
  - `.env`, `.env.local`, `config/app_local.php`
  - Any file that contains API keys, passwords, or tokens.
- If a workflow step requires a secret value, **STOP** and ask the user to provide only the specific non-sensitive value.

---

## 7. Commit message format

```
<type>(<scope>): <short description>

Types: feat | fix | refactor | test | docs | chore
Example: feat(products): add CRUD API endpoints
```

---

## 8. PR creation preference

1. **Preferred:** `gh pr create` (if `gh` CLI is available and authenticated).
2. **Fallback:** Print the PR title, body, and branch compare URL so the user can open the PR manually.
