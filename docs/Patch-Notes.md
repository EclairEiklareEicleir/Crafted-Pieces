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

## 28/04/26: Major Remake Part 2:
- Expanded Laravel application architecture and routing structure
- Added controller system for account management, storefront/shop handling, cart management
- Added storefront pages: Home, Shop, Product View, Cart
- Remade reusable Blade components: Navbar, Product Cards, Cart Items, Authentication Modal
- Improved navigation rendering based on authentication state and user roles
- Remade Eloquent Model: Product, Category, Cart, CartItem

## 02/05/26: Major Remake Part 3:
- Added storefront pages: Account
- Remade shopping cart infrastructure: carts, cart items, dynamic quantity handling, session/user cart handling
- Remade product and category database system
- Remade order infrastructure: orders, order items, checkout flow, payment flow, order success handling, order tracking system
- Remade database migrations for categories, products, carts, cart items
- Fixed 403 authorization conflicts during commission acceptance flow

## 06/05/26: Major Remake Part 4:
- Remade admin pages: Dashboard, Commission Management, Order Management, Custom Commision Management
- Added storefront pages: Checkout, Payment, Order Tracking, Order History, Custom Commission Pages
- Improved backend validation consistency across controllers
- Improved route grouping and middleware organization
- Added Eloquent models: Order, OrderItem, CustomOrderRequest, CustomOrderMessage

## 09/05/26: Major Remake Part 5:
- Added database migrations for orders, order items, custom commission: requests, commission messages, quotation fields, and payment deadline support
- Added database seeders: products, categories, users, analytics/demo data
- Added custom commission infrastructure: commission requests, commission messaging/chat system, quotation handling, admin notes, payment workflow, commission status lifecycle
- Added controller system and UI for checkout/payment processing, order tracking, custom commission workflow, admin commission handling, admin order management, and admin dashboard analytics

## 15/05/26: Major Remake Part 5:
- Added customer ↔ owner messaging/chat system for commission discussions
- Added centralized commission statuses: pending, quoted, awaiting_confirmation, awaiting_payment, paid, in_progress, completed, rejected
- Added universal payment processing system for regular orders and custom commissions
- Added payment computation system: platform fee calculation, deposit calculation, remaining balance calculation
- Added payment deadline system for custom commissions with automatic expiration/rejection handling
- Added paid timestamp tracking and payment validity helper methods
- Added secure authorization handling for payment access, ticket ownership, order ownership, and admin-only routes
- Added admin-side quotation approval and commission acceptance workflow
- Improved storefront UI consistency, reusable layouts, and authentication-integrated UI behavior
- Improved cart/product rendering components and overall frontend maintainability
- Refactored multiple Blade views, backend flows, and route handling for maintainability
- Removed duplicate and unused files/components/routes

## 16/05/26: Admin System Expansion, Seeder Refactor and Payment Schema Update
- Refactored admin order management controller to improve manual order creation and streamline order workflow handling
- Updated admin order UI (index, show, and create views) to improve usability 
- Added CRUD (bulk and individual) order actions to admin side
- Improved admin dashboard layout and analytics display with refined statistical breakdowns for orders, commissions, and revenue
- Updated commission admin views to ensure consistent status handling and UI behavior across workflows
- Enhanced user-side custom order view to match updated commission workflow structure
- Refactored database seeders (UserSeeder, CategorySeeder, ProductSeeder, AnalyticSeeder) to align with current system structure and generate more realistic test data
- Introduced admin manual order creation interface to support direct order insertion workflows
- Improved route organization and updated admin routing structure to support expanded order, commission, and dashboard features
- Added foundational backend support for improved payment tracking in custom commission transactions
- Improved maintainability of admin modules through better alignment between controllers, views, and route structure

## 17/05/26: Admin Product System Overhaul (CRUD + Storage Image Integration)
- Combined admin product tab and category tab under one tab: Products
- Introduced new admin product management views under resources/views/admin/products/ for create and edit workflows
- Added 2 controllers for admin product management: AdminCategoryController and AdminProductController
- Updated routing system to support product and category management
- Remade image logic: now connects to the storage folder and utilizes it for the product images
- Updated product and product-card views to handle new image logic

- Remade Footer

- Introduced centralized PricingService to unify all checkout, order, and receipt calculations
- Refactored checkout and payment flows to remove duplicated pricing logic and rely on service layer
- Unified order view and receipt output to ensure 1:1 consistent totals across UI and PDF
- Added download receipt feature tied to OrderController using PricingService calculations
- Created settings system foundation for dynamic platform fee, VAT, and delivery fee configuration
- Updated custom order ticket flow to properly support base price, payment status, and final pricing logic
- Cleaned up routes and views to align checkout, payment, and order display structure
- Added SettingSeeder for default pricing configuration bootstrap
- Updated composer.json / lock due to dependency alignment for PDF + service changes


- Ui Update

## 21/05/26: Minor Navbar Modification
- Modified Navbar: Switched account and cart button, converted My Orders to a group dropdown(Custom Orders and Orders)

- Added Global Alert UI
- Added Password Update in Accounts