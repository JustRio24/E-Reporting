# PROJECT CONTEXT

# E-REPORTING INSPEKSI FASILITAS PELABUHAN

## PROJECT INFORMATION

### Project Name

E-Reporting Inspeksi Fasilitas Pelabuhan Berbasis Web

### Academic Title

Pengembangan Aplikasi E-Reporting Inspeksi Fasilitas Pelabuhan Berbasis Web guna Optimalisasi Monitoring Penanganan Kerusakan pada PT Bukit Asam Kertapati Port

### Project Type

Kerja Praktek (KP)

### Organization

PT Bukit Asam Dermaga Kertapati

### Development Approach

AI-Assisted Development

### UI Design Tool

Google Stitch

### Development Agent

Claude Code / Cursor / Gemini CLI / Copilot Agent

---

# PROJECT BACKGROUND

Saat ini proses pelaporan hasil inspeksi fasilitas pelabuhan masih dilakukan secara manual menggunakan dokumen, spreadsheet, dan komunikasi melalui pesan instan.

Kondisi tersebut menyebabkan:

* Informasi kerusakan tidak terpusat
* Sulit memonitor progres penanganan
* Tidak tersedia histori kerusakan yang terstruktur
* Sulit mengetahui kerusakan yang belum ditangani
* Sulit membuat laporan periodik
* Sulit memvisualisasikan lokasi kerusakan

Untuk mengatasi permasalahan tersebut diperlukan sistem E-Reporting berbasis web yang mampu mengintegrasikan proses pelaporan, monitoring, penugasan, dan penanganan kerusakan fasilitas pelabuhan.

---

# PROJECT OBJECTIVE

Membangun aplikasi web yang digunakan untuk:

1. Pelaporan kerusakan fasilitas pelabuhan
2. Monitoring status penanganan kerusakan
3. Penugasan tim penanganan
4. Tracking progres perbaikan
5. Visualisasi lokasi kerusakan pada peta
6. Penyediaan histori kerusakan
7. Penyusunan laporan monitoring

---

# PROJECT SCOPE

## Included Features

### Authentication

* Login
* Logout
* Role Based Access Control

### Dashboard

* Total laporan
* Kerusakan aktif
* Kerusakan selesai
* Kerusakan kritis
* Grafik statistik
* Peta kerusakan

### Facility Management

* Data fasilitas
* Kategori fasilitas
* Lokasi fasilitas

### Damage Reporting

* Input laporan kerusakan
* Upload foto
* Lokasi kerusakan
* Tingkat kerusakan
* Kategori kerusakan

### Damage Monitoring

* Monitoring seluruh laporan
* Tracking status
* Detail laporan

### Work Order Management

* Penugasan tim
* Target penyelesaian
* Monitoring pekerjaan

### Repair Progress

* Update progres
* Upload bukti pekerjaan
* Catatan pekerjaan

### Reporting

* Rekap laporan
* Export PDF

---

## Excluded Features

* Mobile Application
* ERP Integration
* SAP Integration
* IoT Devices
* Drone Inspection
* Inventory Management
* Procurement Management
* WhatsApp Gateway
* AI Detection
* Predictive Maintenance

---

# USER ROLES

## Administrator

Responsibilities:

* Manage users
* Manage facilities
* Manage categories
* Manage system configuration

Permissions:

* Full system access

---

## Inspector

Responsibilities:

* Create damage reports
* Upload inspection photos
* View report status

Permissions:

* Create reports
* View own reports

---

## Supervisor

Responsibilities:

* Verify reports
* Assign work orders
* Monitor progress
* Verify completion

Permissions:

* Verify reports
* Create assignments
* Approve completion

---

## Maintenance Team

Responsibilities:

* Execute repairs
* Update progress
* Upload completion evidence

Permissions:

* View assigned work orders
* Update progress

---

# BUSINESS PROCESS

Inspector
→ Create Damage Report

Supervisor
→ Verify Report

Supervisor
→ Assign Maintenance Team

Maintenance Team
→ Perform Repair

Maintenance Team
→ Update Progress

Maintenance Team
→ Upload Completion Evidence

Supervisor
→ Verify Completion

System
→ Close Report

---

# REPORT STATUS FLOW

DRAFT

↓

REPORTED

↓

VERIFIED

↓

ASSIGNED

↓

IN_PROGRESS

↓

WAITING_VERIFICATION

↓

COMPLETED

---

# DAMAGE SEVERITY

LOW

Description:
Minor issue with no operational impact.

Examples:

* Paint damage
* Minor lighting issue

---

MEDIUM

Description:
Moderate issue affecting operations.

Examples:

* Drain blockage
* Conveyor wear

---

HIGH

Description:
Major issue affecting performance.

Examples:

* Structural crack
* Equipment malfunction

---

CRITICAL

Description:
Severe issue requiring immediate action.

Examples:

* Major structural failure
* Main electrical failure

---

# CORE MODULES

MODULE 01
Authentication

MODULE 02
Dashboard

MODULE 03
Facility Management

MODULE 04
Damage Reporting

MODULE 05
Damage Monitoring

MODULE 06
Work Order Management

MODULE 07
Repair Progress

MODULE 08
GIS Monitoring

MODULE 09
Reporting

---

# DASHBOARD REQUIREMENTS

KPI Cards:

* Total Reports
* Active Reports
* Completed Reports
* Critical Reports

Charts:

* Damage by Month
* Damage by Category
* Damage by Facility

Map:

* Damage Location Pins

Map Status Colors:

Red = Active

Yellow = In Progress

Green = Completed

---

# GIS REQUIREMENTS

Map Library:
Leaflet.js

Coordinate Type:
Latitude / Longitude

Functions:

* Show damage locations
* Open report details from marker
* Filter by status
* Filter by severity

---

# TECHNOLOGY STACK

Backend:
Laravel 12

Frontend:
Blade Template Engine

Styling:
Tailwind CSS

Database:
MySQL

Map:
Leaflet.js

Charts:
Chart.js

Alert:
SweetAlert2

Export:
DomPDF

Authentication:
Laravel Breeze

Storage:
Laravel Storage

Version Control:
Git

Repository:
GitHub

---

# ARCHITECTURE RULES

Use:

* MVC Pattern
* Service Layer
* Repository Pattern
* Form Request Validation
* Policy Authorization
* Eloquent ORM

Avoid:

* React
* Vue
* Alpine.js
* Livewire

Application must be server-rendered using Blade.

---

# UI DESIGN GUIDELINES

Design Style:

* Enterprise
* Industrial
* Corporate
* Clean
* Modern

Reference Inspiration:

* SAP Fiori
* Jira Service Management
* Logistics Management Systems

Color Palette:

Primary:
Dark Blue

Secondary:
Orange

Background:
White

Desktop First Design

Responsive Layout Required

---

# SUCCESS CRITERIA

The application is considered successful when:

1. Inspector can create damage reports.
2. Supervisor can verify reports.
3. Supervisor can assign maintenance teams.
4. Maintenance teams can update repair progress.
5. Supervisor can close completed reports.
6. Dashboard displays real-time monitoring data.
7. Damage locations are visualized on a map.
8. Reports can be exported as PDF.
9. Historical damage data can be searched and reviewed.

---

# KP DELIVERABLES

1. Web Application
2. Database Design
3. ERD
4. Use Case Diagram
5. Activity Diagram
6. Class Diagram
7. System Architecture
8. User Documentation
9. KP Report

END OF DOCUMENT
