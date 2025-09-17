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

## Usage

- Access the dashboard at `/irms`
- Manage users, warehouses, and sites via the sidebar navigation
- Add, edit, and delete records using modals
- Search and filter data in tables

## Creators

Lead Developer: 
**Jhon Patrick M. Torres**
System Analyst Programmer - L1

**Aron Kyle Suarnaba**
System Analyst Programmer - Trainee

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
<a href="http://www.printwell.com.ph" > Printwell, Inc. IRMS &copy; 2025 </a>