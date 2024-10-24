# Point of Sale (POS) API

A REST API built with Laravel for a Point of Sale system. This API provides endpoints for managing sales, inventory, staff, and more with role-based access control.

## Tech Stack

- Laravel 11
- Laravel Sanctum (Authentication)

## Features & Roadmap

### Authentication & Authorization ✅
- [x] User registration with automatic superadmin role
- [x] Login with token generation
- [x] Protected routes with token authentication
- [x] User profile retrieval
- [x] Logout functionality

### Categories Management 🚀
- [ ] Create categories
- [ ] List all categories
- [ ] Update category details
- [ ] Delete categories
- [ ] Get single category

### Products/Inventory Management 🚀
- [ ] Add new products
- [ ] List all products
- [ ] Update product details
- [ ] Delete products
- [ ] Stock management
- [ ] Product search
- [ ] Get single product

### Transaction Management 🚀
- [ ] Create new sale
- [ ] List all sales
- [ ] Get sale details
- [ ] Refund management 
- [ ] Sales reports
- [ ] Daily/weekly/monthly statistics

### Staff Management 🚀
- [ ] Add staff members
- [ ] List all staff
- [ ] Update staff details
- [ ] Remove staff
- [ ] Role assignment
- [ ] Staff permissions

### Settings 🚀
- [ ] Store details
- [ ] Tax configurations
- [ ] Receipt customization
- [ ] General preferences

## Role-Based Access

The API implements role-based access control with the following roles:
- Superadmin (Full access)
- Admin (Store management)
- Cashier (Sales and basic inventory)
- Staff (Limited access)
