# DATABASE SCHEMA

# E-REPORTING INSPEKSI FASILITAS PELABUHAN

Version: 1.0

Database Engine: MySQL 8.0+

Framework: Laravel 12

Charset: utf8mb4

Collation: utf8mb4_unicode_ci

---

## CONVENTIONS

| Convention | Rule |
|---|---|
| Primary Key | `id` — `BIGINT UNSIGNED AUTO_INCREMENT` |
| Foreign Key | `{related_table_singular}_id` — `BIGINT UNSIGNED` |
| Timestamps | `created_at`, `updated_at` — `TIMESTAMP NULL` |
| Soft Deletes | Not used (status-based lifecycle instead) |
| String Default | `VARCHAR(255)` unless specified |
| Boolean Default | `BOOLEAN DEFAULT false` |
| Enum Storage | `VARCHAR(50)` with PHP Backed Enum validation |

---

## ENUM DEFINITIONS

### UserRole

| Value | Description |
|---|---|
| `admin` | Full system access |
| `inspector` | Creates damage reports |
| `supervisor` | Verifies reports, assigns work orders |
| `maintenance` | Executes repairs, updates progress |

### DamageSeverity

| Value | Description |
|---|---|
| `low` | Minor issue, no operational impact |
| `medium` | Moderate issue affecting operations |
| `high` | Major issue affecting performance |
| `critical` | Severe issue requiring immediate action |

### DamageStatus

| Value | Description |
|---|---|
| `draft` | Report created but not submitted |
| `reported` | Report submitted by inspector |
| `verified` | Report verified by supervisor |
| `assigned` | Work order created and assigned |
| `in_progress` | Repair work in progress |
| `waiting_verification` | Repair done, awaiting supervisor verification |
| `completed` | Verified complete by supervisor |

### WorkOrderStatus

| Value | Description |
|---|---|
| `pending` | Work order created, not yet started |
| `in_progress` | Maintenance team working |
| `completed` | Work finished, pending report verification |
| `cancelled` | Work order cancelled |

---

## TABLE: `users`

> System users with role-based access control. Extends Laravel Breeze default.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `name` | `VARCHAR(255)` | NO | — | Full name |
| `email` | `VARCHAR(255)` | NO | — | Login email |
| `email_verified_at` | `TIMESTAMP` | YES | `NULL` | Email verification timestamp |
| `password` | `VARCHAR(255)` | NO | — | Hashed password |
| `role` | `VARCHAR(50)` | NO | `'inspector'` | User role (enum: UserRole) |
| `phone` | `VARCHAR(20)` | YES | `NULL` | Phone number |
| `is_active` | `BOOLEAN` | NO | `true` | Account active flag |
| `remember_token` | `VARCHAR(100)` | YES | `NULL` | Session remember token |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `users_email_unique` | `email` | UNIQUE |
| `users_role_index` | `role` | INDEX |
| `users_is_active_index` | `is_active` | INDEX |

---

## TABLE: `facility_categories`

> Master lookup for facility types (e.g., Dermaga, Conveyor, Gudang).

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `name` | `VARCHAR(255)` | NO | — | Category name |
| `description` | `TEXT` | YES | `NULL` | Category description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `facility_categories_name_unique` | `name` | UNIQUE |

---

## TABLE: `locations`

> Master location areas within the port complex.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `name` | `VARCHAR(255)` | NO | — | Location name |
| `description` | `TEXT` | YES | `NULL` | Location description |
| `latitude` | `DECIMAL(10,7)` | YES | `NULL` | GPS latitude |
| `longitude` | `DECIMAL(10,7)` | YES | `NULL` | GPS longitude |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `locations_name_unique` | `name` | UNIQUE |

---

## TABLE: `facilities`

> Master facility data, linked to a category and a location.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `facility_code` | `VARCHAR(50)` | NO | — | Unique facility identifier code |
| `facility_name` | `VARCHAR(255)` | NO | — | Facility name |
| `facility_category_id` | `BIGINT UNSIGNED` | NO | — | FK → facility_categories |
| `location_id` | `BIGINT UNSIGNED` | NO | — | FK → locations |
| `description` | `TEXT` | YES | `NULL` | Facility description |
| `latitude` | `DECIMAL(10,7)` | YES | `NULL` | GPS latitude |
| `longitude` | `DECIMAL(10,7)` | YES | `NULL` | GPS longitude |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `facilities_facility_code_unique` | `facility_code` | UNIQUE |
| `facilities_facility_category_id_index` | `facility_category_id` | INDEX |
| `facilities_location_id_index` | `location_id` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `facility_category_id` | `facility_categories(id)` | `CASCADE` | `RESTRICT` |
| `location_id` | `locations(id)` | `CASCADE` | `RESTRICT` |

---

## TABLE: `damage_categories`

> Master lookup for damage types (e.g., Struktural, Mekanikal, Elektrikal).

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `name` | `VARCHAR(255)` | NO | — | Category name |
| `description` | `TEXT` | YES | `NULL` | Category description |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `damage_categories_name_unique` | `name` | UNIQUE |

---

## TABLE: `damage_reports`

> Core table — inspection damage reports submitted by inspectors.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `report_number` | `VARCHAR(50)` | NO | — | Auto-generated report number (e.g., `DR-202606-0001`) |
| `facility_id` | `BIGINT UNSIGNED` | NO | — | FK → facilities |
| `reporter_id` | `BIGINT UNSIGNED` | NO | — | FK → users (inspector) |
| `damage_category_id` | `BIGINT UNSIGNED` | NO | — | FK → damage_categories |
| `severity` | `VARCHAR(50)` | NO | `'low'` | Damage severity (enum: DamageSeverity) |
| `title` | `VARCHAR(255)` | NO | — | Report title |
| `description` | `TEXT` | NO | — | Detailed damage description |
| `latitude` | `DECIMAL(10,7)` | YES | `NULL` | Damage GPS latitude |
| `longitude` | `DECIMAL(10,7)` | YES | `NULL` | Damage GPS longitude |
| `status` | `VARCHAR(50)` | NO | `'draft'` | Report status (enum: DamageStatus) |
| `reported_at` | `TIMESTAMP` | YES | `NULL` | When report was submitted |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `damage_reports_report_number_unique` | `report_number` | UNIQUE |
| `damage_reports_facility_id_index` | `facility_id` | INDEX |
| `damage_reports_reporter_id_index` | `reporter_id` | INDEX |
| `damage_reports_damage_category_id_index` | `damage_category_id` | INDEX |
| `damage_reports_status_index` | `status` | INDEX |
| `damage_reports_severity_index` | `severity` | INDEX |
| `damage_reports_reported_at_index` | `reported_at` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `facility_id` | `facilities(id)` | `CASCADE` | `RESTRICT` |
| `reporter_id` | `users(id)` | `CASCADE` | `RESTRICT` |
| `damage_category_id` | `damage_categories(id)` | `CASCADE` | `RESTRICT` |

---

## TABLE: `damage_photos`

> Photos attached to damage reports (minimum 1 per report — enforced at application level).

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `damage_report_id` | `BIGINT UNSIGNED` | NO | — | FK → damage_reports |
| `photo_path` | `VARCHAR(500)` | NO | — | Storage path relative to public disk |
| `caption` | `VARCHAR(255)` | YES | `NULL` | Photo caption |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `damage_photos_damage_report_id_index` | `damage_report_id` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `damage_report_id` | `damage_reports(id)` | `CASCADE` | `CASCADE` |

---

## TABLE: `work_orders`

> Repair assignments created by supervisors. One work order per damage report.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `damage_report_id` | `BIGINT UNSIGNED` | NO | — | FK → damage_reports |
| `assigned_to` | `BIGINT UNSIGNED` | NO | — | FK → users (maintenance team) |
| `assigned_by` | `BIGINT UNSIGNED` | NO | — | FK → users (supervisor) |
| `assigned_date` | `DATE` | NO | — | Date assignment was made |
| `due_date` | `DATE` | NO | — | Target completion date |
| `status` | `VARCHAR(50)` | NO | `'pending'` | Work order status (enum: WorkOrderStatus) |
| `notes` | `TEXT` | YES | `NULL` | Supervisor instructions |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `work_orders_damage_report_id_unique` | `damage_report_id` | UNIQUE |
| `work_orders_assigned_to_index` | `assigned_to` | INDEX |
| `work_orders_assigned_by_index` | `assigned_by` | INDEX |
| `work_orders_status_index` | `status` | INDEX |
| `work_orders_due_date_index` | `due_date` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `damage_report_id` | `damage_reports(id)` | `CASCADE` | `CASCADE` |
| `assigned_to` | `users(id)` | `CASCADE` | `RESTRICT` |
| `assigned_by` | `users(id)` | `CASCADE` | `RESTRICT` |

---

## TABLE: `repair_progress`

> Activity log entries for each work order, tracking incremental progress.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `work_order_id` | `BIGINT UNSIGNED` | NO | — | FK → work_orders |
| `progress_percentage` | `TINYINT UNSIGNED` | NO | `0` | 0–100 completion percentage |
| `description` | `TEXT` | NO | — | Work activity description |
| `photo` | `VARCHAR(500)` | YES | `NULL` | Evidence photo storage path |
| `created_by` | `BIGINT UNSIGNED` | NO | — | FK → users (maintenance team) |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `repair_progress_work_order_id_index` | `work_order_id` | INDEX |
| `repair_progress_created_by_index` | `created_by` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `work_order_id` | `work_orders(id)` | `CASCADE` | `CASCADE` |
| `created_by` | `users(id)` | `CASCADE` | `RESTRICT` |

---

## TABLE: `status_histories`

> Audit trail recording every status transition on a damage report.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `damage_report_id` | `BIGINT UNSIGNED` | NO | — | FK → damage_reports |
| `old_status` | `VARCHAR(50)` | YES | `NULL` | Previous status (NULL for initial creation) |
| `new_status` | `VARCHAR(50)` | NO | — | New status |
| `changed_by` | `BIGINT UNSIGNED` | NO | — | FK → users |
| `remarks` | `TEXT` | YES | `NULL` | Optional notes for the transition |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `status_histories_damage_report_id_index` | `damage_report_id` | INDEX |
| `status_histories_changed_by_index` | `changed_by` | INDEX |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `damage_report_id` | `damage_reports(id)` | `CASCADE` | `CASCADE` |
| `changed_by` | `users(id)` | `CASCADE` | `RESTRICT` |

---

## TABLE: `notifications`

> Internal system notifications for users.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED AI PK` | NO | — | Primary key |
| `user_id` | `BIGINT UNSIGNED` | NO | — | FK → users (recipient) |
| `title` | `VARCHAR(255)` | NO | — | Notification title |
| `message` | `TEXT` | NO | — | Notification body |
| `is_read` | `BOOLEAN` | NO | `false` | Read status flag |
| `created_at` | `TIMESTAMP` | YES | `NULL` | — |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | — |

**Indexes:**

| Name | Columns | Type |
|---|---|---|
| `PRIMARY` | `id` | PRIMARY |
| `notifications_user_id_index` | `user_id` | INDEX |
| `notifications_is_read_index` | `is_read` | INDEX |
| `notifications_user_id_is_read_index` | `user_id, is_read` | COMPOSITE |

**Foreign Keys:**

| Column | References | On Update | On Delete |
|---|---|---|---|
| `user_id` | `users(id)` | `CASCADE` | `CASCADE` |

---

## LARAVEL DEFAULT TABLES

The following tables are auto-generated by Laravel 12 / Breeze and should remain unchanged:

| Table | Purpose |
|---|---|
| `password_reset_tokens` | Password reset flow |
| `sessions` | Session driver (if using database) |
| `cache` | Cache driver (if using database) |
| `cache_locks` | Cache lock management |
| `jobs` | Queue jobs (if using database) |
| `job_batches` | Batched jobs |
| `failed_jobs` | Failed queue jobs |

---

## CASCADE RULES SUMMARY

| Parent Table | Child Table | On Delete | Reason |
|---|---|---|---|
| `facility_categories` | `facilities` | `RESTRICT` | Cannot delete category with existing facilities |
| `locations` | `facilities` | `RESTRICT` | Cannot delete location with existing facilities |
| `facilities` | `damage_reports` | `RESTRICT` | Cannot delete facility with existing reports |
| `users` | `damage_reports` | `RESTRICT` | Cannot delete user with existing reports |
| `damage_categories` | `damage_reports` | `RESTRICT` | Cannot delete category with existing reports |
| `damage_reports` | `damage_photos` | `CASCADE` | Photos deleted with report |
| `damage_reports` | `work_orders` | `CASCADE` | Work order deleted with report |
| `damage_reports` | `status_histories` | `CASCADE` | Audit trail deleted with report |
| `work_orders` | `repair_progress` | `CASCADE` | Progress entries deleted with work order |
| `users` | `work_orders` | `RESTRICT` | Cannot delete user with active assignments |
| `users` | `repair_progress` | `RESTRICT` | Cannot delete user with progress entries |
| `users` | `notifications` | `CASCADE` | Notifications deleted with user |

---

## ENTITY RELATIONSHIP DIAGRAM

```
users ─────────────┬──── 1:N ────→ damage_reports
                    ├──── 1:N ────→ work_orders (assigned_to)
                    ├──── 1:N ────→ work_orders (assigned_by)
                    ├──── 1:N ────→ repair_progress (created_by)
                    ├──── 1:N ────→ status_histories (changed_by)
                    └──── 1:N ────→ notifications

facility_categories ──── 1:N ────→ facilities

locations ─────────────── 1:N ────→ facilities

facilities ────────────── 1:N ────→ damage_reports

damage_categories ─────── 1:N ────→ damage_reports

damage_reports ────┬──── 1:N ────→ damage_photos
                   ├──── 1:1 ────→ work_orders
                   └──── 1:N ────→ status_histories

work_orders ───────────── 1:N ────→ repair_progress
```

---

## MIGRATION ORDER

Migrations must be created and run in this order to satisfy foreign key dependencies:

| # | Migration | Table |
|---|---|---|
| 1 | `create_users_table` | `users` |
| 2 | `create_facility_categories_table` | `facility_categories` |
| 3 | `create_locations_table` | `locations` |
| 4 | `create_facilities_table` | `facilities` |
| 5 | `create_damage_categories_table` | `damage_categories` |
| 6 | `create_damage_reports_table` | `damage_reports` |
| 7 | `create_damage_photos_table` | `damage_photos` |
| 8 | `create_work_orders_table` | `work_orders` |
| 9 | `create_repair_progress_table` | `repair_progress` |
| 10 | `create_status_histories_table` | `status_histories` |
| 11 | `create_notifications_table` | `notifications` |

---

## INDEXING STRATEGY

**Rationale for selected indexes:**

| Index | Reason |
|---|---|
| `damage_reports.status` | Dashboard KPI filters, monitoring views |
| `damage_reports.severity` | Critical damage filtering on dashboard and map |
| `damage_reports.reported_at` | Time-based reporting and chart queries |
| `damage_reports.facility_id` | Damage-by-facility chart, facility detail views |
| `work_orders.due_date` | Overdue work order queries |
| `work_orders.status` | Work order monitoring filters |
| `notifications(user_id, is_read)` | Composite index for unread notification badge count |
| `users.role` | Role-based query filtering for dropdowns and policies |

---

END OF DOCUMENT
