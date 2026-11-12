# Case_Study_Section_9

# Kopi Piro — Coffee Shop Management & Ordering System

| **Name** | **NRP** |
|---|---|
| Ahmad Farras Favian A | 5025251005 |

A PHP (procedural MySQLi) + Bootstrap 5 web application for managing a coffee shop, products, customer orders, and staff access. Built with role-based access control and a MySQL relational database.

## Features

- Product management: add, edit, and remove products, including product images, descriptions, categories, and prices
- Product categories: coffee, food, non-caffeine beverages, and other products
- Customer registration and login using `password_hash()` / `password_verify()` and `$_SESSION`
- Member discount: registered customers receive a 10% discount, while guests pay regular prices
- Shopping cart supporting multiple products and quantity updates
- Checkout system that stores orders and individual order items in the database
- Guest checkout without requiring an account
- Order history for registered customers
- One-to-many relationship between `orders` and `order_items`
- Cashier assignment for individual orders
- Sales dashboard with order statistics and revenue charts
- Sales report export in CSV format, compatible with Microsoft Excel
- Prepared statements (`mysqli_prepare`) to help prevent SQL injection
- Output escaping (`htmlspecialchars`) to help prevent cross-site scripting (XSS)
- Role-based access control enforced on the server side

## Roles

| **Action** | **owner** | **cashier** | **customer** | **guest** |
|---|:---:|:---:|:---:|:---:|
| View product menu | Yes | Yes | Yes | Yes |
| Add products to cart | Yes | Yes | Yes | Yes |
| Place orders | Yes | Yes | Yes | Yes |
| Receive 10% discount | No | No | Yes | No |
| View personal order history | No | No | Yes | No |
| Manage products | Yes | No | No | No |
| View all orders | Yes | No | No | No |
| Assign orders to cashiers | Yes | No | No | No |
| View assigned orders | No | Yes | No | No |
| Update assigned order status | No | Yes | No | No |
| View sales dashboard and reports | Yes | No | No | No |
| Export sales report | Yes | No | No | No |

## Requirements

- XAMPP (PHP 8.1+, Apache, MySQL/MariaDB)
- Web browser
- Internet connection for Bootstrap 5 and Chart.js CDN resources

## Setup

1. Extract the zip file into `C:\xampp\htdocs`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin → **Import** → choose `database.sql` → Go.
4. Check the database settings in `config.php` (defaults: host `localhost`, user `root`, empty password, database `kopi_piro`).
5. Open `http://localhost/kopi_piro/seed_users.php` **once** to create the initial owner and cashier accounts.
6. **Delete `seed_users.php` after running it.**
7. Open `http://localhost/kopi_piro/` to access the application.

### Default Staff Accounts

| **Role** | **Email** | **Password** |
|---|---|---|
| Owner | `owner@kopipiro.test` | `owner123` |
| Cashier | `cashier@kopipiro.test` | `cashier123` |

Change the default passwords before using the application in a real environment.

Public registration is available for customers only. Users cannot assign themselves owner or cashier roles through the registration page.

## Project Structure

```text
kopi_piro/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── images/
├── cashier/
│   ├── index.php
│   └── orders.php
├── owner/
│   ├── index.php
│   ├── products/
│   │   ├── index.php
│   │   ├── form.php
│   │   └── delete.php
│   ├── orders/
│   │   └── index.php
│   └── reports/
│       ├── index.php
│       └── export_excel.php
├── uploads/
│   ├── .htaccess
│   └── product_images/
├── config.php
├── database.sql
├── functions.php
├── header.php
├── footer.php
├── index.php
├── cart.php
├── order_success.php
├── register.php
├── login.php
├── logout.php
├── my_orders.php
├── seed_users.php
└── README.md
```

## Order Workflow

1. Customers or guests browse the product menu and add items to their shopping cart.
2. The application retrieves product prices from the database when processing checkout.
3. Registered customers receive a 10% discount, while guest orders use the original product prices.
4. The order information and purchased items are saved in the database.
5. The owner views all orders and assigns each order to a cashier.
6. Cashiers access only the orders assigned to their accounts.
7. Cashiers update the order status, while the owner can monitor overall orders and sales.

## Notes

- `database.sql` creates a new database named `kopi_piro` and its required tables.
- Import this SQL file into a fresh database. It is separate from the original `db_coffee` database.
- Product images support JPG, PNG, and WEBP formats, with a maximum upload size of 3 MB.
- Sales reports are exported as CSV files, which can be opened using Microsoft Excel.
- The `product_reviews` table is included in the database, and product average ratings are displayed on the menu. A customer-facing review submission feature is not included in this version.
- The `seed_users.php` file should be deleted after the initial staff accounts have been created.
