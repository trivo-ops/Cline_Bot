# Task Prompt
## STATUS: APPROVED

---

## Context

The existing system requires an API for managing products from an admin dashboard. This task involves creating a set of CRUD (Create, Read, Update, Delete) endpoints for products.

---

## Goal

To implement a RESTful CRUD API for products including list, detail, create, update, and delete operations, adhering to CakePHP 5 conventions and returning JSON responses with a consistent envelope.

---

## Acceptance Criteria

- [ ] `GET /api/products`: List products with pagination (`page`, `limit`) and sorting (`sort`, `direction`). Returns 200 OK.
- [ ] `GET /api/products/{id}`: Get product details by ID. Returns 200 OK if found, 404 Not Found otherwise.
- [ ] `POST /api/products`: Create a new product. Returns 201 Created on success, 422 Unprocessable Entity on validation failure.
- [ ] `PUT /api/products/{id}`: Update an existing product by ID. Returns 200 OK on success, 404 Not Found if product doesn't exist, 422 Unprocessable Entity on validation failure.
- [ ] `DELETE /api/products/{id}`: Delete a product by ID. Returns 204 No Content on success, 404 Not Found if product doesn't exist.
- [ ] All API responses adhere to the consistent JSON envelope: `{ "success", "data", "message", "errors" }`.
- [ ] Basic validation for product data (e.g., 'name' and 'price' are required).
- [ ] Implementation follows CakePHP 5 conventions for controllers, models, and entities.

---

## API Endpoints (draft)

| Method | URI | Description | Auth |
|--------|-----|-------------|------|
| GET | `/api/products` | List all products with pagination and sorting | admin |
| GET | `/api/products/{id}` | Get details of a single product | admin |
| POST | `/api/products` | Create a new product | admin |
| PUT | `/api/products/{id}` | Update an existing product | admin |
| DELETE | `/api/products/{id}` | Delete a product | admin |

**Query params for `GET /api/products`:**
- `page` (optional): Current page number, default 1.
- `limit` (optional): Number of items per page, default 10.
- `sort` (optional): Field to sort by (e.g., `name`, `price`).
- `direction` (optional): Sort direction (`asc` or `desc`), default `asc`.

---

## Data Model (draft)

**Table:** `products`

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | INT UNSIGNED | PK, AUTO_INCREMENT | |
| `name` | VARCHAR(255) | NOT NULL | Product name |
| `description` | TEXT | NULL | Product description |
| `price` | DECIMAL(10, 2) | NOT NULL | Product price |
| `stock` | INT | NOT NULL | Product stock quantity |
| `created` | DATETIME | NOT NULL | CakePHP auto |
| `modified` | DATETIME | NOT NULL | CakePHP auto |

---

## Validation & Errors

| Field | Rule | HTTP code |
|-------|------|-----------|
| `name` | Not empty | 422 |
| `price` | Not empty, numeric, greater than 0 | 422 |
| `stock` | Not empty, integer, greater than or equal to 0 | 422 |
| `id` | Exists for PUT/DELETE | 404 |

---

## Security / Auth

> **Assumption:** API endpoints are protected and require an authenticated 'admin' role.

- Unauthenticated → 401 Unauthorized
- Unauthorized role → 403 Forbidden

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

- User interface for product management is out of scope. This task focuses solely on the API.
- Complex business logic beyond basic CRUD (e.g., inventory management, order processing) is out of scope.
- Authentication and authorization implementation is assumed to be in place; only role checking will be considered.

---

## Expected touch points (best guess)

- `config/routes.php`
- `src/Controller/Api/ProductsController.php`
- `src/Model/Table/ProductsTable.php`
- `src/Model/Entity/Product.php`
- `tests/TestCase/Controller/Api/ProductsControllerTest.php`
- `tests/Fixture/ProductsFixture.php`

---

## Test expectations

- _Happy path — list products with pagination and sorting._
- _Happy path — get product details._
- _Happy path — create a new product._
- _Happy path — update an existing product._
- _Happy path — delete a product._
- _Validation failure — create/update with missing/invalid data._
- _Not found — get/update/delete non-existent product._
- _Unauthenticated access to endpoints._
- _Unauthorized role access to endpoints._
