# SYSTEM DIAGRAMS

# E-REPORTING INSPEKSI FASILITAS PELABUHAN

Version: 1.0

---

## 1. USE CASE DIAGRAM

```mermaid
graph LR
    subgraph System["E-Reporting Inspeksi Fasilitas Pelabuhan"]
        UC01[Login / Logout]
        UC02[View Dashboard]
        UC03[Manage Users]
        UC04[Manage Facilities]
        UC05[Manage Facility Categories]
        UC06[Manage Locations]
        UC07[Manage Damage Categories]
        UC08[Create Damage Report]
        UC09[View Damage Reports]
        UC10[Edit / Delete Draft Report]
        UC11[Submit Report for Verification]
        UC12[Verify / Reject Report]
        UC13[Create Work Order]
        UC14[View Work Orders]
        UC15[Start Repair Work]
        UC16[Update Repair Progress]
        UC17[Complete Repair Work]
        UC18[Verify Repair Completion]
        UC19[View GIS Map]
        UC20[Export Report PDF]
        UC21[Manage Notifications]
    end

    Admin([Administrator])
    Inspector([Inspector])
    Supervisor([Supervisor])
    Maintenance([Maintenance Team])

    Admin --> UC01
    Admin --> UC02
    Admin --> UC03
    Admin --> UC04
    Admin --> UC05
    Admin --> UC06
    Admin --> UC07
    Admin --> UC09
    Admin --> UC14
    Admin --> UC19
    Admin --> UC20
    Admin --> UC21

    Inspector --> UC01
    Inspector --> UC02
    Inspector --> UC08
    Inspector --> UC09
    Inspector --> UC10
    Inspector --> UC11
    Inspector --> UC19
    Inspector --> UC21

    Supervisor --> UC01
    Supervisor --> UC02
    Supervisor --> UC09
    Supervisor --> UC12
    Supervisor --> UC13
    Supervisor --> UC14
    Supervisor --> UC18
    Supervisor --> UC19
    Supervisor --> UC20
    Supervisor --> UC21

    Maintenance --> UC01
    Maintenance --> UC02
    Maintenance --> UC14
    Maintenance --> UC15
    Maintenance --> UC16
    Maintenance --> UC17
    Maintenance --> UC19
    Maintenance --> UC21
```

### Use Case Description Table

| ID | Use Case | Actor | Description |
|---|---|---|---|
| UC01 | Login / Logout | All Actors | Authenticate into the system |
| UC02 | View Dashboard | All Actors | View KPI cards, charts, and summary |
| UC03 | Manage Users | Admin | Create, edit, deactivate user accounts |
| UC04 | Manage Facilities | Admin | CRUD facility master data |
| UC05 | Manage Facility Categories | Admin | CRUD facility category master data |
| UC06 | Manage Locations | Admin | CRUD location master data |
| UC07 | Manage Damage Categories | Admin | CRUD damage category master data |
| UC08 | Create Damage Report | Inspector | Input damage report with photos and coordinates |
| UC09 | View Damage Reports | All Actors | Browse and filter damage reports |
| UC10 | Edit / Delete Draft Report | Inspector | Modify or delete own draft reports |
| UC11 | Submit Report for Verification | Inspector | Transition draft → reported status |
| UC12 | Verify / Reject Report | Supervisor | Verify or reject a reported damage |
| UC13 | Create Work Order | Supervisor | Assign maintenance team to verified report |
| UC14 | View Work Orders | Supervisor, Maintenance | Browse work order list |
| UC15 | Start Repair Work | Maintenance | Mark work order as in-progress |
| UC16 | Update Repair Progress | Maintenance | Add progress entries with percentage and photos |
| UC17 | Complete Repair Work | Maintenance | Mark work order as completed (100%) |
| UC18 | Verify Repair Completion | Supervisor | Verify and close completed repair |
| UC19 | View GIS Map | All Actors | View damage locations on interactive map |
| UC20 | Export Report PDF | Admin, Supervisor | Generate and download monitoring reports |
| UC21 | Manage Notifications | All Actors | View and mark notifications as read |

---

## 2. ACTIVITY DIAGRAM

### 2.1 Main Business Process — Damage Report Lifecycle

```mermaid
flowchart TD
    START([Start]) --> A[Inspector: Create Damage Report]
    A --> B[Upload Photos & Fill Details]
    B --> C{Save as Draft or Submit?}

    C -->|Draft| D[Report Saved as DRAFT]
    C -->|Submit| E[Report Status → REPORTED]
    D --> F[Inspector: Edit Draft Report]
    F --> C
    D --> G[Inspector: Submit Draft]
    G --> E

    E --> H[Supervisor: Review Report]
    H --> I{Verify or Reject?}

    I -->|Reject| J[Report Status → DRAFT]
    J --> K[Notification Sent to Inspector]
    K --> F

    I -->|Verify| L[Report Status → VERIFIED]
    L --> M[Supervisor: Create Work Order]
    M --> N[Assign to Maintenance Team]
    N --> O[Report Status → ASSIGNED]
    O --> P[Notification Sent to Maintenance]

    P --> Q[Maintenance: View Assigned Work Order]
    Q --> R[Maintenance: Start Repair Work]
    R --> S[Work Order Status → IN_PROGRESS]
    S --> T[Report Status → IN_PROGRESS]

    T --> U[Maintenance: Update Repair Progress]
    U --> V[Upload Evidence Photo & Percentage]
    V --> W{Progress = 100%?}

    W -->|No| U
    W -->|Yes| X[Work Order Status → COMPLETED]
    X --> Y[Report Status → WAITING_VERIFICATION]
    Y --> Z[Notification Sent to Supervisor]

    Z --> AA[Supervisor: Review Completion]
    AA --> AB{Approve or Return?}

    AB -->|Return| AC[Report Status → IN_PROGRESS]
    AC --> U

    AB -->|Approve| AD[Report Status → COMPLETED]
    AD --> AE[Notification Sent to Reporter]
    AE --> AF([End])
```

### 2.2 Create Damage Report — Detailed Activity

```mermaid
flowchart TD
    S1([Start]) --> S2[Inspector Opens Create Form]
    S2 --> S3[Select Facility from List]
    S3 --> S4[Select Damage Category]
    S4 --> S5[Fill Title & Description]
    S5 --> S6[Select Severity Level]
    S6 --> S7[Set GPS Coordinates]
    S7 --> S8[Upload Photos min 1]
    S8 --> S9{Validate Input}

    S9 -->|Invalid| S10[Show Validation Errors]
    S10 --> S3

    S9 -->|Valid| S11[Generate Report Number DR-YYYYMM-NNNN]
    S11 --> S12[Save Report as DRAFT]
    S12 --> S13[Store Photos to Public Disk]
    S13 --> S14[Record Initial Status History]
    S14 --> S15{Draft or Submit?}

    S15 -->|Draft| S16[Redirect to Report List]
    S15 -->|Submit| S17[Status → REPORTED]
    S17 --> S18[Set reported_at Timestamp]
    S18 --> S19[Record Status History]
    S19 --> S16
    S16 --> E1([End])
```

### 2.3 Verify Repair Completion — Detailed Activity

```mermaid
flowchart TD
    V1([Start]) --> V2[Supervisor Opens Completed Report]
    V2 --> V3[Review Repair Progress Entries]
    V3 --> V4[Review Evidence Photos]
    V4 --> V5{Repair Satisfactory?}

    V5 -->|No| V6[Add Rejection Remarks]
    V6 --> V7[Report Status → IN_PROGRESS]
    V7 --> V8[Record Status History]
    V8 --> V9[Notify Maintenance Team]
    V9 --> V10([End — Return to Repair])

    V5 -->|Yes| V11[Report Status → COMPLETED]
    V11 --> V12[Record Status History]
    V12 --> V13[Notify Inspector / Reporter]
    V13 --> V14[Report Closed]
    V14 --> V15([End — Report Completed])
```

---

## 3. ENTITY RELATIONSHIP DIAGRAM (ERD)

```mermaid
erDiagram

    %% ============================================
    %% MASTER DATA ENTITIES
    %% ============================================

    users {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        varchar role
        varchar phone
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    facility_categories {
        bigint id PK
        varchar name UK
        text description
        timestamp created_at
        timestamp updated_at
    }

    locations {
        bigint id PK
        varchar name UK
        text description
        decimal latitude
        decimal longitude
        timestamp created_at
        timestamp updated_at
    }

    damage_categories {
        bigint id PK
        varchar name UK
        text description
        timestamp created_at
        timestamp updated_at
    }

    %% ============================================
    %% CORE ENTITIES
    %% ============================================

    facilities {
        bigint id PK
        varchar facility_code UK
        varchar facility_name
        bigint facility_category_id FK
        bigint location_id FK
        text description
        decimal latitude
        decimal longitude
        timestamp created_at
        timestamp updated_at
    }

    damage_reports {
        bigint id PK
        varchar report_number UK
        bigint facility_id FK
        bigint reporter_id FK
        bigint damage_category_id FK
        varchar severity
        varchar title
        text description
        decimal latitude
        decimal longitude
        varchar status
        timestamp reported_at
        timestamp created_at
        timestamp updated_at
    }

    damage_photos {
        bigint id PK
        bigint damage_report_id FK
        varchar photo_path
        varchar caption
        timestamp created_at
        timestamp updated_at
    }

    work_orders {
        bigint id PK
        bigint damage_report_id FK_UK
        bigint assigned_to FK
        bigint assigned_by FK
        date assigned_date
        date due_date
        varchar status
        text notes
        timestamp created_at
        timestamp updated_at
    }

    repair_progress {
        bigint id PK
        bigint work_order_id FK
        tinyint progress_percentage
        text description
        varchar photo
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    status_histories {
        bigint id PK
        bigint damage_report_id FK
        varchar old_status
        varchar new_status
        bigint changed_by FK
        text remarks
        timestamp created_at
        timestamp updated_at
    }

    notifications {
        bigint id PK
        bigint user_id FK
        varchar title
        text message
        boolean is_read
        timestamp created_at
        timestamp updated_at
    }

    %% ============================================
    %% RELATIONSHIPS
    %% ============================================

    facility_categories ||--o{ facilities : "has many"
    locations ||--o{ facilities : "has many"
    facilities ||--o{ damage_reports : "has many"
    users ||--o{ damage_reports : "reports as reporter"
    users ||--o{ work_orders : "assigned to"
    users ||--o{ work_orders : "assigned by"
    users ||--o{ repair_progress : "creates"
    users ||--o{ status_histories : "changes"
    users ||--o{ notifications : "receives"
    damage_categories ||--o{ damage_reports : "categorizes"
    damage_reports ||--o{ damage_photos : "has many"
    damage_reports ||--o| work_orders : "has one"
    damage_reports ||--o{ status_histories : "has many"
    work_orders ||--o{ repair_progress : "has many"
```

### Cardinality Summary

| Relationship | Cardinality | FK Column | On Delete |
|---|---|---|---|
| FacilityCategory → Facility | 1 : N | `facility_category_id` | RESTRICT |
| Location → Facility | 1 : N | `location_id` | RESTRICT |
| Facility → DamageReport | 1 : N | `facility_id` | RESTRICT |
| User → DamageReport | 1 : N | `reporter_id` | RESTRICT |
| DamageCategory → DamageReport | 1 : N | `damage_category_id` | RESTRICT |
| DamageReport → DamagePhoto | 1 : N | `damage_report_id` | CASCADE |
| DamageReport → WorkOrder | 1 : 1 | `damage_report_id` (UNIQUE) | CASCADE |
| DamageReport → StatusHistory | 1 : N | `damage_report_id` | CASCADE |
| User → WorkOrder (assigned) | 1 : N | `assigned_to` | RESTRICT |
| User → WorkOrder (assigner) | 1 : N | `assigned_by` | RESTRICT |
| WorkOrder → RepairProgress | 1 : N | `work_order_id` | CASCADE |
| User → RepairProgress | 1 : N | `created_by` | RESTRICT |
| User → StatusHistory | 1 : N | `changed_by` | RESTRICT |
| User → Notification | 1 : N | `user_id` | CASCADE |

---

## 4. CLASS DIAGRAM

### 4.1 Enums

```mermaid
classDiagram
    class UserRole {
        <<enumeration>>
        ADMIN
        INSPECTOR
        SUPERVISOR
        MAINTENANCE
        +label() string
    }

    class DamageStatus {
        <<enumeration>>
        DRAFT
        REPORTED
        VERIFIED
        ASSIGNED
        IN_PROGRESS
        WAITING_VERIFICATION
        COMPLETED
        +label() string
        +mapColor() string
        +isActive() bool
        +allowedTransitions() array
    }

    class DamageSeverity {
        <<enumeration>>
        LOW
        MEDIUM
        HIGH
        CRITICAL
        +label() string
        +color() string
    }

    class WorkOrderStatus {
        <<enumeration>>
        PENDING
        IN_PROGRESS
        COMPLETED
        CANCELLED
        +label() string
    }
```

### 4.2 Model Layer (Eloquent Models)

```mermaid
classDiagram
    class User {
        +id : bigint
        +name : string
        +email : string
        +password : string
        +role : UserRole
        +phone : string
        +is_active : boolean
        +remember_token : string
        +damageReports() HasMany
        +assignedWorkOrders() HasMany
        +createdWorkOrders() HasMany
        +repairProgressEntries() HasMany
        +statusHistories() HasMany
        +internalNotifications() HasMany
        +isAdmin() bool
        +isInspector() bool
        +isSupervisor() bool
        +isMaintenance() bool
    }

    class FacilityCategory {
        +id : bigint
        +name : string
        +description : text
        +facilities() HasMany
    }

    class Location {
        +id : bigint
        +name : string
        +description : text
        +latitude : decimal
        +longitude : decimal
        +facilities() HasMany
    }

    class Facility {
        +id : bigint
        +facility_code : string
        +facility_name : string
        +facility_category_id : bigint
        +location_id : bigint
        +description : text
        +latitude : decimal
        +longitude : decimal
        +category() BelongsTo
        +location() BelongsTo
        +damageReports() HasMany
    }

    class DamageCategory {
        +id : bigint
        +name : string
        +description : text
        +damageReports() HasMany
    }

    class DamageReport {
        +id : bigint
        +report_number : string
        +facility_id : bigint
        +reporter_id : bigint
        +damage_category_id : bigint
        +severity : DamageSeverity
        +title : string
        +description : text
        +latitude : decimal
        +longitude : decimal
        +status : DamageStatus
        +reported_at : datetime
        +facility() BelongsTo
        +reporter() BelongsTo
        +damageCategory() BelongsTo
        +photos() HasMany
        +workOrder() HasOne
        +statusHistories() HasMany
        +scopeActive(query) Builder
        +scopeCritical(query) Builder
        +scopeByStatus(query, status) Builder
        +scopeBySeverity(query, severity) Builder
    }

    class DamagePhoto {
        +id : bigint
        +damage_report_id : bigint
        +photo_path : string
        +caption : string
        +damageReport() BelongsTo
        +getPhotoUrlAttribute() string
    }

    class WorkOrder {
        +id : bigint
        +damage_report_id : bigint
        +assigned_to : bigint
        +assigned_by : bigint
        +assigned_date : date
        +due_date : date
        +status : WorkOrderStatus
        +notes : text
        +damageReport() BelongsTo
        +assignee() BelongsTo
        +assigner() BelongsTo
        +progressEntries() HasMany
        +scopeOverdue(query) Builder
        +isOverdue() bool
    }

    class RepairProgress {
        +id : bigint
        +work_order_id : bigint
        +progress_percentage : int
        +description : text
        +photo : string
        +created_by : bigint
        +workOrder() BelongsTo
        +creator() BelongsTo
        +getPhotoUrlAttribute() string
    }

    class StatusHistory {
        +id : bigint
        +damage_report_id : bigint
        +old_status : DamageStatus
        +new_status : DamageStatus
        +changed_by : bigint
        +remarks : text
        +damageReport() BelongsTo
        +changedBy() BelongsTo
    }

    class Notification {
        +id : bigint
        +user_id : bigint
        +title : string
        +message : text
        +is_read : boolean
        +user() BelongsTo
        +scopeUnread(query) Builder
    }

    User "1" --> "*" DamageReport : reporter
    User "1" --> "*" WorkOrder : assignee
    User "1" --> "*" WorkOrder : assigner
    User "1" --> "*" RepairProgress : creator
    User "1" --> "*" StatusHistory : changed_by
    User "1" --> "*" Notification : recipient
    FacilityCategory "1" --> "*" Facility : has
    Location "1" --> "*" Facility : has
    Facility "1" --> "*" DamageReport : has
    DamageCategory "1" --> "*" DamageReport : categorizes
    DamageReport "1" --> "*" DamagePhoto : has
    DamageReport "1" --> "0..1" WorkOrder : has
    DamageReport "1" --> "*" StatusHistory : has
    WorkOrder "1" --> "*" RepairProgress : has

    DamageReport --> DamageStatus : uses
    DamageReport --> DamageSeverity : uses
    WorkOrder --> WorkOrderStatus : uses
    User --> UserRole : uses
    StatusHistory --> DamageStatus : uses
```

### 4.3 Service Layer

```mermaid
classDiagram
    class DamageReportService {
        -DamageReportRepository reportRepository
        -DamagePhotoRepository photoRepository
        -StatusHistoryRepository historyRepository
        -NotificationService notificationService
        +create(data, photos, reporter) DamageReport
        +submit(report, user) DamageReport
        +verify(report, supervisor, remarks) DamageReport
        +transitionStatus(report, newStatus, user, remarks) DamageReport
        +getPaginated(filters, perPage) LengthAwarePaginator
        +getDetail(id) DamageReport
        +getDashboardStats() array
        +getMapData(filters) array
        #generateReportNumber() string
        #storePhoto(file) string
    }

    class WorkOrderService {
        -WorkOrderRepository workOrderRepository
        -DamageReportService damageReportService
        -NotificationService notificationService
        +create(data, report, supervisor) WorkOrder
        +startWork(workOrder, user) WorkOrder
        +completeWork(workOrder, user) WorkOrder
        +getPaginated(filters, perPage) LengthAwarePaginator
        +getDetail(id) WorkOrder
    }

    class RepairProgressService {
        -RepairProgressRepository progressRepository
        -WorkOrderService workOrderService
        +addProgress(workOrder, data, photo, creator) RepairProgress
        +getByWorkOrder(workOrderId) Collection
        +getLatest(workOrderId) RepairProgress
    }

    class FacilityService {
        -FacilityRepository facilityRepository
        -FacilityCategoryRepository categoryRepository
        -LocationRepository locationRepository
        +getPaginated(filters, perPage) LengthAwarePaginator
        +getDetail(id) Facility
        +create(data) Facility
        +update(id, data) bool
        +delete(id) bool
        +getCategories() Collection
        +getLocations() Collection
    }

    class UserService {
        -UserRepository userRepository
        +create(data) User
        +update(id, data) bool
        +toggleActive(id) bool
        +getPaginated(filters, perPage) LengthAwarePaginator
        +getMaintenanceTeam() Collection
    }

    class NotificationService {
        -NotificationRepository notificationRepository
        +notify(userId, title, message) void
        +getUnreadCount(userId) int
        +markAsRead(id) bool
        +markAllAsRead(userId) int
    }

    DamageReportService --> NotificationService : uses
    WorkOrderService --> DamageReportService : uses
    WorkOrderService --> NotificationService : uses
    RepairProgressService --> WorkOrderService : uses
```

### 4.4 Repository Layer

```mermaid
classDiagram
    class BaseRepository {
        <<abstract>>
        #Model model
        +all(columns) Collection
        +paginate(perPage, columns) LengthAwarePaginator
        +find(id, columns) Model
        +findOrFail(id, columns) Model
        +create(data) Model
        +update(id, data) bool
        +delete(id) bool
        +count() int
        #newQuery() Builder
    }

    class DamageReportRepository {
        +paginateWithRelations(filters, perPage) LengthAwarePaginator
        +findWithFullRelations(id) DamageReport
        +countActive() int
        +countCompleted() int
        +countCritical() int
        +getReportsForMap(filters) Collection
    }

    class WorkOrderRepository {
        +paginateWithRelations(filters, perPage) LengthAwarePaginator
        +findWithRelations(id) WorkOrder
    }

    class RepairProgressRepository {
        +getByWorkOrder(workOrderId) Collection
        +getLatestByWorkOrder(workOrderId) RepairProgress
    }

    class UserRepository {
        +paginateWithFilters(filters, perPage) LengthAwarePaginator
        +getMaintenanceTeam() Collection
    }

    class FacilityRepository {
        +paginateWithRelations(filters, perPage) LengthAwarePaginator
        +findWithRelations(id) Facility
    }

    class FacilityCategoryRepository {
        +getAllWithFacilityCount() Collection
    }

    class LocationRepository {
        +getAllWithFacilityCount() Collection
    }

    class DamagePhotoRepository
    class DamageCategoryRepository
    class StatusHistoryRepository
    class NotificationRepository {
        +countUnreadByUser(userId) int
        +markAsRead(id) bool
        +markAllAsReadForUser(userId) int
    }

    BaseRepository <|-- DamageReportRepository
    BaseRepository <|-- WorkOrderRepository
    BaseRepository <|-- RepairProgressRepository
    BaseRepository <|-- UserRepository
    BaseRepository <|-- FacilityRepository
    BaseRepository <|-- FacilityCategoryRepository
    BaseRepository <|-- LocationRepository
    BaseRepository <|-- DamagePhotoRepository
    BaseRepository <|-- DamageCategoryRepository
    BaseRepository <|-- StatusHistoryRepository
    BaseRepository <|-- NotificationRepository
```

### 4.5 Controller Layer

```mermaid
classDiagram
    class Controller {
        <<abstract>>
    }

    class DamageReportController {
        -DamageReportService reportService
        -DamageReportRepository reportRepo
        -FacilityRepository facilityRepo
        -DamageCategoryRepository categoryRepo
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +show(id) View
        +edit(id) View
        +update(request, id) RedirectResponse
        +submit(id) RedirectResponse
        +verify(request, id) RedirectResponse
        +destroy(id) RedirectResponse
    }

    class WorkOrderController {
        -WorkOrderService workOrderService
        -UserService userService
        +index(request) View
        +show(id) View
        +create(reportId) View
        +store(request, reportId) RedirectResponse
        +startWork(id) RedirectResponse
        +completeWork(id) RedirectResponse
    }

    class RepairProgressController {
        -RepairProgressService progressService
        +store(request, workOrderId) RedirectResponse
        +show(workOrderId) View
    }

    class DashboardController {
        -DamageReportService reportService
        +index() View
    }

    class FacilityController {
        -FacilityService facilityService
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +show(id) View
        +edit(id) View
        +update(request, id) RedirectResponse
        +destroy(id) RedirectResponse
    }

    class UserController {
        -UserService userService
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +edit(id) View
        +update(request, id) RedirectResponse
        +toggleActive(id) RedirectResponse
    }

    class GisController {
        -DamageReportService reportService
        +index() View
        +getData(request) JsonResponse
    }

    class ReportController {
        +index(request) View
        +exportPdf(request) Response
    }

    class NotificationController {
        -NotificationService notificationService
        +index() View
        +markAsRead(id) RedirectResponse
        +markAllAsRead() RedirectResponse
        +unreadCount() JsonResponse
    }

    Controller <|-- DamageReportController
    Controller <|-- WorkOrderController
    Controller <|-- RepairProgressController
    Controller <|-- DashboardController
    Controller <|-- FacilityController
    Controller <|-- UserController
    Controller <|-- GisController
    Controller <|-- ReportController
    Controller <|-- NotificationController

    DamageReportController --> DamageReportService : uses
    WorkOrderController --> WorkOrderService : uses
    RepairProgressController --> RepairProgressService : uses
    DashboardController --> DamageReportService : uses
    FacilityController --> FacilityService : uses
    UserController --> UserService : uses
    GisController --> DamageReportService : uses
    NotificationController --> NotificationService : uses
```

### 4.6 Full Architecture Overview

```mermaid
flowchart TB
    subgraph Presentation["Presentation Layer"]
        BladeViews[Blade Views]
    end

    subgraph Controllers["Controller Layer"]
        DC[DamageReportController]
        WOC[WorkOrderController]
        RPC[RepairProgressController]
        DBC[DashboardController]
        FC[FacilityController]
        UC[UserController]
        GC[GisController]
        RC[ReportController]
        NC[NotificationController]
    end

    subgraph Services["Service Layer"]
        DRS[DamageReportService]
        WOS[WorkOrderService]
        RPS[RepairProgressService]
        FS[FacilityService]
        US[UserService]
        NS[NotificationService]
    end

    subgraph Repositories["Repository Layer"]
        BR[BaseRepository]
        DRR[DamageReportRepository]
        WOR[WorkOrderRepository]
        RPR[RepairProgressRepository]
        FR[FacilityRepository]
        UR[UserRepository]
        NR[NotificationRepository]
    end

    subgraph Models["Model Layer"]
        User
        DamageReport
        WorkOrder
        RepairProgress
        Facility
        FacilityCategory
        Location
        DamageCategory
        DamagePhoto
        StatusHistory
        Notification
    end

    subgraph Enums["Enum Layer"]
        UserRole
        DamageStatus
        DamageSeverity
        WorkOrderStatus
    end

    BladeViews --> Controllers
    Controllers --> Services
    Services --> Repositories
    Repositories --> Models
    Models --> Enums
```

---

END OF DOCUMENT
