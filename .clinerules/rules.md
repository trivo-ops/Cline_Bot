# Cline Rules

## 1. Mandatory Approval Gates

**NEVER implement any business feature code unless ALL of the following are true:**

1. `docs/tasks/<TASK>/task_prompt.md` contains the exact line:
   ```
   ## STATUS: APPROVED
   ```
2. `docs/tasks/<TASK>/implement_plan.md` contains the exact line:
   ```
   ## STATUS: APPROVED
   ```

If either file is still `## STATUS: DRAFT`, **STOP** immediately and inform the user to review and approve the relevant file before continuing.

---

## 2. Output location

- All task-related documentation and artifacts **must** live under `docs/tasks/<TASK>/`.
- Never write task docs to the project root or any other location.
- Standard files per task:
  - `task_description.md` — original ticket + assumptions + questions
  - `task_prompt.md` — normalized prompt (must be APPROVED before planning)
  - `implement_plan.md` — step-by-step plan (must be APPROVED before coding)
  - `pr_description.md` — PR write-up (filled after implementation)

---

## 3. Running tests

- **Primary command:** `vendor/bin/phpunit`
- **Docker variant:** `docker compose exec app vendor/bin/phpunit`
- Always run tests after implementation and report results before creating a PR.
- Never mark a task complete if tests are failing.

---

## 4. Secrets policy

- **NEVER** read, log, print, commit, or expose any file matching:
  - `tools/antigravity-manager/antigravity_data/**`
  - `.env`, `.env.local`, `config/app_local.php`, or any file containing credentials/API keys.
- If a workflow step would require reading such a file, **STOP** and ask the user to provide only the specific non-sensitive value needed.

---

## 5. General coding conventions

- Follow CakePHP 5 conventions: Table/Entity class names, snake_case DB columns, `created`/`modified` auto-timestamps.
- All new API endpoints return JSON; use a consistent envelope: `{ "success", "data", "message", "errors" }`.
- Never use `debug()`, `var_dump()`, or `die()` in committed code.
- Prefer `replace_in_file` over `write_to_file` for modifying existing files.
