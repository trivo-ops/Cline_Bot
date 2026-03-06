# Human-in-the-Loop Workflow

This project uses a structured, human-in-the-loop workflow with Cline. All workflow rules and steps live in `.clinerules/`.

## Overview

1.  **Write Task (Human):** Start with a new task or feature ticket.
2.  **Generate Prompt(AI):** Use Cline to generate an initial `task_prompt.md`.
3.  **Review Prompt(Human):** User reviews and approves `task_prompt.md` by changing its status to `## STATUS: APPROVED`.
4.  **Plan(AI):** Use Cline to generate an `implement_plan.md` based on the approved prompt.
5.  **Review Plan(Human):** User reviews and approves `implement_plan.md` by changing its status to `## STATUS: APPROVED`.
6.  **Implement & Commit+PR(AI):** Cline implements the plan, runs tests, commits the changes, and initiates a Pull Request.

---

## Quick Start

### 1. Create a new task
```bash

Create a task folder using a slug and copy templates:

Template: docs/tasks/_TEMPLATE/

Task: docs/tasks/<task>/

Minimum files in a task folder:

task_description.md

task_prompt.md

implement_plan.md

pr_description.md

Tip: if multiple tasks exist, always specify the exact <task> when running workflows to avoid Cline selecting the wrong folder.

```

### 2. Generate prompt (tell Cline)
```
gen prompt for <task>
```

### 3. Review & approve prompt
Open `docs/tasks/<task>/task_prompt.md`, review, then add:
```
## STATUS: APPROVED
```

### 4. Generate plan (tell Cline)
```
plan <task>
```

### 5. Review & approve plan
Open `docs/tasks/<task>/implement_plan.md`, review, then add:
```
## STATUS: APPROVED
```

### 6. Implement (tell Cline)
```
implement <task>
```
Cline will code, run tests, fix issues, and report back.

### 7. Commit & PR (tell Cline)
```
commit and PR <task>
```

---

## File Structure

```
myapp/
├── .clinerules/
│   ├── rules.md                    ← Coding standards & project rules
│   └── workflows/
│       ├── gen-prompt.md           ← How AI generates prompts
│       ├── plan.md                 ← How AI creates implementation plans
│       ├── implement.md            ← How AI implements code
│       └── commit-pr.md            ← How AI commits and prepares PRs
│
└── docs/
    └── tasks/
        ├── _TEMPLATE/              ← Copy these for each new task
        │   ├── task_description.md
        │   ├── task_prompt.md
        │   └── implement_plan.md
        │
        └── {task-name}/            ← One folder per task
            ├── task_description.md ← Human writes (Step 1)
            ├── task_prompt.md      ← AI generates, Human approves (Steps 2-3)
            └── implement_plan.md   ← AI generates, Human approves (Steps 4-5)
```

## Useful Make Commands

| Command                                                 | Description                                                          |
| ------------------------------------------------------- | -------------------------------------------------------------------- |
| `docker compose up -d`                                  | Start dev services                                                   |
| `docker compose down`                                   | Stop dev services                                                    |
| `docker compose ps`                                     | Show service status                                                  |
| `docker compose config --services`                      | List service names (useful when Cline needs the correct PHP service) |
| `docker compose exec -T cakephp-app php -v`             | Verify PHP runtime inside the app container                          |
| `docker compose exec -T cakephp-app composer install`   | Install PHP deps (if composer exists in container)                   |
| `docker compose exec -T cakephp-app vendor/bin/phpunit` | Run PHPUnit (best effort; may be skipped if env blocks)              |

---

## Git Branch Strategy

* Base branch: master (or main — follow the repo default)
*
* Feature branches: feat/<task>
*
* Fix branches: fix/<task>
*
* Chore branches: chore/<task>

All branches originate from `master` and are merged back via PR.

---

## Commit Convention

```
feat(scope): add user authentication
fix(scope): correct email validation rule
refactor(scope): extract service class
test(scope): add ArticlesTable unit tests
docs(scope): update workflow documentation
```

---

## Docker Services

| Service                          | URL                                            | Description                                    |
| -------------------------------- | ---------------------------------------------- | ---------------------------------------------- |
| App (`cakephp-app`)              | [http://localhost:8080](http://localhost:8080) | CakePHP 5 application                          |
| DB (`mysql-test`)                | localhost:3306                                 | MySQL (dev/test database)                      |
| Provider (`antigravity-manager`) | [http://localhost:8045](http://localhost:8045) | OpenAI-compatible proxy for Cline (if enabled) |


Credentials and connection details should live in env/config files (never committed secrets).

## Testing Note

If the environment blocks testing or tests cannot be run for valid reasons, indicate this in `pr_description.md` by writing: "Testing: Skipped (env issue)".
