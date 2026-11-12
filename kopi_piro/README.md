# Kopi Piro

Kopi Piro is a PHP + MySQL coffee shop ordering system based on the supplied `coffee_shop` project. It keeps the original lightweight PHP/MySQL approach and Bootstrap interface, while expanding the database and roles for the case study.

## Features

- Public menu with coffee, food, non-caffeine and other products.
- Owner can add/edit products, set prices, upload images and hide products.
- Customer registration/login; registered customers receive a 10% checkout discount.
- Guest checkout is supported at regular prices.
- Cart supports multiple products and quantity changes.
- Orders store item names and historical prices.
- Owner can view all orders, assign a cashier and update order status.
- Cashiers can see and update only orders assigned to their account.
- Owner dashboard, daily sales chart and CSV export (opens in Excel).
- Customers can submit or update a 1–5 star rating for each product; the menu displays average ratings and review counts.

## Requirements

- XAMPP (Apache, PHP 8+, MySQL/MariaDB)
- Web browser
- Internet connection for Bootstrap and Chart.js CDN assets

## Installation (XAMPP)

1. Extract this project into `C:\\xampp\\htdocs\\kopi_piro`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database.sql`. This creates a new database named `kopi_piro`.
5. Check `config.php`. The defaults use `localhost`, user `root`, an empty password, port `3306`, and database `kopi_piro`.
6. Visit `http://localhost/kopi_piro/seed_users.php` once to create the initial owner and cashier accounts.
7. **Delete `seed_users.php` after it runs.**
8. Open `http://localhost/kopi_piro/`.

## Initial staff accounts

| Role | Email | Password |
|---|---|---|
| Owner | `owner@kopipiro.test` | `owner123` |
| Cashier | `cashier@kopipiro.test` | `cashier123` |

Change these credentials after the first login. Public registration always creates a `customer` account; users cannot choose a staff role through the registration form.

## Project structure

```text
kopi_piro/
├── assets/css/style.css
├── assets/images/
├── cashier/
│   ├── index.php
│   └── orders.php
├── owner/
│   ├── index.php
│   ├── products/
│   │   ├── index.php
│   │   ├── form.php
│   │   └── delete.php
│   ├── orders/index.php
│   └── reports/
│       ├── index.php
│       └── export_excel.php
├── uploads/product_images/
├── config.php
├── database.sql
├── functions.php
├── header.php
├── footer.php
├── index.php
├── cart.php
├── checkout flow: cart.php
├── order_success.php
├── register.php
├── login.php
├── logout.php
└── my_orders.php
```

## How the roles work

- **Owner:** full dashboard access, product management, all orders, cashier assignment, reports and export.
- **Cashier:** sees only orders where `cashier_id` matches their own user ID. They can update the status of those orders.
- **Customer:** browses the menu, orders products, receives a 10% discount and views their own order history.
- **Guest:** can order without an account but does not receive the member discount.

## Order workflow

1. A customer or guest adds products to the cart.
2. At checkout, the application reloads product prices from MySQL rather than trusting browser-submitted prices.
3. Registered customers receive a 10% discount; guests receive no discount.
4. The order and its item snapshots are stored in MySQL.
5. The owner opens **All Orders**, assigns a cashier and sets the order status.
6. The assigned cashier sees the order in **Assigned Orders** and can update its status.

## Notes

- The database script is for a **fresh Kopi Piro database**. It does not modify or delete the existing `db_coffee` database.
- Do not import the original `coffee_shop/database.sql` into the new `kopi_piro` database.
- Product images accept JPG, PNG or WEBP up to 3 MB.
- The report export is CSV format, which can be opened in Microsoft Excel. It is not a native `.xlsx` workbook.
- The `product_reviews` table is included and the menu displays average ratings/counts. Customers can submit one rating per product; submitting again updates their previous rating.
