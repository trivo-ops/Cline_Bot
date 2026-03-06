# Implement Plan: crud-product-api
## STATUS: APPROVED

> ⚠️ Do NOT expand this plan until `task_prompt.md` STATUS is **APPROVED**.

---

## Summary

This plan outlines the steps to implement a RESTful CRUD API for managing products in a CakePHP 5 application. The API will include endpoints for listing, viewing, creating, updating, and deleting products, adhering to CakePHP conventions and returning consistent JSON responses.

---

## Files to change

| File | Action | Notes |
|------|--------|-------|
| `config/routes.php` | Modify | Add API routes for products |
| `src/Controller/Api/ProductsController.php` | Create | Contains CRUD actions for products |
| `src/Model/Table/ProductsTable.php` | Modify | Add validation rules for products |
| `src/Model/Entity/Product.php` | Modify | Define accessible fields for mass assignment |
| `tests/TestCase/Controller/Api/ProductsControllerTest.php` | Create | Integration tests for API endpoints |
| `tests/Fixture/ProductsFixture.php` | Create | Test fixture for product data |
| `config/schema/products.sql` | Modify | Ensure product table schema is current |

---

## Step-by-step plan

1. **Verify Database Schema**:
   - Ensure the `products` table exists with the specified columns (`id`, `name`, `description`, `price`, `stock`, `created`, `modified`). If not, generate a migration or update `config/schema/products.sql`.

2. **Update `src/Model/Entity/Product.php`**:
   - Define `$_accessible` properties to allow mass assignment for `name`, `description`, `price`, and `stock`.

3. **Update `src/Model/Table/ProductsTable.php`**:
   - Add validation rules for `name`, `price`, and `stock` using the `validationDefault` method.
   - Implement pagination and sorting logic for the `index` action.

4. **Create `src/Controller/Api/ProductsController.php`**:
   - Extend `Cake\Controller\Controller` and use `JsonTrait` for JSON responses.
   - Implement the `index()` action for listing products with pagination and sorting.
   - Implement the `view($id)` action for retrieving a single product.
   - Implement the `add()` action for creating a new product.
   - Implement the `edit($id)` action for updating an existing product.
   - Implement the `delete($id)` action for deleting a product.
   - Ensure consistent JSON response format `{ "success", "data", "message", "errors" }` are returned.

5. **Update `config/routes.php`**:
   - Add API routes for `/api/products` to map to the `Api/ProductsController`.
   - Configure routes for individual CRUD operations (index, view, add, edit, delete).

6. **Create `tests/Fixture/ProductsFixture.php`**:
   - Define a fixture for the `products` table with sample data.

7. **Create `tests/TestCase/Controller/Api/ProductsControllerTest.php`**:
   - Write integration tests for each API endpoint (`index`, `view`, `add`, `edit`, `delete`).
   - Test happy paths, validation errors (422), not found errors (404), and authorization (401/403).

8. **Run Tests**:
   - Execute the test suite to ensure all new and existing tests pass.

---

## Test plan

- Run `vendor/bin/phpunit` (or `docker compose exec app vendor/bin/phpunit`) to execute the test suite.
- Specific scenarios to be covered by tests include:
    - Listing products with and without pagination/sorting parameters.
    - Retrieving details of an existing product.
    - Attempting to retrieve a non-existent product.
    - Successfully creating a new product.
    - Attempting to create a product with invalid data (e.g., missing name, invalid price).
    - Successfully updating an existing product.
    - Attempting to update a non-existent product.
    - Attempting to update a product with invalid data.
    - Successfully deleting an existing product.
    - Attempting to delete a non-existent product.
    - Testing authentication and authorization for all endpoints.
