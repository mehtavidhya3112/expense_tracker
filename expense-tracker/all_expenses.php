<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$search   = isset($_GET['search']) ? trim($_GET['search']) : '';

$expenses   = getAllExpenses($pdo, $category, $search);
$categories = getCategories();

$grandTotal = 0;
foreach ($expenses as $exp) {
    $grandTotal += (float) $exp['amount'];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1>All Expenses</h1>
    <p class="page-subtitle">Browse, search and filter every recorded expense</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="all_expenses.php" class="filter-form">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" placeholder="Search by title or notes"
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>"
                            <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-group-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="all_expenses.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Results (<?php echo count($expenses); ?>)</h3>
        <span class="total-tag">Total: ₹<?php echo number_format($grandTotal, 2); ?></span>
    </div>
    <div class="card-body">
        <?php if (count($expenses) === 0): ?>
            <p class="empty-state">No expenses found matching your criteria.</p>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Notes</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expenses as $exp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exp['title']); ?></td>
                        <td><span class="badge"><?php echo htmlspecialchars($exp['category']); ?></span></td>
                        <td><?php echo date('d M Y', strtotime($exp['expense_date'])); ?></td>
                        <td class="notes-cell"><?php echo htmlspecialchars($exp['notes'] !== null && $exp['notes'] !== '' ? $exp['notes'] : '—'); ?></td>
                        <td class="text-right amount">₹<?php echo number_format($exp['amount'], 2); ?></td>
                        <td class="text-center actions-cell">
                            <a href="edit_expense.php?id=<?php echo (int) $exp['id']; ?>" class="action-btn edit-btn" title="Edit">✏️</a>
                            <a href="delete_expense.php?id=<?php echo (int) $exp['id']; ?>" class="action-btn delete-btn" title="Delete" data-confirm-delete>🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
