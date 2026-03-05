---
description: "Phase 3 — Stage changes, create a commit, and open a pull request."
author: "Cline"
version: "1.0.0"
tags: ["git", "pr", "commit", "phase-3"]
globs: ["docs/tasks/**", "src/**", "tests/**", "config/**"]
---

<task name="commit-and-pr">

<task_objective>
Stage all task-related changes, create a well-formed commit, and open a pull request (via gh CLI if available, otherwise print PR details for manual creation). Do NOT start until implement_plan.md is APPROVED and all tests pass.
</task_objective>

<detailed_sequence_steps>

## GATE CHECK — Before doing anything ⛔

1. Read `docs/tasks/<TASK>/implement_plan.md`.
2. Confirm it contains the exact line: `## STATUS: APPROVED`
3. If it still says `## STATUS: DRAFT` → **STOP immediately.**
   Tell the user:
   > "`implement_plan.md` is still DRAFT. Please change its STATUS to APPROVED and confirm all tests pass before I can proceed."
4. Confirm the most recent test run passed (ask user if unsure).

---

## ⚠️ SECRETS WARNING

Before staging any files, verify that NONE of the following are included in the diff:
- `tools/antigravity-manager/antigravity_data/**`
- `.env`, `.env.local`, `config/app_local.php`
- Any file containing API keys, passwords, or tokens.

If any such file appears in `git status`, **STOP** and tell the user to add it to `.gitignore` before proceeding.

---

## Step 1 — Check current branch

```bash
git status
git branch
```

- If on `main` / `master`, create a feature branch:
  ```bash
  git checkout -b feat/<TASK>
  ```
- If already on a feature branch, confirm it matches the task slug.

---

## Step 2 — Stage changes

```bash
git add docs/tasks/<TASK>/
git add src/
git add tests/
git add config/routes.php
```

Review staged files with:
```bash
git diff --cached --stat
```

Do NOT stage `.env`, secrets files, or anything outside the task scope.

---

## Step 3 — Commit

Use the conventional commit format:

```
feat(<scope>): <short description>

Types: feat | fix | refactor | test | docs | chore
```

Example:
```bash
git commit -m "feat(products): add CRUD API endpoints"
```

---

## Step 4 — Fill in pr_description.md

Update `docs/tasks/<TASK>/pr_description.md` with:
- **What:** brief description of what this PR implements.
- **Why:** business reason / ticket reference.
- **How:** technical approach summary.
- **Testing:** how to run tests + test result summary.
- **Notes/Risks:** open questions, known risks, or follow-up items.

---

## Step 5 — Push branch

```bash
git push -u origin feat/<TASK>
```

---

## Step 6 — Create PR

### Preferred: gh CLI

Check if `gh` is available and authenticated:
```bash
gh auth status
```

If available:
```bash
gh pr create \
  --title "feat(<scope>): <short description>" \
  --body "$(cat docs/tasks/<TASK>/pr_description.md)" \
  --base main \
  --head feat/<TASK>
```

### Fallback: manual PR

If `gh` is not available or not authenticated, print the following for the user to open manually:

```
PR Title : feat(<scope>): <short description>
Base     : main
Compare  : feat/<TASK>
URL      : https://github.com/<owner>/<repo>/compare/main...feat/<TASK>

PR Body  :
----------
(contents of pr_description.md)
----------
```

---

## GATE — STOP HERE ⛔

Tell the user:
> "Phase 3 complete. PR has been created (or details printed above for manual creation).
> Task `<TASK>` is done. ✅"

</detailed_sequence_steps>

</task>
