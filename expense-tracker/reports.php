<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$availableYears = getAvailableYears($pdo);

$month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
$year  = isset($_GET['year'])  ? (int) $_GET['year']  : (int) date('Y');

if ($month < 1 || $month > 12) {
    $month = (int) date('n');
}
if (!in_array($year, $availableYears, true)) {
    $availableYears[] = $year;
    rsort($availableYears);
}

$periodExpenses   = getExpensesForPeriod($pdo, $month, $year);
$categoryTotals   = getCategoryTotalsForPeriod($pdo, $month, $year);
$dailyTotals      = getDailyTotalsForPeriod($pdo, $month, $year);
$highestExpense   = getHighestExpense($pdo, $month, $year);

$periodTotal = 0;
foreach ($periodExpenses as $exp) {
    $periodTotal += (float) $exp['amount'];
}
$entryCount = count($periodExpenses);
$averagePerEntry = $entryCount > 0 ? $periodTotal / $entryCount : 0;

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Reports</h1>
    <p class="page-subtitle">Monthly spending summary and category breakdown</p>
</div>

<div class="card no-print">
    <div class="card-body">
        <form method="GET" action="reports.php" class="period-form">
            <div class="form-group">
                <label for="month">Month</label>
                <select id="month" name="month">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php echo $m === $month ? 'selected' : ''; ?>>
                            <?php echo getMonthName($m); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <select id="year" name="year">
                    <?php foreach ($availableYears as $y): ?>
                        <option value="<?php echo $y; ?>" <?php echo $y === $year ? 'selected' : ''; ?>><?php echo $y; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-group-actions">
                <button type="submit" class="btn btn-primary">View Report</button>
                <button type="button" class="btn btn-secondary" onclick="window.print()">🖨️ Print</button>
                <a href="export_csv.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="btn btn-secondary">⬇ Export CSV</a>
            </div>
        </form>
    </div>
</div>

<div class="page-header" style="margin-top: 8px;">
    <h1 style="font-size: 1.3rem;"><?php echo getMonthName($month) . ' ' . $year; ?> Summary</h1>
</div>

<div class="stat-strip">
    <div class="stat-box">
        <p class="stat-label">Total Spent</p>
        <h2 class="stat-value">₹<?php echo number_format($periodTotal, 2); ?></h2>
    </div>
    <div class="stat-box">
        <p class="stat-label">Entries Logged</p>
        <h2 class="stat-value"><?php echo $entryCount; ?></h2>
    </div>
    <div class="stat-box">
        <p class="stat-label">Average per Entry</p>
        <h2 class="stat-value">₹<?php echo number_format($averagePerEntry, 2); ?></h2>
    </div>
</div>

<section class="dashboard-grid">
    <div class="card">
        <div class="card-header">
            <h3>Category Breakdown</h3>
        </div>
        <div class="card-body">
            <?php if (count($categoryTotals) === 0): ?>
                <p class="empty-state">No expenses recorded for this period.</p>
            <?php else: ?>
                <?php
                $maxTotal = max(array_column($categoryTotals, 'total'));
                foreach ($categoryTotals as $cat):
                    $percent = $maxTotal > 0 ? ((float) $cat['total'] / (float) $maxTotal) * 100 : 0;
                ?>
                <div class="bar-row">
                    <div class="bar-label">
                        <span><?php echo htmlspecialchars($cat['category']); ?> (<?php echo (int) $cat['cnt']; ?>)</span>
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
            <h3>Daily Spending</h3>
        </div>
        <div class="card-body">
            <?php if (count($dailyTotals) === 0): ?>
                <p class="empty-state">No daily data for this period.</p>
            <?php else: ?>
                <div class="daily-list">
                    <?php foreach ($dailyTotals as $day): ?>
                        <div class="daily-row">
                            <span class="daily-date"><?php echo date('d M, D', strtotime($day['expense_date'])); ?></span>
                            <span class="daily-amount">₹<?php echo number_format($day['total'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($highestExpense): ?>
<div class="card">
    <div class="card-header">
        <h3>Highest Expense This Period</h3>
    </div>
    <div class="card-body">
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
                    <tr>
                        <td><?php echo htmlspecialchars($highestExpense['title']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars($highestExpense['category']); ?></span></td>
                        <td><?php echo date('d M Y', strtotime($highestExpense['expense_date'])); ?></td>
                        <td class="text-right amount">₹<?php echo number_format($highestExpense['amount'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>All Entries — <?php echo getMonthName($month) . ' ' . $year; ?></h3>
    </div>
    <div class="card-body">
        <?php if (count($periodExpenses) === 0): ?>
            <p class="empty-state">No expenses found for this period.</p>
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
                    <?php foreach ($periodExpenses as $exp): ?>
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

<?php include 'includes/footer.php'; ?>
