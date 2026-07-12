# HR Management System

A full-featured Human Resource Management System built with Laravel and Filament.

## Features

- **Employee Management** - Create, view, update, and manage employee records with profile avatars
- **Department Management** - Organize employees into departments with assigned managers
- **Attendance Tracking** - Clock in/out system with hours worked calculation
- **Leave Management** - Request, approve, and reject leaves with multiple leave types (sick, vacation, personal, maternity, paternity, unpaid)
- **Role-Based Access Control** - Three roles: Admin/HR, Manager, and Employee
- **Dashboard Widgets** - Stats overview, attendance charts, leave stats, and department breakdowns

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3+
- **Admin Panel:** Filament 3
- **Frontend:** Tailwind CSS 4, Vite 8
- **Database:** SQLite (configurable)

## Roles

| Role | Access |
|------|--------|
| **Admin/HR** | Full access via Filament admin panel (`/admin`) |
| **Manager** | Employee panel with department leave approval (`/manager-panel`) |
| **Employee** | Self-service panel for profile, attendance, and leaves (`/my-panel`) |

## Installation

```bash
composer setup
```

Or manually:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

## Development

```bash
composer dev
```

This starts the Laravel server, queue worker, logs (Pail), and Vite dev server concurrently.

## Testing

```bash
composer test
```

## License

MIT
