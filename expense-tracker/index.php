<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$totalExpense    = getTotalExpense($pdo);
$monthlyTotal    = getMonthlyTotal($pdo);
$totalCount      = getTotalCount($pdo);
$topCategory     = getTopCategory($pdo);
$categoryTotals  = getCategoryTotals($pdo);
$recentExpenses  = array_slice(getAllExpenses($pdo), 0, 6);

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p class="page-subtitle">Track and manage your everyday expenses</p>
</div>

<section class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon icon-blue">💵</div>
        <div>
            <p class="summary-label">Total Spent</p>
            <h2 class="summary-value">₹<?php echo number_format($totalExpense, 2); ?></h2>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon icon-green">📅</div>
        <div>
            <p class="summary-label">This Month</p>
            <h2 class="summary-value">₹<?php echo number_format($monthlyTotal, 2); ?></h2>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon icon-purple">🧾</div>
        <div>
            <p class="summary-label">Total Entries</p>
            <h2 class="summary-value"><?php echo $totalCount; ?></h2>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon icon-orange">🏷️</div>
        <div>
            <p class="summary-label">Top Category</p>
            <h2 class="summary-value summary-value-small"><?php echo htmlspecialchars($topCategory); ?></h2>
        </div>
    </div>
</section>

<section class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h3>Spending by Category</h3>
        </div>
        <div class="card-body">
            <?php if (count($categoryTotals) === 0): ?>
                <p class="empty-state">No expenses recorded yet.</p>
            <?php else: ?>
                <?php
                $maxTotal = max(array_column($categoryTotals, 'total'));
                foreach ($categoryTotals as $cat):
                    $percent = $maxTotal > 0 ? ((float) $cat['total'] / (float) $maxTotal) * 100 : 0;
                ?>
                <div class="bar-row">
                    <div class="bar-label">
                        <span><?php echo htmlspecialchars($cat['category']); ?></span>
                        <span>₹<?php echo number_format($cat['total'], 2); ?></span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: <?php echo $percent; ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Recent Expenses</h3>
            <a href="all_expenses.php" class="link-view-all">View All &rarr;</a>
        </div>
        <div class="card-body">
            <?php if (count($recentExpenses) === 0): ?>
                <p class="empty-state">No expenses yet. <a href="add_expense.php">Add your first expense</a>.</p>
            <?php else: ?>
                <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentExpenses as $exp): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($exp['title']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($exp['category']); ?></span></td>
                            <td><?php echo date('d M Y', strtotime($exp['expense_date'])); ?></td>
                            <td class="text-right amount">₹<?php echo number_format($exp['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
