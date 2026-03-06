Type: feat
Scope: products

Description: Add CRUD API endpoints for products.

This PR implements a RESTful CRUD API for product management, including endpoints for listing, viewing, creating, updating, and deleting products.

Features:
- List products with pagination and sorting.
- Retrieve individual product details.
- Create new products with validation.
- Update existing products with validation.
- Delete products.
- Consistent JSON response format for all API interactions.

Testing: Skipped (due to Docker environment issues with Composer/PHPUnit)

Files Changed:
- `config/routes.php`: Added API routes for products.
- `src/Controller/Api/ProductsController.php`: Created API controller with CRUD actions.
- `src/Model/Entity/Product.php`: Updated accessible fields.
- `src/Model/Table/ProductsTable.php`: Added validation rules.
- `tests/Fixture/ProductsFixture.php`: Created test fixture for products.
- `tests/TestCase/Controller/Api/ProductsControllerTest.php`: Created integration tests (skipped).
