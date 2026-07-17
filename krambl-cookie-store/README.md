# Krambl Cookie Store

A beginner-friendly PHP and MySQL final project using a Filipino-inspired cookie-store design and the selected Krambl logo.

## Technology

- PHP without a PHP framework
- MySQL
- HTML
- CSS
- Basic JavaScript
- Procedural `mysqli` functions

The project deliberately avoids PDO, classes, object-oriented database calls, prepared-statement objects, `bind_param()`, and `fetch_assoc()`.

## Local installation

1. Copy the folder to `C:\xampp\htdocs\krambl-cookie-store`.
2. Start Apache and MySQL.
3. Import `database/krambl_store.sql` through phpMyAdmin.
4. Visit `http://localhost/krambl-cookie-store`.

## Database connection

Every PHP page includes the root `config.php`. That file creates the shared `$conn` variable using:

```php
$conn = mysqli_connect($host, $username, $password);
mysqli_select_db($conn, $database);
```

Therefore, pages such as `register.php` can use `$conn` after this line:

```php
include __DIR__ . "/config.php";
```

## Required functions included

- Admin account management
- Product, price, category, and stock management
- Inventory report
- Audit-log report
- Buyer registration with required fields
- Basic email-verification link
- Categorized store
- Cart, checkout, and payment pages
- About page with company and members
- Shared group logo and educational disclaimer

## Accounts

Administrator: `admin@krambl.test` / `Admin123!`

Buyer: `buyer@krambl.test` / `Buyer123!`
