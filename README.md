<p align="center">
<a href="#">
<img src="/public/uploads/img/logo-irms-transparent.png" width="400" alt="IRMS Logo">
</a>

# IRMS - Inventory Rack Management System
</p>

IRMS is a web-based application designed to efficiently manage inventory racks, users, warehouses, and site information for Printwell, Inc. and its affiliates.

## Features

- User management with profile pictures, roles, and access levels
- Warehouse and site management with location details
- Responsive dashboard and data tables
- Modal-based CRUD operations for users and warehouses
- Secure authentication and session management
- Real-time search and filtering
- Audit trail for user creation and updates
- Environment configuration via `.env.example`
- Stored procedures for efficient database operations
- Mobile-friendly interface
- Select site, warehouse, and bay with dynamic filtering
- Temporary form caching for user convenience

## Technologies Used

- Laravel (PHP Framework)
- Bootstrap 5 & Bootstrap Icons
- AdminLTE (UI Theme)
- jQuery & DataTables
- SQL Server (with stored procedures)

## Getting Started

1. **Clone the repository**
2. **Install dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Configure your `.env` file** for database and mail settings.
4. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
5. **Start the development server**
   ```bash
   php artisan serve
   ```

## Environment Configuration

Before running the application, copy the example environment file and update it with your settings:

```bash
cp .env.example .env
```

Open `.env` and configure the following:

- **App Key**:  
  Generate a new application key for security:
  ```bash
  php artisan key:generate
  ```
  This will automatically update the `APP_KEY` value in your `.env` file.

- **Database settings**:  
  Set your database host, port, name, username, and password for each connection (Main, PI-SP, FP-SP, PIGRP-SP).
- **Session and cache settings**:  
  Adjust session, cache, and queue drivers as needed.
- **Mail settings**:  
  Set up your mailer, host, port, username, password, and sender address.
- **Other environment variables**:  
  Update any other values to match your local or production environment.

Refer to `.env.example` for all available configuration options.

## Usage

- Access the dashboard at `/irms`
- Log in using your assigned credentials
- Configure your environment by copying `.env.example` to `.env` and updating the settings
- Manage users, warehouses, and sites via the sidebar navigation
- Add, edit, and delete records using modals
- Search and filter data in tables using the built-in DataTables features
- Use the responsive interface on desktop or mobile devices
- All changes are tracked for audit purposes

## Creators <br>

**Jhon Patrick M. Torres**  <br>
Lead Developer / System Analyst Programmer <br>

**Aron Kyle Suarnaba**  <br>
System Analyst Programmer Trainee

<!-- ## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT). -->

---
<p align="center" style="font-family: 'Century Schoolbook', serif; font-weight: bold; font-style: italic; font-size: 2rem;">
    <a href="http://www.printwell.com.ph" style="text-decoration: none; color: inherit;">
        <img src="/public/uploads/img/printwell.png" width="400" alt="IRMS Logo">
    </a>
</p>
<br>
IRMS &copy; 2025 Printwell, Inc