# Sprint 02 - Categories Module

## Goal

Implement the Category module to organize and classify products inside StockFlow.

The module will provide category management and establish the relationship between categories and products.

---

## Business Context

Products need to be grouped into categories to improve organization, filtering and future inventory analysis.

Examples:

- Category: Beverages
    - Coca-Cola 2L
    - Water 500ml

- Category: Cleaning
    - Detergent
    - Soap Powder

---

## Scope

### Included

- Category CRUD
- Category validation
- Category/Product relationship
- Category API endpoints
- Feature tests
- Module documentation

### Not Included

- Product stock movement
- Inventory calculations
- Category permissions
- Reports
- Category hierarchy (subcategories)

---

## Features

### Category Management

The system must allow:

- Create categories
- List categories
- Find category by ID
- Update categories
- Delete categories

---

## Domain Rules

### Category

Rules:

- Name is required.
- Category name must be unique.
- Category cannot be created without a name.

---

## Relationship

Category has many Products.

Product belongs to one Category.

Relationship:

Category (1) -------- (*) Product

---

## API Endpoints

Expected endpoints:

- GET /api/categories
- POST /api/categories
- GET /api/categories/{id}
- PUT /api/categories/{id}
- DELETE /api/categories/{id}

---

## Technical Goals

- Implement Eloquent relationships.
- Create database foreign keys.
- Improve API responses using Resources.
- Apply Laravel best practices.
- Maintain test coverage.

---

## Testing Strategy

Feature tests:

### Success cases

- Create category
- List categories
- Find category
- Update category
- Delete category

### Error cases

- Duplicate category name
- Category not found
- Invalid category data

---

## Documentation

Update:

- README.md
- CHANGELOG.md
- Category module documentation

---

## Version

Target:

v0.2.0
