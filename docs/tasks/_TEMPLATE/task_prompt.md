# Task Prompt
## STATUS: DRAFT

---

## Context

_Describe the system context, existing state, and why this task is needed._

---

## Goal

_One or two sentences: what must be true when this task is done._

---

## Acceptance Criteria

- [ ] _Specific, testable criterion (include HTTP status codes and payload shapes for API tasks)._
- [ ] _..._

---

## API Endpoints (draft)

| Method | URI | Description | Auth |
|--------|-----|-------------|------|
| GET | `/api/...` | _description_ | _role_ |

**Query params:** _list any optional/required query params._

---

## Data Model (draft)

**Table:** `<table_name>`

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | INT UNSIGNED | PK, AUTO_INCREMENT | |
| `created` | DATETIME | NOT NULL | CakePHP auto |
| `modified` | DATETIME | NOT NULL | CakePHP auto |

---

## Validation & Errors

| Field | Rule | HTTP code |
|-------|------|-----------|
| `field` | Required, ... | 422 |

---

## Security / Auth

> **Assumption:** _Describe auth assumption; list it under Questions if unknown._

- Unauthenticated → 401
- Unauthorized role → 403

---

## Response format for admin dashboard (list + detail)

**List:**
```json
{
  "success": true,
  "data": [],
  "message": "OK",
  "errors": {},
  "pagination": { "page": 1, "limit": 20, "total": 0, "pages": 0 }
}
```

**Detail:**
```json
{
  "success": true,
  "data": {},
  "message": "OK",
  "errors": {}
}
```

---

## Constraints / Non-goals

- _List explicit constraints and things this task does NOT do._

---

## Expected touch points (best guess)

- `config/routes.php`
- `src/Controller/...`
- `src/Model/Table/...`
- `src/Model/Entity/...`
- `tests/TestCase/...`
- `tests/Fixture/...`

---

## Test expectations

- _Happy path — list:_ ...
- _Validation failure:_ ...
- _Not found:_ ...
- _Unauthenticated:_ ...
