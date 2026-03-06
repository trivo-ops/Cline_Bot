# Human-in-the-Loop Workflow

This document outlines the human-in-the-loop workflow for feature development.

## 6-Step Flow

1.  **Ticket:** Start with a new task or feature ticket.
2.  **Generate Prompt:** Use Cline to generate an initial `task_prompt.md`.
3.  **Approve Prompt:** User reviews and approves `task_prompt.md` by changing its status to `## STATUS: APPROVED`.
4.  **Plan:** Use Cline to generate an `implement_plan.md` based on the approved prompt.
5.  **Approve Plan:** User reviews and approves `implement_plan.md` by changing its status to `## STATUS: APPROVED`.
6.  **Implement & Commit+PR:** Cline implements the plan, runs tests, commits the changes, and initiates a Pull Request.

## Quick Commands in Cline

You can trigger the workflow steps using the following commands:

1.  `/ship-new-feature.md` (preferred end-to-end workflow)
2.  Alternatively, you can use individual commands for each phase:
    *   `gen prompt for <task>`
    *   `plan <task>`
    *   `implement <task>`
    *   `commit and PR <task>`

## Approval Gates

Both `task_prompt.md` and `implement_plan.md` require the exact line `## STATUS: APPROVED` to be present before Cline can proceed to the next phase of the workflow.

## File Locations

All task-related documentation and artifacts are stored in a standardized structure:

*   Template files: `docs/tasks/_TEMPLATE/*`
*   Task-specific files: `docs/tasks/<task>/{task_description.md, task_prompt.md, implement_plan.md, pr_description.md}`

## Testing Note

If the environment blocks testing or tests cannot be run for valid reasons, indicate this in `pr_description.md` by writing: "Testing: Skipped (env issue)".
