# Task Description: Implement CRUD API for Product

## Ticket (verbatim)

Task: Implement CRUD API for Product

Description:
Create a basic CRUD API to manage products that will be used in the admin dashboard. The API should allow administrators to create, read, update, and delete product records. These APIs will support dashboard functionalities such as displaying a list of products, viewing product details, adding new products, editing existing products, and deleting products directly from the dashboard interface. The API should return data in a format suitable for dashboard tables and detail views, enabling easy integration with the dashboard UI.

---

## Assumptions

1. All endpoints are admin-only; unauthenticated or non-admin requests must be rejected.
2. Auth mechanism is unknown — assume bearer token (JWT) until confirmed; listed under Questions.
3. The `products` table follows CakePHP 5 conventions: snake_case columns, auto-managed `created` and `modified` timestamps.
4. Minimum required fields: `id`, `name`, `description`, `price`, `stock`, `status`, `created`, `modified`.
5. The API returns JSON using a consistent envelope: `{ "success", "data", "message", "errors" }`.
6. Hard delete is acceptable; soft-delete is not required for this ticket.
7. The list endpoint supports pagination (`page`, `limit`) and basic sorting (`sort`, `direction`).
8. No image/file upload is in scope for this ticket.

---

## Out of scope

- Product image / media upload and management.
- Public-facing (unauthenticated) product endpoints.
- Product categories, tags, or variant management.
- Inventory history or audit logging.
- Soft delete / restore functionality.
- Full-text or advanced search (basic `status` filter only).
- Frontend / dashboard UI implementation.
- Third-party integrations (e-commerce platforms, ERP, etc.).

---

## Questions

1. **Auth mechanism:** What authentication method is in use (JWT, session, API key)? Determines how the admin guard is implemented.
2. **Additional fields:** Are there mandatory fields beyond `name`, `description`, `price`, `stock` (e.g., SKU, `category_id`, `image_url`)?
3. **Soft delete:** Should deleted products be flagged (`deleted_at`) for restore capability, or is hard delete sufficient?
4. **Pagination defaults:** What are the expected default and maximum `limit` values per page?
5. **Existing envelope:** Is there an existing JSON response envelope/format in other endpoints that this API must match exactly?
