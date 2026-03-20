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

## Quick Start

Get the application running in 5 minutes:

```bash
# 1. Clone the repository
git clone https://github.com/renvelitario/inventory-management-system.git
cd inventory-management-system

# 2. Install dependencies
composer install

# 3. Create .env file with your database credentials
# Edit .env with your DB_HOST, DB_NAME, DB_USER, DB_PASSWORD

# 4. Import database schema
# - Open phpMyAdmin: http://localhost/phpmyadmin/
# - Create database 'ims_db'
# - Import config/database/ims_db.sql

# 5. Start Apache & MySQL in XAMPP, then access:
# http://localhost/inventory-management-system
```

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

```bash
# Copy/rename the template if available, or create a new .env file:
cp .env.example .env    # On Linux/Mac
copy .env.example .env  # On Windows (or create manually)
```

Edit `.env` and configure your database connection:
```ini
APP_NAME=Inventory Management System
APP_DEBUG=false

DB_HOST=localhost
DB_NAME=ims_db
DB_USER=root
DB_PASSWORD=
```

**Default XAMPP Configuration:**
- `DB_HOST`: `localhost`
- `DB_USER`: `root`
- `DB_PASSWORD`: `` (empty)

> **Note:** The `.env` file is ignored by Git to protect your sensitive credentials. Never commit this file to version control.

### 4. Database Setup

The application requires a MySQL database. Follow these steps:

1. **Start MySQL**: Open XAMPP Control Panel and start the MySQL service.
2. **Create Database**: Open phpMyAdmin at `http://localhost/phpmyadmin/`
3. **Create empty database** named `ims_db`
4. **Import database schema**:
   - Select the `ims_db` database
   - Go to the **Import** tab
   - Browse and select `config/database/ims_db.sql`
   - Click **Go** to import tables and sample data

### 5. Verify Installation

After completing the above steps, verify your setup:

1. **Start your web server**: Open XAMPP Control Panel and start Apache
2. **Access the application**:
   ```
   http://localhost/inventory-management-system
   ```
3. You should see the **Login page**
4. **Create an account** or login with sample credentials from the database

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
