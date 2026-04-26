# Patch Notes

## 04/04/26: Initial Laravel Setup
- Set up initial Laravel project requirements
- Created and connected MySQL/MariaDB database (`crafted_pieces`)

## 13/04/26: Frontend Structure Setup
- Created Data Dictionary (NOT FINALIZED)
- Setup Views for Home, Shop, Custom Order, Cart
- Setup Components for Authentication Modal, Cart Items, Footer, Nav Bar, Product Card

## 17/04/26: Admin Panel & Store Refactor
- Added full admin dashboard with separated layout and routes (`/admin`)
- Created AdminController and StoreController
- Added admin pages (products, orders, categories, dashboard, etc.)
- Added new store pages (product detail, checkout, my-orders, about)
- Introduced chatbot widget
- Refactored components to PascalCase (AuthModal, CartItem, NavBar, ProductCard)
- Added centralized ShowcaseData provider
- Updated routes and layouts for admin/store separation
- Improved UI consistency and navigation across storefront

## 18/04/26: Major Features, Database Integration, and System Enhancements
- Implemented authentication system (login/register/logout)
- Added role system (`customer`, `staff`)
- Protected admin routes (staff only)
- Protected customer routes (checkout, my-orders)
- Updated navigation for auth states and roles
- Migrated products, categories, and tags to database
- Added models: Product, Category, ProductTag
- Added migrations and pivot relationships
- Updated controllers to use database instead of ShowcaseData
- Seeded initial product and category data
- Added database tables for orders, order_items, and quotations
- Added models: Order, OrderItem, Quotation
- Seeded demo orders and quotations
- Updated admin pages to read from database
- Linked orders and quotations to users via `user_id`
- Migrated `/my-orders` to database-backed data
- Ensured customer-only access to owned records
- Implemented order creation from checkout
- Implemented quotation creation from custom order form
- Added validation for checkout and custom order flows
- Assigned ownership to authenticated users for orders and quotations
- Redirected successful submissions to `/my-orders`
- Added cart and cart_items tables
- Implemented add-to-cart functionality
- Connected cart to authenticated users
- Integrated cart with checkout flow
- Cleared cart after successful checkout
- Added quantity update for cart items
- Added remove item functionality from cart
- Enforced ownership validation for cart actions
- Added cart item count badge in navigation
- Prevented checkout with empty cart
- Fixed guest access issues in checkout and custom-order pages
- Added login-required modal for restricted actions
- Added customer account page
- Enabled updating name and email
- Added account link in navigation
- Fixed admin product creation flow
- Added full create and edit functionality for products
- Added image upload support for products
- Displayed product images in admin list
- Fixed product image rendering via storage symlink
- Added product delete functionality in admin
- Added delete confirmation UI
- Ensured uploaded images are removed on delete
- Added admin product search (name, slug)
- Added filtering by category and status
- Combined admin filters into single query system

## 21/04/26: Major Remake Part 1:
- Remade Routing Structure
- Remade Role System (added role to users table: user, owner, created RoleMiddleware)
- Remade centralized layout system (layouts.store)
- Remade Navigation Bar (dynamic rendering based on auth state and roles)
- Login and Register now use a single auth-modal component
- Improved Authentication UX (modal, transitions, blur background, better UI)
- Added animated form switching (login ↔ register) with reset and auto-open on validation errors
- Added required privacy policy and terms checkbox with disabled register button until checked
- Removed duplicate / unused files