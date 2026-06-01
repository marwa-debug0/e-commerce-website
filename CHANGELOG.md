# Changelog: Bauhaus E-commerce Project

This file tracks all modifications and enhancements made to the Bauhaus E-commerce codebase. It is maintained and updated as new features are added, bugs are resolved, and the project is refined.

---

## [1.1.0] - 2026-06-01 (Completed)
### Added
- **Modernist Admin Panel**:
  - Registered dynamic `AdminMiddleware` checking for `is_admin` privileges on `/admin` prefixed routes.
  - Implemented `AdminController` to generate detailed sales report metrics (Total Revenue, Placed Checkouts Count, Total Products) and compile Best-Selling items and prioritize low-stock alert products.
  - Created a Bauhaus-styled admin panel master layout with responsive navbar controls.
  - Built comprehensive Product CRUD routes (`AdminProductController`) allowing administrators to view, create, edit, and safely delete design items.
- **Inventory Management Core**:
  - Created and ran database migration to add `stock` (integer) and `sku` (unique string) fields to `products`.
  - Added helper methods `isOutOfStock()` and `isLowStock()` to `Product` model.
  - Intercepted Checkout Controller (`index` and `store` methods) to perform real-time verification of stock limits.
  - Wrapped stock decrementing inside a robust transactional block, failing checkouts gracefully with alert notifications on insufficient supply.
- **Ratings & Reviews System**:
  - Built `ReviewController` to handle user submissions for 1-to-5 star ratings and textual comments, including duplicate review prevention/updates.
  - Crafted dynamic star rating gauges and ratings breakdown graphs on the product detail page.
  - Implemented comments history list showing author names and star indicators.
  - Developed a minimalist review submission form for all authenticated users.
- **Advanced Unified Search & Filters**:
  - Enhanced homepage `ProductController` index action with full-text search across titles and descriptions.
  - Enabled price range filtering (`min_price`, `max_price`), availability filtering (`in_stock` only), and three sorting modes (Name A-Z, Price Low-High, Price High-Low).
  - Preserved query context across filters and categories utilizing dynamic query generation.
- **Rule-based Recommendations**:
  - Implemented a "You May Also Like" recommendation engine loading up to 4 matched cards.
  - Built priority mapping loading items in the same category first, and gracefully backfilling using price proximity (+/- 30% price range) if category counts are low.
- **Expanded Test Coverage**:
  - Created `AdminTest.php` and `ReviewTest.php` verifying non-admin access denials, admin dashboard telemetry, and review validation boundaries, bringing automated suite from 25 to 31 passing tests.

## [1.0.0] - 2026-06-01 (Completed)
### Added
- **Categories Architecture**: 
  - Integrated Category models and migrations.
  - Seeded three core categories: `Lighting`, `Accessories`, and `Furniture`.
  - Added category filter navigation to the Homepage (`home.blade.php`).
  - Added filter query parameter support in `ProductController` allowing users to filter items by category slug.
- **Coupons Architecture**:
  - Integrated Coupon models and migrations.
  - Created `CouponController` to apply and remove coupons from the user's session.
  - Seeded test coupons: `BAUHAUS10` (10% off) and `MINIMAL50` ($50 off on minimum $200 purchase).
  - Designed responsive Coupon Apply / Remove UI in the Shopping Cart.
- **Transactional Checkout**:
  - Fully implemented multi-step transactional Checkout in `CheckoutController`.
  - Added dynamic address, country, and postal code shipping forms to `/checkout`.
  - Ensured cart-to-order logic is wrapped inside a robust database transaction (`DB::transaction`).
  - Automated dynamic discount calculation and order mapping based on active session coupon codes.
- **Breeze Compatibility & Authentication**:
  - Added Breeze-compatible Profile routes and views so that users can edit or delete their profile context.
  - Added Breeze redirection/dashboard fallback routing to guarantee authentication flow works.

### Fixed
- **Duplicate Checkout Classes**: Deleted duplicate controller class `app/Http/Controllers/Checkout.php` to avoid PHP class collision with the main controller.
- **Cart Variable Mismatches**: Fixed Eloquent query discrepancies in `cart/index.blade.php` and `checkout/index.blade.php`, replacing incorrect `$cart->items` calls with the proper `$user->carts()` relationship.
- **Test Pipeline**: Added the `RefreshDatabase` trait to `ExampleTest.php` so SQLite memory migrations run successfully before each test case, securing a 100% success rate (25/25 tests passing).

### Changed
- **Aesthetic Refinements**: Styled global alert flash banners (success and error) with the signature minimal Bauhaus palette (`bauhaus-black`, `bauhaus-white`, sharp solid borders).
