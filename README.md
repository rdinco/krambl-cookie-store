# Krambl Cookie Store

- PHP 
- MySQL
- HTML
- CSS
- Basic JavaScript
- Procedural `mysqli` functions

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

