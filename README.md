# 🚀 Integrated Event Management System

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)

> A comprehensive web-based platform designed to streamline event organization, facilitate team collaboration, and automate accountability reporting.

---

## 📖 About The Project

The **Integrated Event Management System** is a robust web application built with **Laravel 11**. It solves the problem of manual event tracking by providing a centralized dashboard for Event Organizers (Ketua), Committee Members, and Sponsors.

Key capabilities include **Role-Based Access Control (RBAC)** to secure data, a **Kanban-style Task Management** system for real-time progress tracking, and an **Automated PDF Generator** for creating instant accountability reports (Laporan Pertanggungjawaban).

### ✨ Key Features

* **🔐 Multi-Role Authentication (RBAC):**
    * **Super Admin:** Manage all users and global system settings.
    * **Organizer (Ketua):** Full control over specific events and member assignments.
    * **Member:** View tasks and update status (Pending/Done).
    * **Sponsor:** Read-only access to monitor event progress transparency.
* **📅 Event & Task Management:**
    * Create, Edit, and Delete events with detailed metadata.
    * Assign tasks to specific members with deadlines.
    * Real-time progress bar calculation based on completed tasks.
* **📄 Automated Reporting (DomPDF):**
    * One-click generation of official PDF reports.
    * Includes automatic formatting for A4 paper size.
* **🛡️ Security:**
    * Middleware grouping for route protection.
    * CSRF protection and input validation.

---

## 🛠️ Tech Stack

| Component | Technology |
| :--- | :--- |
| **Framework** | Laravel 11 (PHP 8.2+) |
| **Database** | MySQL / MariaDB |
| **Frontend** | Blade Templates, Bootstrap 5, JavaScript |
| **PDF Engine** | barryvdh/laravel-dompdf |
| **Icons** | Bootstrap Icons |

---

## 📸 Screenshots

| **Admin Dashboard** | **Task Management** |
|:---:|:---:|
| ![Dashboard](https://via.placeholder.com/600x300?text=Dashboard+Screenshot) | ![Tasks](https://via.placeholder.com/600x300?text=Task+Manager+Screenshot) |

| **PDF Report Output** | **Responsive Mobile View** |
|:---:|:---:|
| ![PDF](https://via.placeholder.com/600x300?text=PDF+Report+Screenshot) | ![Mobile](https://via.placeholder.com/600x300?text=Mobile+View) |

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally:

### 1. Clone the Repository
```bash
git clone [https://github.com/your-username/event-management-system.git](https://github.com/your-username/event-management-system.git)
cd event-management-system
