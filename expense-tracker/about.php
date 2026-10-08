<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$totalCount   = getTotalCount($pdo);
$totalExpense = getTotalExpense($pdo);

include 'includes/header.php';
?>

<div class="page-header">
    <h1>About This Project</h1>
    <p class="page-subtitle">Web Technology college project — Expense Tracker</p>
</div>

<div class="card">
    <div class="card-body">
        <p style="margin-bottom: 14px;">
            Expense Tracker is a full-stack web application that lets a user record, organize and
            review everyday expenses. It was built to demonstrate core Web Technology concepts:
            semantic HTML, responsive CSS, client-side JavaScript, and server-side PHP backed by a
            MySQL database — all working together as a complete CRUD (Create, Read, Update, Delete)
            application.
        </p>
        <p>
            So far the app is tracking <strong><?php echo $totalCount; ?></strong> expense
            <?php echo $totalCount === 1 ? 'entry' : 'entries'; ?> totalling
            <strong>₹<?php echo number_format($totalExpense, 2); ?></strong>.
        </p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Key Features</h3>
    </div>
    <div class="card-body">
        <div class="feature-grid">
            <div class="feature-item">
                <span class="feature-icon">📊</span>
                <h4>Dashboard</h4>
                <p>Live summary cards and a category spending chart at a glance.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">🔎</span>
                <h4>Search &amp; Filter</h4>
                <p>Find expenses instantly by keyword or category.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">✏️</span>
                <h4>Full CRUD</h4>
                <p>Add, edit and delete expenses with validated forms.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">📅</span>
                <h4>Monthly Reports</h4>
                <p>Period-based breakdowns with daily spending and top expense.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">⬇️</span>
                <h4>CSV Export</h4>
                <p>Download any period's expenses as a spreadsheet-ready CSV.</p>
            </div>
            <div class="feature-item">
                <span class="feature-icon">🌓</span>
                <h4>Dark / Light Mode</h4>
                <p>Theme preference is remembered across visits using localStorage.</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Technology Stack</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table tech-table">
                <thead>
                    <tr>
                        <th>Layer</th>
                        <th>Technology</th>
                        <th>Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Structure</td>
                        <td><span class="badge">HTML5</span></td>
                        <td>Semantic page markup</td>
                    </tr>
                    <tr>
                        <td>Styling</td>
                        <td><span class="badge">CSS3</span></td>
                        <td>Responsive layout, CSS variables, dark / light theming</td>
                    </tr>
                    <tr>
                        <td>Interactivity</td>
                        <td><span class="badge">JavaScript</span></td>
                        <td>Theme toggle, delete confirmation, form feedback</td>
                    </tr>
                    <tr>
                        <td>Server logic</td>
                        <td><span class="badge">PHP 7.4+</span></td>
                        <td>Routing, validation, CRUD operations</td>
                    </tr>
                    <tr>
                        <td>Data access</td>
                        <td><span class="badge">PDO</span></td>
                        <td>Prepared statements against MySQL</td>
                    </tr>
                    <tr>
                        <td>Database</td>
                        <td><span class="badge">MySQL / MariaDB</span></td>
                        <td>Persistent storage of expense records</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Security Practices</h3>
    </div>
    <div class="card-body">
        <ul style="list-style: disc; padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
            <li>All SQL queries use <strong>PDO prepared statements</strong> with bound parameters to prevent SQL injection.</li>
            <li>All dynamic output is passed through <strong>htmlspecialchars()</strong> to prevent Cross-Site Scripting (XSS).</li>
            <li>Both server-side (PHP) and client-side (HTML5 + JavaScript) input validation are used.</li>
        </ul>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
