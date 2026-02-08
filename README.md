# 🚀 Integrated Event Management System

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-00000f?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap)

> **Professional Portfolio Project** > A comprehensive web-based platform designed to streamline event organization, facilitate team collaboration, and automate accountability reporting.

---

## 👨‍💻 Author's Note

**To Hiring Managers & Recruiters:**

Hello, I am **EineKleine**, a final-year Informatics student specializing in **Backend Development**.
This project demonstrates my ability to build a scalable **MVC application** with complex features such as **Role-Based Access Control (RBAC)**, **Task Management**, and **Automated Reporting**.

I focused on writing clean, maintainable code and delivering a user-friendly interface. I am eager to apply these skills in a professional environment.

---

## 🎯 Project Overview

### The Problem
Managing organizational events manually using spreadsheets often leads to data redundancy, miscommunication between committee members, and delays in creating accountability reports.

### The Solution
I developed this **Event Management System** to centralize all operations. It allows the Chairperson, Members, and Sponsors to collaborate on a single platform.

**Key Capabilities:**
1.  **Secure Access:** Multi-level authentication (Admin, Organizer, Member, Sponsor).
2.  **Efficiency:** Automated task tracking (Kanban logic) and PDF report generation.
3.  **Transparency:** Real-time progress monitoring for stakeholders.

---

## 🏗️ System Architecture

To ensure scalability and ease of maintenance, the system follows the strict **Model-View-Controller (MVC)** architectural pattern.

```mermaid
flowchart LR
    subgraph Client_Side ["Client Side (Frontend)"]
        Browser["User Browser"]
    end

    subgraph Server_Side ["Server Side (Laravel Backend)"]
        direction TB
        Router["Routing (Web.php)"]
        Middleware["Middleware (Security/Auth)"]
        Controller["Controllers (Business Logic)"]
        Model["Eloquent Models"]
        View["Blade Templates / PDF Engine"]
    end

    subgraph Database ["Data Storage"]
        MySQL[("MySQL Database")]
    end

    %% Data Flow
    Browser -->|HTTP Request| Router
    Router -->|Check Role| Middleware
    Middleware -->|Verified| Controller
    Controller <-->|Query/Save| Model
    Model <-->|Read/Write| MySQL
    Controller -->|Render Data| View
    View -->|HTML / PDF Response| Browser

    style Client_Side fill:#f9f9f9,stroke:#333,stroke-width:1px
    style Server_Side fill:#e1f5fe,stroke:#0277bd,stroke-width:2px
    style Database fill:#fff3e0,stroke:#ef6c00,stroke-width:2px
