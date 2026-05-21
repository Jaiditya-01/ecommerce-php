# Ecommerce Site PHP

A legacy PHP/MySQL ecommerce demo with product browsing, user registration/login, cart handling, admin product/category/user management, and password reset support.

## Requirements

- PHP 7.4+ with PDO MySQL enabled
- MySQL or MariaDB
- A local web server such as Apache/XAMPP/WAMP

## Setup

1. Copy `includes/config.example.php` to `includes/config.php`.
2. Update `includes/config.php` with your local database credentials.
3. Create a MySQL database named `ecomm`.
4. Import `database/ecomm.sql`.
5. Serve this folder from your web root, for example `http://localhost/ecommerce`.

Default admin login after importing the seed database:

- Email: `admin@example.com`
- Password: `password`

Change the default admin password immediately after first login.

## Optional Mail Setup

Password reset emails require SMTP settings in `includes/config.php`.

Set `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`, `SMTP_SECURE`, `SMTP_FROM_EMAIL`, and `SMTP_FROM_NAME`. Leave these empty in public repositories.

## Git Notes

`includes/config.php` is intentionally ignored because it can contain database and SMTP credentials. Commit `includes/config.example.php` instead so other developers know which settings are required.

The project currently keeps legacy frontend/vendor assets in the repository so it can run without a package-install step.
