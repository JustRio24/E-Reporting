# TECHNICAL_ARCHITECTURE.md

# E-REPORTING INSPEKSI FASILITAS PELABUHAN

Version: 1.0

---

# SYSTEM ARCHITECTURE

Architecture Style:

Monolithic Web Application

Pattern:

MVC + Service Layer + Repository Pattern

Framework:

Laravel 12

Frontend:

Blade + Tailwind CSS

Database:

MySQL

Map:

Leaflet.js

Chart:

Chart.js

Export:

DomPDF

Authentication:

Laravel Breeze

---

# FOLDER STRUCTURE

app/

├── Http/

│   ├── Controllers/

│   ├── Requests/

│   └── Middleware/

│

├── Models/

│

├── Services/

│

├── Repositories/

│

├── Policies/

│

├── Enums/

│

└── Helpers/

---

resources/views/

├── layouts/

├── dashboard/

├── facilities/

├── damage-reports/

├── work-orders/

├── progress/

├── gis/

└── reports/

---

# DATABASE DESIGN

Database Name:

e_reporting_pelabuhan

---

# TABLE USERS

Purpose:

System users.

Columns:

id

name

email

password

role

phone

is_active

created_at

updated_at

---

# TABLE FACILITY_CATEGORIES

Purpose:

Master category of facilities.

Columns:

id

name

description

created_at

updated_at

---

# TABLE LOCATIONS

Purpose:

Master location areas.

Columns:

id

name

description

latitude

longitude

created_at

updated_at

---

# TABLE FACILITIES

Purpose:

Master facility data.

Columns:

id

facility_code

facility_name

facility_category_id

location_id

description

latitude

longitude

created_at

updated_at

---

# TABLE DAMAGE_CATEGORIES

Purpose:

Master damage category.

Columns:

id

name

description

created_at

updated_at

---

# TABLE DAMAGE_REPORTS

Purpose:

Main inspection report.

Columns:

id

report_number

facility_id

reporter_id

damage_category_id

severity

title

description

latitude

longitude

status

reported_at

created_at

updated_at

---

# TABLE DAMAGE_PHOTOS

Purpose:

Store report images.

Columns:

id

damage_report_id

photo_path

caption

created_at

updated_at

---

# TABLE WORK_ORDERS

Purpose:

Repair assignment.

Columns:

id

damage_report_id

assigned_to

assigned_by

assigned_date

due_date

status

notes

created_at

updated_at

---

# TABLE REPAIR_PROGRESS

Purpose:

Repair activity log.

Columns:

id

work_order_id

progress_percentage

description

photo

created_by

created_at

updated_at

---

# TABLE STATUS_HISTORIES

Purpose:

Audit trail.

Columns:

id

damage_report_id

old_status

new_status

changed_by

remarks

created_at

updated_at

---

# TABLE NOTIFICATIONS

Purpose:

Internal notification.

Columns:

id

user_id

title

message

is_read

created_at

updated_at

---

# ENUMS

UserRole

ADMIN

INSPECTOR

SUPERVISOR

MAINTENANCE

---

DamageSeverity

LOW

MEDIUM

HIGH

CRITICAL

---

DamageStatus

DRAFT

REPORTED

VERIFIED

ASSIGNED

IN_PROGRESS

WAITING_VERIFICATION

COMPLETED

---

# MODEL RELATIONSHIPS

User

hasMany DamageReport

hasMany WorkOrder

hasMany RepairProgress

---

FacilityCategory

hasMany Facility

---

Location

hasMany Facility

---

Facility

belongsTo FacilityCategory

belongsTo Location

hasMany DamageReport

---

DamageCategory

hasMany DamageReport

---

DamageReport

belongsTo Facility

belongsTo User

belongsTo DamageCategory

hasMany DamagePhoto

hasOne WorkOrder

hasMany StatusHistory

---

WorkOrder

belongsTo DamageReport

belongsTo User

hasMany RepairProgress

---

RepairProgress

belongsTo WorkOrder

belongsTo User

---

# BUSINESS RULES

RULE-001

Only Inspector can create damage reports.

---

RULE-002

Only Supervisor can verify reports.

---

RULE-003

Only Supervisor can create work orders.

---

RULE-004

Only Maintenance Team can update repair progress.

---

RULE-005

Completed report must pass Supervisor verification.

---

RULE-006

Every status change must be recorded in StatusHistory.

---

RULE-007

Every report must have at least one photo.

---

RULE-008

Work Order cannot be created before report verification.

---

# ROUTING STRUCTURE

/dashboard

/facilities

/facility-categories

/locations

/damage-reports

/work-orders

/repair-progress

/gis-monitoring

/reports

/users

---

# UI MODULES

Dashboard

Facility Management

Damage Reporting

Damage Monitoring

Work Orders

Repair Progress

GIS Monitoring

Reports

User Management

---

# DASHBOARD COMPONENTS

Top Cards:

Total Reports

Active Reports

Completed Reports

Critical Reports

---

Charts:

Damage By Month

Damage By Category

Damage By Facility

---

Map Widget:

Damage Locations

Status Filter

Severity Filter

---

# SECURITY

Authentication:

Laravel Breeze

Authorization:

Policy Based

Validation:

Form Request Validation

CSRF Protection:

Enabled

Mass Assignment:

Protected

---

# FILE STORAGE

Storage Disk:

public

Folder Structure:

storage/app/public/

damage-reports/

repair-progress/

user-profile/

---

# CODING CONVENTIONS

Controllers:

Thin Controllers

Business Logic:

Service Layer

Database Access:

Repository Layer

Validation:

Form Requests

Authorization:

Policies

No business logic inside Blade views.

No direct database queries inside Blade views.

---

END OF DOCUMENT
