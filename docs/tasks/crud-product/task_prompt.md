# Task Prompt
## STATUS: APPROVED

---

## Context

This is a **CakePHP 5** backend project. An admin dashboard needs a product management API. Currently no product endpoints exist. The API must serve dashboard table views (paginated list with sorting) and detail views (single product), and support create, update, and delete operations initiated from the dashboard UI.

Auth mechanism is unknown — assumed to be admin-only bearer token (JWT); listed under Questions.

---

## Goal

Implement a JSON REST API that allows admin users to **Create, Read (list + detail), Update, and Delete** product records stored in the database. The API must return responses formatted for consumption by an admin dashboard UI.

---

## Acceptance Criteria

- [ ] `GET /api/products` returns a paginated JSON list of products with HTTP 200, including pagination metadata.
- [ ] `GET /api/products/{id}` returns a single product as JSON with HTTP 200, or HTTP 404 with an error message if not found.
- [ ] `POST /api/products` creates a new product and returns HTTP 201 with the created product payload on success; returns HTTP 422 with validation errors on failure.
- [ ] `PUT /api/products/{id}` updates an existing product and returns HTTP 200 with the updated product payload on success; returns HTTP 422 with validation errors on failure; returns HTTP 404 if not found.
- [ ] `DELETE /api/products/{id}` deletes the product and returns HTTP 200 with a success message; returns HTTP 404 if not found.
- [ ] All endpoints require admin authentication; unauthenticated requests return HTTP 401; unauthorized (non-admin) requests return HTTP 403.
- [ ] All responses use the standard JSON envelope: `{ "success": bool, "data": ..., "message": string, "errors": {...} }`.
- [ ] `name` and `price` are required fields; missing or invalid values return HTTP 422 with field-level error details.
- [ ] `price` must be a positive number; `stock` must be a non-negative integer; violations return HTTP 422.
- [ ] Unit and integration tests pass for all five endpoints covering happy path and key error cases.

---

## API Endpoints (draft)

| Method | URI | Description | Auth |
|--------|-----|-------------|------|
| GET | `/api/products` | List all products (paginated) | Admin |
| GET | `/api/products/{id}` | Get single product detail | Admin |
| POST | `/api/products` | Create a new product | Admin |
| PUT | `/api/products/{id}` | Update an existing product | Admin |
| DELETE | `/api/products/{id}` | Delete a product | Admin |

**Query params for list:**
- `page` (integer, default: 1)
- `limit` (integer, default: 20, max TBD — see Questions)
- `status` (string, optional filter: `active` | `inactive`)

---

## Data Model (draft)

**Table:** `products`

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | INT UNSIGNED | PK, AUTO_INCREMENT | |
| `name` | VARCHAR(255) | NOT NULL | Required |
| `description` | TEXT | NULLABLE | |
| `price` | DECIMAL(10,2) | NOT NULL | Must be > 0 |
| `stock` | INT UNSIGNED | NOT NULL, DEFAULT 0 | Must be >= 0 |
| `status` | ENUM('active','inactive') | NOT NULL, DEFAULT 'active' | |
| `created` | DATETIME | NOT NULL | CakePHP auto-managed |
| `modified` | DATETIME | NOT NULL | CakePHP auto-managed |

> **Open question:** Additional fields (SKU, category_id, image_url) may be required — see Questions in `task_description.md`.

---

## Validation & Errors

| Field | Rule | Error HTTP code |
|-------|------|----------------|
| `name` | Required, non-empty string, max 255 chars | 422 |
| `price` | Required, numeric, > 0 | 422 |
| `stock` | Optional, integer, >= 0, defaults to 0 | 422 |
| `status` | Optional, must be `active` or `inactive` | 422 |
| `description` | Optional, string | — |

**Error response shape (422):**
```json
{
  "success": false,
  "data": null,
  "message": "Validation failed",
  "errors": {
    "name": ["Name is required"],
    "price": ["Price must be greater than 0"]
  }
}
```

**Not found response (404):**
```json
{
  "success": false,
  "data": null,
  "message": "Product not found",
  "errors": {}
}
```

---

## Security / Auth

> **Assumption (DRAFT):** All endpoints are protected by admin-only authentication.

- Unauthenticated requests → HTTP 401 `{ "success": false, "message": "Unauthorized", ... }`
- Authenticated but non-admin role → HTTP 403 `{ "success": false, "message": "Forbidden", ... }`
- Auth mechanism is TBD (JWT / session / API key) — **listed under Questions**.
- No public read access to products in scope.

---

## Response format for admin dashboard (list + detail)

**List response (`GET /api/products`):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product A",
      "description": "...",
      "price": "99.99",
      "stock": 50,
      "status": "active",
      "created": "2026-03-01T10:00:00+00:00",
      "modified": "2026-03-01T10:00:00+00:00"
    }
  ],
  "message": "OK",
  "errors": {},
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 150,
    "pages": 8
  }
}
```

**Detail response (`GET /api/products/{id}`):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product A",
    "description": "Full product description here.",
    "price": "99.99",
    "stock": 50,
    "status": "active",
    "created": "2026-03-01T10:00:00+00:00",
    "modified": "2026-03-01T10:00:00+00:00"
  },
  "message": "OK",
  "errors": {}
}
```

---

## Constraints / Non-goals

- No soft delete — hard delete only (for now).
- No image/file upload.
- No product categories or variants.
- No public-facing endpoints.
- No audit trail / history logging.
- No full-text search (basic `status` filter only).
- Must follow CakePHP 5 conventions (ORM, Table/Entity classes, Bake-compatible structure).

---

## Expected touch points (best guess)

- `config/routes.php` — register `/api/products` routes under an `api` prefix.
- `src/Controller/Api/ProductsController.php` — new controller with `index`, `view`, `add`, `edit`, `delete` actions.
- `src/Model/Table/ProductsTable.php` — new Table class with validation rules.
- `src/Model/Entity/Product.php` — new Entity class with accessible fields.
- `config/schema/` or a migration file — DDL for `products` table.
- `tests/TestCase/Controller/Api/ProductsControllerTest.php` — integration tests.
- `tests/Fixture/ProductsFixture.php` — test fixture.
- `tests/schema.sql` — add `products` table definition for test DB.

---

## Test expectations

- **Happy path — list:** `GET /api/products` → 200, `data` is array, `pagination` keys present.
- **Happy path — detail:** `GET /api/products/1` → 200, `data.id === 1`.
- **Happy path — create:** `POST /api/products` with valid payload → 201, `data.id` is integer.
- **Happy path — update:** `PUT /api/products/1` with valid payload → 200, `data` reflects changes.
- **Happy path — delete:** `DELETE /api/products/1` → 200, `success === true`.
- **Validation failure:** `POST /api/products` missing `name` → 422, `errors.name` present.
- **Not found:** `GET /api/products/9999` → 404, `success === false`.
- **Unauthenticated:** any endpoint without credentials → 401.
- **Forbidden:** authenticated non-admin → 403.
