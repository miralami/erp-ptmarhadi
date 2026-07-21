# Revision Plan — ERP PT Marhadi

## Assumptions / Open Notes

- **Faktur Pajak** — apakah "dibuat faktur" di flow invoice = e-Faktur resmi DJP, atau hanya dokumen internal? *Assume: dokumen internal dulu.*
- **PPN 1.1%** — perlu konfirmasi dengan akuntan apakah ini DPP Nilai Lain (Freight Forwarding PMK 71/2022). *Assume: yes, 1.1% dari nilai bruto.*
- **Kubikasi + Unit** — untuk Darat+Laut hybrid, assume dua SP terpisah untuk sekarang.
- **Driver dropdown** — masih text input sampai Phase 7 (Auth). Setelah ada user roles, jadi dropdown dari users filter role DRIVER.
- **Kendaraan sewa** — data kendaraan diinput manual per SP (`vehicle_plate_manual`, `vehicle_type_manual`), tidak disimpan ke tabel `vehicles`.

---

## Backend Status (sudah jadi)

| Komponen | Status |
|---|---|
| `Vehicle` model + migration | ✅ |
| `DeliveryExpense` model + migration | ✅ |
| `CompanySetting` model + migration | ✅ |
| `VehicleType` enum (FUSO, TRONTON, CDD, CDE, PICKUP, WINGBOX, DUMP_TRUCK, TRAILER, OTHER) | ✅ |
| `OrderCategory` enum (DARAT, LAUT) | ✅ |
| `VehicleSource` enum (OWNED, RENTED) | ✅ |
| `OrderStatus` enum (ORDER_RECEIVED, SCHEDULED, IN_TRANSIT, COMPLETED, CANCELLED) | ✅ |
| `DeliveryStatus` enum | ✅ |
| `VehicleService` | ✅ |
| `SuratPengirimanController` (`index`, `create`, `store`, `show`, `updateStatus`, `updateDelivery`, `uploadPhotos`, `cetak`) | ✅ |
| `VehicleController` CRUD | ✅ |
| `CompanySettingController` | ✅ |
| Route SP (`/surat-pengiriman`) | ✅ |
| Route kendaraan (`/kendaraan`) | ✅ |
| Route pengaturan (`/pengaturan`) | ✅ |
| Order fields: `received_by`, `origin_company`, `origin_city`, `destination_city`, `category`, `vehicle_source`, `customer_po_number`, `customer_spb_number` | ✅ |
| OrderItem fields: `unit` (ganti `quantity`), `kubikasi`, `max_slot`, `police_fee`, `threshold_exceeded` | ✅ |
| Delivery fields: `vehicle_id`, `vehicle_plate_manual`, `vehicle_type_manual`, `uang_jalan`, `photo_muat`, `photo_bongkar`, `photo_surat_jalan` | ✅ |
| Customer field: `npwp` | ✅ |
| Invoice fields: `customer_po_number`, `customer_spb_number`, `subtotal`, `ppn_rate`, `ppn_amount` | ✅ |

---

## Yang Perlu Diimplementasikan

### Task 1 — SP Index: Pending Section

**File:** `app/Http/Controllers/SuratPengirimanController.php`

Tambah query untuk SP yang butuh data pengiriman (status ORDER_RECEIVED dan belum punya delivery dengan driver):

```php
$pendingOrders = Order::with('customer', 'delivery')
    ->where('status', OrderStatus::ORDER_RECEIVED)
    ->whereDoesntHave('delivery', fn($q) => $q->whereNotNull('driver_name'))
    ->latest()
    ->get();
```

Pass ke view.

**File:** `resources/views/surat-pengiriman/index.blade.php`

Tambah section di atas tabel:

```
[Perlu Aksi] (hanya muncul kalau ada pendingOrders)
┌─ No SP · Customer · Kota Asal → Tujuan ─────── [Isi Pengiriman →] ─┐
└──────────────────────────────────────────────────────────────────────┘

[Semua Surat Pengiriman] ← tabel existing
```

---

### Task 2 — SP Show: Delivery Section Selalu Muncul

**File:** `resources/views/surat-pengiriman/show.blade.php`

Hilangkan `@if ($order->delivery)` wrapper di section Detail Pengiriman. Form harus selalu tampil, dengan kondisi:
- Kalau delivery belum ada: form kosong, submit akan create delivery via `updateDelivery`
- Kalau delivery sudah ada: form terisi dari existing data

---

### Task 3 — SP Show: Driver Field

**File:** `resources/views/surat-pengiriman/show.blade.php`

Ganti input text driver menjadi lebih jelas:
- Text input tetap (pre-auth)
- Placeholder: "Nama driver"
- Label: "Driver"

---

### Task 4 — SP Show: Kendaraan Toggle OWNED/RENTED

**File:** `resources/views/surat-pengiriman/show.blade.php`

Tambah Alpine.js toggle yang mengontrol tampilan field kendaraan berdasarkan `vehicle_source`:

- `vehicle_source` = OWNED: tampilkan dropdown kendaraan dari tabel `vehicles`
- `vehicle_source` = RENTED: tampilkan input `vehicle_plate_manual` + dropdown `vehicle_type_manual` (dari enum)
- Ambil `vehicle_source` dari `$order->vehicle_source` (field sudah ada di Order)

---

### Task 5 — SP Show: Vehicle Type Dropdown

**File:** `resources/views/surat-pengiriman/show.blade.php`

Ganti `vehicle_type_manual` dari text input ke dropdown `VehicleType` enum.

---

### Task 6 — SP Show: Status Dropdown Filter

**File:** `resources/views/surat-pengiriman/show.blade.php`

Dropdown status di sidebar harus menampilkan hanya `allowedTransitions()` (sama seperti `orders/show.blade.php`), bukan semua status.

---

### Task 7 — SP Show: Foto Upload ke File, Bukan URL

**File:** `app/Http/Controllers/SuratPengirimanController.php`

Method `uploadPhotos` — ganti validasi dari `required|string` ke:

```php
'photos' => 'required|array',
'photos.*' => 'required|image|max:5120',
```

Store file ke `storage/app/public/sp-photos/{order_id}/`. Simpan array of paths ke JSON column.

**File:** `resources/views/surat-pengiriman/show.blade.php`

- Form perlu `enctype="multipart/form-data"`
- Ganti `<textarea>` dengan `<input type="file" multiple accept="image/*">`
- Tampilkan preview gambar dari path storage (bukan URL)

**Terminal:**
```bash
php artisan storage:link
```

---

### Task 8 — SP Edit: Route + Controller + View

**File:** `routes/web.php`

Tambah di dalam group SP:

```php
Route::get('{surat_pengiriman}/edit', 'edit')->name('edit');
Route::put('{surat_pengiriman}', 'update')->name('update');
```

**File:** `app/Http/Controllers/SuratPengirimanController.php`

Tambah method `edit()` dan `update()`:

- `edit()`: load order + items + customers, render view edit
- `update()`: validasi + update order header + items (sync/delete old + create new), activity log

**File:** `resources/views/surat-pengiriman/edit.blade.php`

Copy dari `create.blade.php` tapi pre-filled dengan data existing. Bisa edit:
- Customer, tanggal, received_by, origin, destination, category, vehicle_source
- PO/SPB number
- Items: nama, unit/kubikasi, harga, max_slot, police_fee
- Notes

**File:** `resources/views/surat-pengiriman/show.blade.php`

Tambah tombol "Edit Order" di header (bersebelahan dengan tombol Cetak).

---

### Task 9 — SP Create: Origin Company Toggle

**File:** `resources/views/surat-pengiriman/create.blade.php`

`origin_company` — tambah toggle:
- Checkbox "PT Marhadi" (default checked)
- Kalau checked, autofill dari `company_settings`
- Kalau unchecked, muncul input text manual

---

### Task 10 — SP Create: Received By

**File:** `resources/views/surat-pengiriman/create.blade.php`

Text input tetap, tapi ganti placeholder jadi lebih informatif: "Nama yang menerima order"

---

### Task 11 — Net Order / Profitability

**File:** `resources/views/surat-pengiriman/show.blade.php`

Diagram nett sudah ada di sidebar card "Ringkasan Keuangan". Tinggal pastikan:
- Tagihan (dari invoice_total)
- Uang Jalan (dari delivery.uang_jalan)
- Biaya Operasional (dari expenses sum)
- Nett = Tagihan - (Uang Jalan + Biaya Operasional)

---

## Execution Order

```
1. Task 6  → SP Show: status dropdown filter           (show.blade.php)
2. Task 8  → SP Edit: route + controller + view         (baru)
3. Task 1  → SP Index: pending query + section           (controller + index.blade.php)
4. Task 2  → SP Show: delivery section always visible    (show.blade.php)
5. Task 3  → SP Show: driver field                       (show.blade.php)
6. Task 4  → SP Show: kendaraan toggle                   (show.blade.php)
7. Task 5  → SP Show: vehicle type dropdown              (show.blade.php)
8. Task 9  → SP Create: origin company toggle            (create.blade.php)
9. Task 10 → SP Create: received by placeholder          (create.blade.php)
10. Task 7 → SP Show: foto upload actual files           (controller + show.blade.php)
11. Task 11 → Verify nett calculation already works      (show.blade.php)
```

---

## Bug Fixes (sprint terpisah nanti)

- `Timeline` component — references dead enum cases `INVOICE_CREATED`, `INVOICE_SENT`, `UNPAID`, `PAID` (tidak ada di `OrderStatus`)
- `DashboardService` — `strftime()` SQLite-only, perlu DB-agnostic
- `PaymentMethod` — belum ada method `icon()`
- `/orders` dan `/deliveries` route lama — deprecated setelah SP stabil
