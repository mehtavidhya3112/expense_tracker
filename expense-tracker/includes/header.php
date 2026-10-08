<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        /* Applied before paint so there is no flash of the wrong theme. */
        (function () {
            var saved = localStorage.getItem('expenseTrackerTheme');
            var theme = saved === 'dark' || saved === 'light'
                ? saved
                : (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>

<header class="navbar">
    <div class="container navbar-inner">
        <a href="index.php" class="brand">
            <span class="brand-icon">💰</span>
            <span class="brand-text">Expense<span class="brand-accent">Tracker</span></span>
        </a>
        <nav class="nav-links">
            <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="all_expenses.php" class="<?php echo $current_page === 'all_expenses.php' ? 'active' : ''; ?>">All Expenses</a>
            <a href="reports.php" class="<?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">Reports</a>
            <a href="about.php" class="<?php echo $current_page === 'about.php' ? 'active' : ''; ?>">About</a>
            <a href="add_expense.php" class="btn-nav <?php echo $current_page === 'add_expense.php' ? 'active' : ''; ?>">+ Add Expense</a>
            <button type="button" id="themeToggle" class="theme-toggle" aria-label="Toggle dark mode" title="Toggle dark / light mode">
                <span class="theme-icon theme-icon-sun">☀️</span>
                <span class="theme-icon theme-icon-moon">🌙</span>
            </button>
        </nav>
    </div>
</header>

<main class="container page-content">

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" id="flashAlert">
            <span><?php echo htmlspecialchars($_GET['success']); ?></span>
            <button type="button" class="alert-close" aria-label="Dismiss">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error" id="flashAlert">
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
            <button type="button" class="alert-close" aria-label="Dismiss">&times;</button>
        </div>
    <?php endif; ?>
