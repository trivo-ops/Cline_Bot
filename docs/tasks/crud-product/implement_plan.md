## STATUS: DRAFT

## Summary
This plan outlines the steps to implement a JSON REST API for handling CRUD operations on products within a CakePHP 5 application. The API will support listing, viewing, creating, updating, and deleting product records, with appropriate validation, error handling, and authentication placeholders. The implementation will adhere to CakePHP 5 conventions and the specified API contract, including JSON response formats and pagination for the product list.

## Files to Change

- `src/Model/Entity/Product.php` (New)
- `src/Model/Table/ProductsTable.php` (New)
- `src/Controller/Api/ProductsController.php` (New)
- `config/routes.php` (Modify)
- `config/schema/products.sql` (New)
- `tests/schema.sql` (Modify)
- `tests/Fixture/ProductsFixture.php` (New)
- `tests/TestCase/Controller/Api/ProductsControllerTest.php` (New)

## Step-by-Step Plan

1.  **Create `config/schema/products.sql`:**
    *   Define the `products` table schema as per the data model in `task_prompt.md`.

2.  **Create `src/Model/Entity/Product.php`:**
    *   Generate the `Product` entity.
    *   Define mass assignable fields (`_accessible`).
    *   Add virtual fields if necessary (though not explicitly required by prompt).

3.  **Create `src/Model/Table/ProductsTable.php`:**
    *   Generate the `ProductsTable`.
    *   Add `initialize()` method for behaviors (TimestampBehavior) and validation rules.
    *   Implement validation rules for `name`, `price`, `stock`, and `status` as specified in `task_prompt.md`.
    *   Ensure `status` allows only 'active' or 'inactive'.
    *   Ensure `price` is positive and `stock` is non-negative.

4.  **Create `src/Controller/Api/ProductsController.php`:**
    *   Create a new controller `Api/ProductsController` that extends `AppController`.
    *   Implement `initialize()` to load components: `RequestHandler`, `Authentication`, `Authorization`.
    *   **Implement `index()` action:**
        *   Fetch paginated products.
        *   Apply `status` filter if provided.
        *   Return JSON response with product data and pagination metadata using the specified envelope.
    *   **Implement `view()` action:**
        *   Fetch a single product by ID.
        *   Handle not found (404) gracefully.
        *   Return JSON response with product data.
    *   **Implement `add()` action:**
        *   Create a new product entity from request data.
        *   Attempt to save.
        *   On success, return 201 with created product.
        *   On validation failure, return 422 with error details.
    *   **Implement `edit()` action:**
        *   Fetch existing product by ID. Handle 404.
        *   Patch entity with request data.
        *   Attempt to save.
        *   On success, return 200 with updated product.
        *   On validation failure, return 422 with error details.
    *   **Implement `delete()` action:**
        *   Fetch existing product by ID. Handle 404.
        *   Attempt to delete.
        *   On success, return 200 with success message.
        *   Handle errors during deletion.

5.  **Modify `config/routes.php`:**
    *   Add API routes for `/api/products` using `Router::prefix('Api', ...)` and `Router::resources('Products')`.
    *   Ensure routes use the `.json` extension.

6.  **Update `tests/schema.sql`:**
    *   Add the `CREATE TABLE` statement for the `products` table to this file to ensure the test database has the correct schema. This should match `config/schema/products.sql`.

7.  **Create `tests/Fixture/ProductsFixture.php`:**
    *   Define a fixture for the `products` table with sample data for testing.

8.  **Create `tests/TestCase/Controller/Api/ProductsControllerTest.php`:**
    *   Create a new test class `ProductsControllerTest` extending `IntegrationTestCase`.
    *   Use `ProductsFixture`.
    *   Write test methods for each API endpoint (`index`, `view`, `add`, `edit`, `delete`):
        *   Test happy paths (200, 201 responses, correct data).
        *   Test error cases (404 Not Found, 422 Validation errors).
        *   Test authentication/authorization (401 Unauthorized, 403 Forbidden).
    *   Ensure JSON response structure matches the specified envelope.

## Test Plan

*   Run all tests:
    ```bash
    vendor/bin/phpunit
    ```
    This command will execute all unit and integration tests.

*   Run specific API tests:
    ```bash
    vendor/bin/phpunit tests/TestCase/Controller/Api/ProductsControllerTest.php
    ```

*   **Note:** Database setup (creating the `products` table in the test database) is expected to be handled by PHPUnit's fixture loading mechanism when `tests/schema.sql` is present and the test environment is correctly configured. I will NOT attempt manual DB setup commands at this stage.

## Risks/Edge Cases

*   **Authentication/Authorization:** The prompt states "Auth mechanism is TBD (JWT / session / API key)". Current implementation will include placeholders for Authentication and Authorization components, but the specific logic for `isAdmin` will be based on assumptions or minimal implementation required to unblock testing. A separate task might be needed to fully implement the chosen auth mechanism.
*   **Pagination parameters:** Ensure `page` and `limit` (max limit) are handled correctly.
*   **Price and Stock data types:** Potential precision issues with `DECIMAL` for price and `INT UNSIGNED` for stock need careful handling, especially during validation and saving.
*   **Database Connectivity:** The recent issues with `mysql-test` connectivity indicate a potential risk for tests. Assuming the environment is stable for running PHPUnit.
*   **Concurrent API calls:** Not in scope for initial implementation, but a risk for future scalability.
*   **Error message consistency:** Ensuring all error responses uniformly follow the specified JSON envelope.
*   **Timezone handling:** `DATETIME` fields `created` and `modified` are auto-managed by CakePHP; need to ensure timezone consistency if not using UTC everywhere.
*   **Security:** SQL injection and XSS will be mitigated by CakePHP's ORM and escaping mechanisms, but review is always necessary.
