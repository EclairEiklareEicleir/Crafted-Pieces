# Contribution Report

## Team Members and Roles

| Member | Role |
|---|---|
| Harry B. Lawang | Full-Stack Developer, System Architect, Backend & Core Systems Lead |
| Charles Jefferson A. Betonio | Frontend Developer, UI/UX Designer, Admin Content Systems Contributor |
| Alrazel R. Llave | Frontend Developer, UI Structuring & Early System Support Contributor |

---

# Contribution Summary (Based on Git History & System Modules)

This contribution report is based on commit history analysis, module ownership, and system-level implementation across the Laravel application.

---

# 🧠 Harry B. Lawang — Full-Stack System Architect (Primary Developer)

Harry B. Lawang is the primary contributor responsible for **core backend systems, architecture design, and full application integration**.

Git evidence:
- Author: `to0tskie`
- Largest system-wide controller and model ownership
- PayMongo + service layer implementation
- Full order/cart/checkout architecture

---

## 🔧 Core Backend Systems

### Order & Checkout Engine
- OrderController
- CheckoutController
- Order lifecycle handling (creation, tracking, status updates)
- OrderItem model implementation
- Order history system (admin + user)

### Cart System
- CartController
- Cart model system design
- Cart-to-checkout workflow
- Quantity and item management logic

### Payment System
- PayMongoController
- CustomOrderPayMongoController
- Payment status handling system
- Transaction flow for orders and custom commissions

### Custom Order System
- CustomOrderController
- CustomOrderRequest model ownership
- CustomOrderMessage system
- Admin custom order management

---

## 🏗 Admin System (Full Ownership)

- AdminDashboardController
- AdminOrderController
- AdminOrderHistoryController
- AdminProductController
- AdminCategoryController
- AdminUserController
- AdminDashboard analytics system

Includes:
- Order management workflows
- Product CRUD system
- User management system
- Order history tracking
- Admin operational control layer

---

## 🧱 Database & Models (Core System Design)

Owned system-wide models:
- User
- Product
- ProductVariant
- Category
- Cart
- Order
- CustomOrderRequest
- CustomOrderMessage
- Notification

Responsibilities:
- Schema design (migrations)
- Relationship mapping (Eloquent ORM)
- Data integrity design
- System-wide model architecture

---

## ⚙ Service & Infrastructure Layer

- PayMongo payment integration logic
- Checkout validation pipelines
- Order computation logic
- Notification system integration
- SearchController (system-wide query handling)

---

## 🧭 System Architecture

- Laravel MVC full structure design
- Middleware-based role protection
- Admin vs Storefront routing separation
- Full backend business logic implementation
- System integration across all modules

---

# 🎨 Charles Jefferson A. Betonio — Frontend & UI/UX Developer

Charles focused on **user interface development, admin content systems, and frontend UX refinement**.

Git evidence:
- Author: `charlezb`

---

## 🖼 Admin Content Systems

- AdminAboutSectionController
- AdminFaqController
- AboutSection model
- Faq model

Includes:
- About page content management system
- FAQ CRUD system (create, edit, delete, display)
- Admin content publishing workflow

---

## 🎨 Frontend UI / UX Development

- Authentication modal UI system
- Navbar UI improvements and structure
- Footer UI and navigation updates
- Checkout page UI adjustments
- Storefront layout refinements

---

## 📄 Static & Legal Pages

- Privacy Policy page
- Terms of Service page
- About page user view updates

---

## 🔐 Authentication & UX

- AuthController UI-related updates
- Login/Register UX improvements
- Form validation and frontend feedback improvements

---

# 🧱 Alrazel R. Llave — Frontend Structuring & System Support Contributor

Git evidence:
- Author: `EclairEiklareEicleir`

---

## 🧩 System-Wide Frontend & Backend Exposure

Alrazel contributed across **multiple system layers**, primarily during structuring, feature expansion, and UI system development phases.

---

## 🧱 Controller-Level Contributions

Participated in development and maintenance of:

### Core Systems
- CartController (cart workflow support)
- CheckoutController (checkout flow integration)
- OrderController (order processing support)
- HomeController (storefront logic support)
- ProductController (product display and interaction layer)
- ReviewController (review system integration)
- NotificationController (user notification system)

### Custom Order System
- CustomOrderController
- CustomOrderRequestController
- CustomOrderPayMongoController

### Admin System Support
- AdminDashboardController
- AdminOrderController
- AdminProductController
- AdminCategoryController
- AdminSettingController
- AdminCustomOrderController
- AdminUserController

### Extended Systems
- ChatbotController (admin chatbot system)
- SearchController (search functionality system)
- StaticController (static page handling)

---

## 🧱 Models & System Structure

Contributed to system-wide model development:

- Cart, CartItem
- Category
- Product, ProductVariant
- Order, OrderItem
- CustomOrderRequest, CustomOrderMessage
- Notification
- Review
- Setting
- User
- YarnColor
- ChatbotFaq

---

## 🎨 Frontend & UI System Contributions

- Authentication modal enhancements
- Navbar and component-based UI system
- Cart item UI components
- Product card components
- Custom order thread UI system
- Status badge UI system
- Chatbot UI component
- Error page UI components
- Layout system improvements (admin + storefront)

---

## 🧭 System Integration Support

- Helped integrate frontend views with backend controllers
- Assisted in connecting models to UI workflows
- Participated in feature-level system testing and refinement
- Contributed to layout consistency across storefront and admin panels
- Supported multi-module feature expansion during later development phases

---

# 📊 Contribution Breakdown (System View)

| Area | Primary Contributor |
|---|---|
| Backend Architecture | Harry B. Lawang |
| Payment System (PayMongo) | Harry B. Lawang |
| Cart & Checkout Engine | Harry B. Lawang |
| Order Management System | Harry B. Lawang |
| Admin Dashboard System | Harry B. Lawang |
| Database Design & Models | Harry B. Lawang |
| UI/UX Design | Charles Jefferson A. Betonio |
| Admin Content Systems (FAQ, About) | Charles Jefferson A. Betonio |
| Frontend Components & UI System | Alrazel R. Llave |
| System Structuring & Multi-module Support | Alrazel R. Llave |

---

# 🧾 Overall Project Summary

The system was developed as a full-stack Laravel application with clearly distributed responsibilities:

- Harry B. Lawang implemented the core system architecture, backend logic, database design, and payment/order infrastructure.
- Charles Jefferson A. Betonio focused on frontend UI/UX development and admin content management systems.
- Alrazel R. Llave contributed to frontend structuring, multi-module support, and system-wide integration assistance across various components.

The final system is a complete e-commerce and custom order platform featuring authentication, product management, cart and checkout systems, payment processing, admin dashboards, and a custom commission workflow system.
