# ERP PT Marhadi

Laravel 13 ERP for Indonesian trading/distribution company. Order-to-cash: orders → deliveries → invoices → payments. UI in Bahasa Indonesia.

## Stack

- PHP 8.3, Laravel 13, SQLite (dev), MySQL (prod-ready)
- Blade + Alpine.js + Tailwind CSS v4 + Lucide icons
- Vite, Laravel Pint (style), PHPUnit

## Commands

```bash
composer run setup   # first-time: install, .env, migrate, npm build
composer run dev     # artisan serve + queue + pail + vite dev (concurrent)
composer run test    # config:clear + artisan test (SQLite :memory:)
npm run build        # production assets
```

## Architecture

```
app/
  Controllers/   # thin — delegate to Services
  Services/      # all business logic
  Models/        # Eloquent + relationships
  Enums/         # backed string enums with label()/color()/icon()
  Requests/      # Store + Update per module
  View/Components/
resources/views/ # Blade; mirrors module structure
routes/web.php   # all routes (no API, no auth middleware yet)
```

## Modules

| Module | Model | Key fields |
|--------|-------|------------|
| Orders | `Order` | `order_number`, `status` (enum), `customer_id` |
| Deliveries | `Delivery` | `delivery_number`, `driver_name`, `vehicle_number` |
| Invoices | `Invoice` | `invoice_number`, `invoice_total`, `paid_amount`, `status` |
| Payments | `Payment` | `payment_number`, `amount`, `payment_method` |
| Customers | `Customer` | `company_name`, `contact_person`, `email` |
| Activity Log | `ActivityLog` | `module`, `action`, `old_value`, `new_value` |

## Enums

All status enums are backed strings with `label()` (Indonesian), `color()` (Tailwind), `icon()` (Lucide).

- `OrderStatus`: `ORDER_RECEIVED → DELIVERY_SCHEDULED → DELIVERED → DELIVERY_NOTE_RETURNED → WAITING_PO → CANCELLED`
  - Enforced state machine via `allowedTransitions()` / `canTransitionTo()`
- `DeliveryStatus`: `SCHEDULED | IN_TRANSIT | DELIVERED | PARTIALLY_DELIVERED | RETURNED`
- `InvoiceStatus`: `DRAFT | SENT | OVERDUE | PARTIALLY_PAID | PAID | VOID`
- `PaymentMethod`: `TRANSFER | CASH | CHEQUE | GIRO`

## Services

| Service | Responsibility |
|---------|---------------|
| `OrderStatusService` | Enforces Order enum state machine |
| `InvoiceService` | Create from order, auto-update status from payments |
| `PaymentService` | Record payment, cascade invoice status |
| `DocumentNumberService` | `{PREFIX}-{yymmdd}-{0001}` with DB lock |
| `ActivityLogService` | Audit trail with old/new values |
| `DashboardService` | KPIs, charts, top customers |

## Conventions

- Document numbers: `INV-260721-0001`, `PAY-260721-0001` — generated in DB transaction with lock
- Controllers inject services via constructor DI; keep controllers thin
- Every status enum must implement `label()`, `color()`, `icon()`
- Blade components live in `app/View/Components/` + `resources/views/components/` — reuse `Badge`, `StatCard`, `PageHeader`, `Timeline`
- Routes: kebab-case (`activity-logs`); views: kebab-case dirs; DB columns: snake_case
- Log all record mutations via `ActivityLogService` (old/new values)
- No auth middleware on routes yet — add before production

## Codebase Reference

Full detailed structure (migrations, schemas, models, enums, services, controllers, routes, views, components) stored in `CODEBASE_STRUCTURE.md`. Read it for complete context before making changes.

## Active Plan

`plan.md` in root — 9-phase revision plan. Execution order: 0→4→5→2→1→3→6→8→7.
