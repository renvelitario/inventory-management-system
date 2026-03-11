# IM-SYSTEM (Inventory Management System)

A PHP-based web application for managing inventory, products, customers, and orders. This was built as a university project for an Applications and Development course.

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
git clone https://github.com/your-username/IM-SYSTEM.git
cd IM-SYSTEM
```

### 2. Database Setup
The application requires a MySQL database.
1. Make sure your local MySQL server is running (via XAMPP Control Panel).
2. Open phpMyAdmin (usually `http://localhost/phpmyadmin/`).
3. Create a new empty database named `ims_db` (or another name if you prefer).
4. Select the database and go to the **Import** tab.
5. Browse and select the `database/ims_db.sql` file provided in this repository.
6. Click **Go** to import the tables and initial data.

### 3. Application Configuration
You need to configure the database credentials so the application can connect to MySQL.

1. Navigate to the `config` directory.
2. Copy or rename the provided example file:
   - Make a copy of `config/database.example.php` 
   - Rename it to `database.php`
3. Open `database.php` in your text editor and update the connection details.
   ```php
   <?php
   return [
       'host' => 'localhost',
       'dbname' => 'ims_db',          // Your database name
       'user' => 'root',               // Your MySQL username (default for XAMPP is 'root')
       'password' => ''                // Your MySQL password (default for XAMPP is empty '')
   ];
   ```
   > **Note:** The `config/database.php` file is ignored by Git to protect your sensitive credentials.

### 4. Running the Project
Once the database is set up and configured, you can access the system through your web browser.

1. Start Apache and MySQL in your XAMPP Control Panel.
2. Open your web browser and go to:
   ```
   http://localhost/IM-SYSTEM/
   ```
3. You should see the login page. Since this system requires authentication, you can sign in using an existing account from the database layout or create a new one via the registration page.

## Important Note on Security
This repository includes a `.gitignore` file that deliberately ignores `config/database.php`. This ensures that your private database credentials, such as host, username, and password, are not accidentally pushed to a public repository. If you are pushing your own changes to GitHub, make sure never to commit your real `config/database.php` file.

## Developed For
*Applications and Development Course (PHP)*
