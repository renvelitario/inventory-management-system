# Inventory Management System

A web-based inventory management system built with PHP and MySQL that allows administrators to manage products, customers, purchases, and orders in a centralized dashboard.

This project was developed as part of an Applications and Development course and demonstrates backend CRUD operations, authentication, and basic inventory workflow management.

## Features
- User Authentication (Login / Register) 
- Dashboard Overview
- Manage Products (Add, Update, List)
- Manage Customers (Add, Update, List)
- Manage Purchases and Orders 
- Profile Settings & Password Management

## Prerequisites
To run this project locally, you need a local server environment with PHP and MySQL. 
- **XAMPP**, **WAMP**, or **MAMP** (XAMPP recommended)
- **PHP** (7.4 or newer recommended)
- **MySQL** Database

## Setup and Installation

### 1. Clone or Fork the Repository

First, fork the project to your own GitHub account or clone it directly to your local machine inside your web server's document root directory (e.g., `C:\xampp\htdocs` for XAMPP):
```bash
git clone https://github.com/renvelitario/inventory-management-system.git
cd inventory-management-system
```

### 2. Install Dependencies (Composer)

The project uses Composer for dependency management:
```bash
composer install
```

### 3. Environment Configuration

Create a `.env` file in the project root directory to configure your environment variables:

```ini
APP_NAME=Inventory Management System
APP_DEBUG=false

DB_HOST=localhost
DB_NAME=ims_db
DB_USER=root
DB_PASSWORD=
```

> **Note:** The `.env` file is ignored by Git to protect your sensitive credentials. Never commit this file to version control.

### 4. Database Setup

1. Start Apache & MySQL in XAMPP  
2. Open http://localhost/phpmyadmin  
3. Create database: `ims_db`  
4. Import: `config/database/ims_db.sql`

## Run the Project

Open in browser:
```
http://localhost/inventory-management-system
```

### 6. Project Structure

The application uses an MVC (Model-View-Controller) architecture:

```
├── app/              # Application code
│   ├── models/       # Database models
│   ├── controllers/  # Request handlers
│   └── views/        # HTML templates
├── config/           # Configuration files
├── public/           # Web root (entry point: index.php)
│   └── assets/       # CSS, images, etc.
├── routes/           # Route definitions
├── src/              # Core classes (Database, Router)
└── .env              # Environment variables (create this)
```

## Important Note on Security

This repository includes a `.gitignore` file that ignores sensitive configuration files:
- `.env` — Database credentials and environment secrets
- `config/database.php` — Database configuration (legacy)

**Never commit** these files to version control. The application reads configuration from `.env` first, so ensure this file is created locally and never pushed to GitHub.

## Developed For
*Applications and Development Course (PHP)*

*Date: July 2023*

*Date: July 2023*

