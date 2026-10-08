# 💰 Expense Tracker

A full-stack Expense Tracker web application built with **HTML, CSS, JavaScript and PHP (MySQL)** as a Web Technology college project. It lets a user add, view, search, filter, edit and delete daily expenses, generate monthly reports, export data to CSV, and switch between dark and light themes — all on top of a steel-green visual identity.

---

## ✨ Features

- **Dashboard** with live summary cards: total spent, this month's spending, total entries, and top spending category
- **Category-wise spending breakdown** shown as a simple horizontal bar chart (no external chart library needed)
- **Monthly Reports** page — pick any month/year to see totals, daily spending, category breakdown and the highest single expense, with a print-friendly layout
- **CSV Export** — download any month's expenses (or all of them) as a spreadsheet-ready `.csv` file
- **About page** summarising the project, its tech stack and security practices — handy for a class walkthrough
- **Add Expense** with server-side and client-side form validation
- **Edit / Update** existing expenses
- **Delete** expenses with a confirmation prompt
- **Search & Filter** expenses by keyword and category
- **Dark / Light theme toggle** with the choice remembered across visits (`localStorage`), applied before first paint so there's no flash of the wrong theme
- A consistent **steel-green** visual identity across both themes, with clean, responsive, professional UI that works on desktop and mobile
- Secure database access using **PDO prepared statements** (prevents SQL injection)
- All user output escaped with `htmlspecialchars()` (prevents XSS)

---

## 🛠️ Tech Stack

| Layer      | Technology              |
|------------|--------------------------|
| Frontend   | HTML5, CSS3, JavaScript (Vanilla) |
| Backend    | PHP 7.4+ (PDO)           |
| Database   | MySQL / MariaDB          |
| Server     | Apache (via XAMPP / WAMP / MAMP) |

No frameworks, no build tools, no external dependencies — everything runs out of the box.

---

## 📁 Project Structure

```
expense-tracker/
│
├── config/
│   └── database.php          # PDO database connection class
│
├── includes/
│   ├── functions.php         # Reusable DB helper functions
│   ├── header.php            # Shared page header + navbar
│   └── footer.php            # Shared page footer
│
├── assets/
│   ├── css/
│   │   └── style.css         # All application styling
│   └── js/
│       └── script.js         # Client-side interactivity
│
├── database/
│   └── schema.sql            # Database schema + sample data
│
├── index.php                 # Dashboard page
├── all_expenses.php          # Full expense list with search/filter
├── add_expense.php           # Add new expense (form + handler)
├── edit_expense.php          # Edit existing expense (form + handler)
├── delete_expense.php        # Delete expense handler
├── reports.php                # Monthly report (totals, breakdown, print view)
├── export_csv.php            # CSV export handler
├── about.php                  # About / tech stack / security notes page
│
├── .gitignore
├── LICENSE
└── README.md
```

---

## 🚀 Getting Started

### 1. Prerequisites

Install a local PHP + MySQL server stack:

- [XAMPP](https://www.apachefriends.org/) (recommended, cross-platform), or
- WAMP (Windows), or
- MAMP (macOS)

Make sure **Apache** and **MySQL** modules are running.

### 2. Clone the repository

```bash
git clone https://github.com/<your-username>/expense-tracker.git
```

Move (or clone directly into) the project folder inside your server's web root:

- XAMPP: `C:/xampp/htdocs/expense-tracker` (Windows) or `/Applications/XAMPP/htdocs/expense-tracker` (macOS)
- WAMP: `C:/wamp64/www/expense-tracker`

### 3. Create the database

Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and either:

- Click **Import**, choose `database/schema.sql`, and run it, **or**
- Run it from a terminal:

```bash
mysql -u root -p < database/schema.sql
```

This creates the `expense_tracker` database, the `expenses` table, and inserts a few sample rows so the dashboard isn't empty on first run.

### 4. Configure the database connection

Open `config/database.php` and update the credentials if needed (defaults match a standard XAMPP install: user `root`, empty password):

```php
private $host     = "localhost";
private $db_name  = "expense_tracker";
private $username = "root";
private $password = "";
```

### 5. Run the project

Start Apache & MySQL from your XAMPP/WAMP control panel, then open:

```
http://localhost/expense-tracker/
```

You should see the dashboard with sample expenses already loaded.

> **Note:** This project must be run through Apache/PHP (not opened as a plain HTML file), because the PHP files need to be executed by a PHP interpreter and connect to MySQL.

---

## 🖥️ Usage

| Action              | How |
|---------------------|-----|
| View dashboard      | Go to `index.php` |
| View all expenses   | Click **All Expenses** in the navbar |
| Add an expense      | Click **+ Add Expense**, fill the form, click **Save Expense** |
| Search / filter     | On the **All Expenses** page, use the search box and category dropdown |
| Edit an expense      | Click the ✏️ icon next to any expense |
| Delete an expense   | Click the 🗑️ icon and confirm the prompt |
| View a monthly report | Click **Reports**, pick a month/year, click **View Report** |
| Export a month to CSV | On the **Reports** page, click **Export CSV** |
| Print a report       | On the **Reports** page, click **Print** |
| Switch theme        | Click the ☀️ / 🌙 toggle button in the top-right of the navbar |

---

## 🌓 Dark / Light Theme

The navbar toggle button switches between a light and a dark variant of the app's **steel-green** theme. The choice is saved in the browser's `localStorage` (key: `expenseTrackerTheme`) so it's remembered on the next visit. If no preference has been saved yet, the app falls back to the visitor's OS-level color scheme preference.

---

## 🔒 Security Notes

- All SQL queries use **PDO prepared statements** with bound parameters — no raw string concatenation into SQL.
- All dynamic values printed into HTML pass through `htmlspecialchars()` to prevent Cross-Site Scripting (XSS).
- Both **server-side** (PHP) and **client-side** (HTML5 + JS) validation are used; server-side validation is the actual source of truth.

---

## 🎯 Possible Future Enhancements

- User authentication (login/register) so multiple users can track expenses separately
- PDF export of monthly reports (currently CSV export + browser print are supported)
- Budget limits per category with alerts
- Pagination for very large expense lists
- REST API layer for a future mobile app

---

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

---

## 👤 Author

Developed as a Web Technology college project.
