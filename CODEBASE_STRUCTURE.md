# Codebase Structure — ERP PT Marhadi

## Migrations (database/migrations/)

| # | File | Purpose |
|---|------|---------|
| 1 | `0001_01_01_000000_create_users_table.php` | Laravel `users`, `password_reset_tokens`, `sessions` |
| 2 | `0001_01_01_000001_create_cache_table.php` | Laravel `cache`, `cache_locks` |
| 3 | `0001_01_01_000002_create_jobs_table.php` | Laravel `jobs`, `job_batches`, `failed_jobs` |
| 4 | `2026_07_16_124623_create_customers_table.php` | Initial `customers` |
| 5 | `2026_07_16_124642_create_orders_table.php` | Initial `orders` |
| 6 | `2026_07_17_000001_fix_orders_constraints.php` | Fixed FK/index on orders |
| 7 | `2026_07_21_000001_modify_customers_table.php` | Renamed name→company_name, added contact_person, notes |
| 8 | `2026_07_21_000002_create_order_items_table.php` | Created `order_items` |
| 9 | `2026_07_21_000003_modify_orders_table.php` | Cleaned up orders columns |
| 10 | `2026_07_21_000004_create_deliveries_table.php` | Created `deliveries` |
| 11 | `2026_07_21_000005_create_invoices_table.php` | Created `invoices` |
| 12 | `2026_07_21_000006_create_payments_table.php` | Created `payments` |
| 13 | `2026_07_21_000007_create_activity_logs_table.php` | Created `activity_logs` |

## Table Schemas

### customers
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| company_name | string | renamed from `name` |
| contact_person | string, nullable | |
| email | string, nullable | |
| phone | string, nullable | |
| address | text, nullable | |
| notes | text, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### orders
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| customer_id | bigint FK | restrictOnDelete → customers |
| order_number | string, unique | e.g. ORD-260721-0001 |
| order_date | date | |
| status | string | cast to OrderStatus |
| notes | text, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### order_items
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| order_id | bigint FK | cascadeOnDelete → orders |
| product_name | string | |
| quantity | integer | |
| price | decimal(15,2) | |
| created_at | timestamp | |
| updated_at | timestamp | |

### deliveries
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| delivery_number | string, unique | e.g. DEL-260721-0001 |
| order_id | bigint FK | restrictOnDelete → orders |
| delivery_date | date | |
| driver_name | string, nullable | |
| vehicle_number | string, nullable | |
| delivery_note_number | string, nullable | |
| product_name | string | |
| quantity | integer | |
| status | string | cast to DeliveryStatus |
| notes | text, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### invoices
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| invoice_number | string, unique | e.g. INV-260721-0001 |
| order_id | bigint FK | restrictOnDelete → orders |
| customer_id | bigint FK | restrictOnDelete → customers |
| invoice_date | date | |
| due_date | date | |
| invoice_total | decimal(15,2) | |
| paid_amount | decimal(15,2) | default 0 |
| status | string | cast to InvoiceStatus |
| notes | text, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### payments
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| payment_number | string, unique | e.g. PAY-260721-0001 |
| invoice_id | bigint FK | restrictOnDelete → invoices |
| payment_date | date | |
| amount | decimal(15,2) | |
| payment_method | string | cast to PaymentMethod |
| reference_number | string, nullable | |
| notes | text, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### activity_logs
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | auto-increment |
| user_id | bigint FK, nullable | nullOnDelete → users |
| module | string | |
| record_id | bigint, nullable | polymorphic |
| action | string | |
| description | text, nullable | |
| old_value | json, nullable | |
| new_value | json, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

## Models (app/Models/)

### Order
- **Fillable:** `customer_id`, `order_number`, `order_date`, `status`, `notes`
- **Casts:** order_date→date, status→OrderStatus
- **Relations:** customer (BelongsTo Customer), items (HasMany OrderItem), deliveries (HasMany Delivery), invoice (HasOne Invoice)
- **Accessors:** `total` = sum of item.qty × item.price

### OrderItem
- **Fillable:** `order_id`, `product_name`, `quantity`, `price`
- **Casts:** price→decimal:2
- **Relations:** order (BelongsTo Order)
- **Accessors:** `subtotal` = qty × price

### Customer
- **Fillable:** `company_name`, `contact_person`, `email`, `phone`, `address`, `notes`
- **Relations:** orders (HasMany Order), invoices (HasMany Invoice), payments (HasManyThrough Payment via Invoice)

### Delivery
- **Fillable:** `delivery_number`, `order_id`, `delivery_date`, `driver_name`, `vehicle_number`, `delivery_note_number`, `product_name`, `quantity`, `status`, `notes`
- **Casts:** delivery_date→date, status→DeliveryStatus
- **Relations:** order (BelongsTo Order)

### Invoice
- **Fillable:** `invoice_number`, `order_id`, `customer_id`, `invoice_date`, `due_date`, `invoice_total`, `paid_amount`, `status`, `notes`
- **Casts:** invoice_date→date, due_date→date, invoice_total→decimal:2, paid_amount→decimal:2, status→InvoiceStatus
- **Relations:** order (BelongsTo Order), customer (BelongsTo Customer), payments (HasMany Payment)
- **Accessors:** `remaining` = max(0, invoice_total - paid_amount), `isOverdue`

### Payment
- **Fillable:** `payment_number`, `invoice_id`, `payment_date`, `amount`, `payment_method`, `reference_number`, `notes`
- **Casts:** payment_date→date, amount→decimal:2, payment_method→PaymentMethod
- **Relations:** invoice (BelongsTo Invoice)

### ActivityLog
- **Fillable:** `user_id`, `module`, `record_id`, `action`, `description`, `old_value`, `new_value`
- **Casts:** old_value→array, new_value→array
- **Relations:** user (BelongsTo User)

### User
- Extends Authenticatable
- **Fillable:** `name`, `email`, `password`
- **Hidden:** `password`, `remember_token`
- **Casts:** email_verified_at→datetime, password→hashed

## Enums (app/Enums/)

All backed strings with label() (Indonesian), color() (Tailwind), icon() (Lucide).

### OrderStatus
ORDER_RECEIVED → DELIVERY_SCHEDULED → DELIVERED → DELIVERY_NOTE_RETURNED → WAITING_PO → CANCELLED
State machine via allowedTransitions()/canTransitionTo()

### DeliveryStatus
SCHEDULED | IN_TRANSIT | DELIVERED | PARTIALLY_DELIVERED | RETURNED

### InvoiceStatus
DRAFT | SENT | OVERDUE | PARTIALLY_PAID | PAID | VOID

### PaymentMethod
TRANSFER | CASH | CHEQUE | GIRO — missing icon() method

## Services (app/Services/)

| Service | Methods |
|---------|---------|
| OrderStatusService | transition(Order, OrderStatus): Order |
| InvoiceService | createFromOrder(Order, array): Invoice, updateStatusFromPayments(Invoice): Invoice, markAsSent(Invoice): Invoice |
| PaymentService | recordPayment(Invoice, array): Payment |
| DocumentNumberService | generate(prefix, table, column): string |
| ActivityLogService | log(module, recordId, action, description, oldValue, newValue): ActivityLog |
| DashboardService | getKpis(), getMonthlyRevenue(), getInvoiceStatusDistribution(), getTopCustomers(), getPaymentTrend(), getRecentOrders(), getRecentPayments(), getRecentActivities() |

## Controllers (app/Http/Controllers/)

| Controller | DI | Methods |
|-----------|-----|---------|
| OrderController | DocumentNumberService, OrderStatusService, ActivityLogService | index, create, store, show, edit, update, updateStatus |
| CustomerController | ActivityLogService | index, create, store, show, edit, update, destroy |
| DeliveryController | DocumentNumberService, ActivityLogService | index, create, store, show, edit, update |
| InvoiceController | InvoiceService, ActivityLogService | index, create, store, show, edit, update, send, pdf |
| PaymentController | PaymentService, ActivityLogService | index, create, store, show, edit, update |
| DashboardController | DashboardService | index |
| ActivityLogController | (none) | index, show |

## Routes (routes/web.php)

- `/` → Dashboard@index
- Resource routes for: customers, orders, deliveries, invoices, payments
- Extra: `POST orders/{order}/status` (status transition), `POST invoices/{invoice}/send`, `GET invoices/{invoice}/pdf`
- `GET activity-logs` + `GET activity-logs/{activityLog}`
- No auth middleware on any route

## View Components (app/View/Components/)

- AppLayout → components.layouts.app
- Badge(label, color) → components.badge
- Card → components.card
- DeliveryTimeline(status) → components.timeline-delivery
- PageHeader(title, description) → components.page-header
- StatCard(title, value, icon, color, description) → components.stat-card
- Table(headers, striped) → components.table
- Timeline(status) → components.timeline (references dead enum cases: INVOICE_CREATED, INVOICE_SENT, UNPAID, PAID)

## Views (resources/views/)

```
activity-logs/  (index, show)
components/  (badge, card, card, layouts/app, page-header, stat-card, table, timeline, timeline-delivery)
customers/  (create, edit, index, show)
dashboard/  (index)
deliveries/  (create, edit, index, show)
invoices/  (create, edit, index, pdf, show)
orders/  (create, edit, index, show)
payments/  (create, edit, index, show)
```

## Form Requests (app/Http/Requests/)

Store/Update per module: Customer, Order, Delivery, Invoice, Payment

## Plan Execution Order & Revisions

Per plan.md execution order:
1. Phase 0 → DB foundation (migrations)
2. Phase 4 → Company settings
3. Phase 5 → Customer NPWP
4. Phase 2 → Vehicle management
5. Phase 1 → SP (Surat Pengiriman)
6. Phase 3 → Invoice revision
7. Phase 6 → Net rekap + dashboard
8. Phase 8 → Bug fixes
9. Phase 7 → Auth (last)
