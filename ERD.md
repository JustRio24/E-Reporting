# ENTITY RELATIONSHIP DIAGRAM

# E-REPORTING INSPEKSI FASILITAS PELABUHAN

Version: 1.0

---

```mermaid
erDiagram

    %% ============================================
    %% MASTER DATA TABLES
    %% ============================================

    users {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar name "NOT NULL"
        varchar email UK "NOT NULL"
        timestamp email_verified_at "NULL"
        varchar password "NOT NULL"
        varchar role "NOT NULL DEFAULT 'inspector'"
        varchar phone "NULL"
        boolean is_active "NOT NULL DEFAULT true"
        varchar remember_token "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    facility_categories {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar name UK "NOT NULL"
        text description "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    locations {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar name UK "NOT NULL"
        text description "NULL"
        decimal latitude "NULL — DECIMAL(10,7)"
        decimal longitude "NULL — DECIMAL(10,7)"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    damage_categories {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar name UK "NOT NULL"
        text description "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    %% ============================================
    %% CORE TABLES
    %% ============================================

    facilities {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar facility_code UK "NOT NULL — VARCHAR(50)"
        varchar facility_name "NOT NULL"
        bigint facility_category_id FK "NOT NULL"
        bigint location_id FK "NOT NULL"
        text description "NULL"
        decimal latitude "NULL — DECIMAL(10,7)"
        decimal longitude "NULL — DECIMAL(10,7)"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    damage_reports {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        varchar report_number UK "NOT NULL — VARCHAR(50)"
        bigint facility_id FK "NOT NULL"
        bigint reporter_id FK "NOT NULL"
        bigint damage_category_id FK "NOT NULL"
        varchar severity "NOT NULL DEFAULT 'low'"
        varchar title "NOT NULL"
        text description "NOT NULL"
        decimal latitude "NULL — DECIMAL(10,7)"
        decimal longitude "NULL — DECIMAL(10,7)"
        varchar status "NOT NULL DEFAULT 'draft'"
        timestamp reported_at "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    damage_photos {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        bigint damage_report_id FK "NOT NULL"
        varchar photo_path "NOT NULL — VARCHAR(500)"
        varchar caption "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    work_orders {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        bigint damage_report_id FK,UK "NOT NULL — UNIQUE"
        bigint assigned_to FK "NOT NULL"
        bigint assigned_by FK "NOT NULL"
        date assigned_date "NOT NULL"
        date due_date "NOT NULL"
        varchar status "NOT NULL DEFAULT 'pending'"
        text notes "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    repair_progress {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        bigint work_order_id FK "NOT NULL"
        tinyint progress_percentage "NOT NULL DEFAULT 0"
        text description "NOT NULL"
        varchar photo "NULL — VARCHAR(500)"
        bigint created_by FK "NOT NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    status_histories {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        bigint damage_report_id FK "NOT NULL"
        varchar old_status "NULL"
        varchar new_status "NOT NULL"
        bigint changed_by FK "NOT NULL"
        text remarks "NULL"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    notifications {
        bigint id PK "UNSIGNED AUTO_INCREMENT"
        bigint user_id FK "NOT NULL"
        varchar title "NOT NULL"
        text message "NOT NULL"
        boolean is_read "NOT NULL DEFAULT false"
        timestamp created_at "NULL"
        timestamp updated_at "NULL"
    }

    %% ============================================
    %% RELATIONSHIPS
    %% ============================================

    %% Master → Facilities
    facility_categories ||--o{ facilities : "has many"
    locations ||--o{ facilities : "has many"

    %% Facilities → Damage Reports
    facilities ||--o{ damage_reports : "has many"

    %% Users → various
    users ||--o{ damage_reports : "reports (reporter_id)"
    users ||--o{ work_orders : "is assigned (assigned_to)"
    users ||--o{ work_orders : "assigns (assigned_by)"
    users ||--o{ repair_progress : "creates (created_by)"
    users ||--o{ status_histories : "changes (changed_by)"
    users ||--o{ notifications : "receives"

    %% Damage Categories → Damage Reports
    damage_categories ||--o{ damage_reports : "categorizes"

    %% Damage Reports → children
    damage_reports ||--o{ damage_photos : "has many"
    damage_reports ||--o| work_orders : "has one"
    damage_reports ||--o{ status_histories : "has many"

    %% Work Orders → Repair Progress
    work_orders ||--o{ repair_progress : "has many"
```

---

## RELATIONSHIP REFERENCE TABLE

| Parent | Child | Cardinality | FK Column | On Delete |
|---|---|---|---|---|
| `facility_categories` | `facilities` | One-to-Many | `facility_category_id` | RESTRICT |
| `locations` | `facilities` | One-to-Many | `location_id` | RESTRICT |
| `facilities` | `damage_reports` | One-to-Many | `facility_id` | RESTRICT |
| `users` | `damage_reports` | One-to-Many | `reporter_id` | RESTRICT |
| `damage_categories` | `damage_reports` | One-to-Many | `damage_category_id` | RESTRICT |
| `damage_reports` | `damage_photos` | One-to-Many | `damage_report_id` | CASCADE |
| `damage_reports` | `work_orders` | One-to-One | `damage_report_id` (UNIQUE) | CASCADE |
| `damage_reports` | `status_histories` | One-to-Many | `damage_report_id` | CASCADE |
| `users` | `work_orders` | One-to-Many | `assigned_to` | RESTRICT |
| `users` | `work_orders` | One-to-Many | `assigned_by` | RESTRICT |
| `work_orders` | `repair_progress` | One-to-Many | `work_order_id` | CASCADE |
| `users` | `repair_progress` | One-to-Many | `created_by` | RESTRICT |
| `users` | `status_histories` | One-to-Many | `changed_by` | RESTRICT |
| `users` | `notifications` | One-to-Many | `user_id` | CASCADE |

---

## CARDINALITY NOTATION

| Symbol | Meaning |
|---|---|
| `\|\|` | Exactly one (mandatory) |
| `o\|` | Zero or one (optional) |
| `o{` | Zero or many |
| `\|{` | One or many |

---

END OF DOCUMENT
