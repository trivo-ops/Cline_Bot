# Implementation Plan
## STATUS: APPROVED

This plan outlines the steps to implement the RESTful CRUD API for products in CakePHP 5, as detailed in `task_prompt.md`.

---

## 1. Environment Setup & Verification

- [ ] Ensure Docker environment is running: `docker compose up -d`.
- [ ] Verify PHP dependencies are installed: `docker compose exec app composer install`.
- [ ] Ensure database migrations are up-to-date: `docker compose exec app bin/cake migrations migrate`.

---

## 2. API Routing Configuration

- [ ] Modify `config/routes.php` to define API routes for products.
  - Use `Router::prefix('Api', function (RouteBuilder $routes) { ... });`
  - Define `products` resource routes: `$routes->resources('Products');`
  - Add specific routes for pagination, sorting if needed (though `resources` often handles basic CRUD).

---

## 3. Product Model (Entity & Table) Enhancements

### `src/Model/Entity/Product.php`
- [ ] Add mass assignment protection (`_accessible` property) for `name`, `description`, `price`, `stock`.

### `src/Model/Table/ProductsTable.php`
- [ ] Implement `initialize()` method:
  - Set up `displayField('name')`.
  - Add `timestamp` behavior.
  - Implement validation rules for `name`, `price`, `stock` as per `task_prompt.md`.
    - `name`: not empty.
    - `price`: not empty, numeric, greater than 0.
    - `stock`: not empty, integer, greater than or equal to 0.

---

## 4. API Controller (`src/Controller/Api/ProductsController.php`) Implementation

- [ ] Extend `AppController`.
- [ ] Use `JsonView` for automatic JSON response serialization.
- [ ] Implement `initialize()`:
  - Load `RequestHandlerComponent` to handle content type negotiation.
  - Load `PaginatorComponent` for listing.
  - (Optional) Implement basic authentication/authorization checks (placeholder for now, as it's out of scope).

### Actions:

#### `index()` (List Products)
- [ ] Fetch paginated and sortable product data.
- [ ] Apply pagination (page, limit) and sorting (sort, direction) from query parameters.
- [ ] Construct JSON response with `{ "success", "data", "message", "errors", "pagination" }` envelope.

#### `view($id)` (Get Product Details)
- [ ] Find product by `$id`.
- [ ] Handle 404 Not Found if product doesn't exist.
- [ ] Construct JSON response with `{ "success", "data", "message", "errors" }` envelope.

#### `add()` (Create Product)
- [ ] Create a new `Product` entity from request data.
- [ ] Attempt to save the entity.
- [ ] On success: return 201 Created with `{ "success": true, "data": product, "message": "Product created successfully." }`.
- [ ] On validation failure: return 422 Unprocessable Entity with `{ "success": false, "errors": validation_errors, "message": "Validation failed." }`.

#### `edit($id)` (Update Product)
- [ ] Find product by `$id`. Handle 404 if not found.
- [ ] Patch existing `Product` entity with request data.
- [ ] Attempt to save the entity.
- [ ] On success: return 200 OK with `{ "success": true, "data": product, "message": "Product updated successfully." }`.
- [ ] On validation failure: return 422 Unprocessable Entity with `{ "success": false, "errors": validation_errors, "message": "Validation failed." }`.

#### `delete($id)` (Delete Product)
- [ ] Find product by `$id`. Handle 404 if not found.
- [ ] Ensure request is a DELETE request.
- [ ] Attempt to delete the entity.
- [ ] On success: return 204 No Content with `{ "success": true, "message": "Product deleted successfully." }`.
- [ ] On failure: return 500 Internal Server Error (or appropriate error) with `{ "success": false, "message": "Product could not be deleted." }`.

---

## 5. Unit Tests (`tests/TestCase/Controller/Api/ProductsControllerTest.php`)

- [ ] Create `ProductsControllerTest` extending `AppTestCase`.
- [ ] Use `ProductsFixture` (or create if not already existing) for test data.
- [ ] Implement tests for each API endpoint, covering:
  - [ ] `index()`:
    - [ ] List all products (happy path).
    - [ ] Pagination (page, limit).
    - [ ] Sorting (sort, direction).
  - [ ] `view()`:
    - [ ] Get existing product (happy path).
    - [ ] Get non-existent product (404).
  - [ ] `add()`:
    - [ ] Create new product (happy path).
    - [ ] Create with invalid data (422).
  - [ ] `edit()`:
    - [ ] Update existing product (happy path).
    - [ ] Update non-existent product (404).
    - [ ] Update with invalid data (422).
  - [ ] `delete()`:
    - [ ] Delete existing product (happy path).
    - [ ] Delete non-existent product (404).
  - [ ] (Optional) Unauthenticated/Unauthorized access (if auth is mocked).

---

## 6. Pre-flight Checks & Reporting

- [ ] Run all tests: `bin/phpunit`.
- [ ] Ensure all tests pass.
- [ ] Update `pr_description.md` with test results and a summary of changes.

---

## 7. Inform User for Approval

- [ ] Notify the user that `implement_plan.md` has been updated and requires their review and approval.
