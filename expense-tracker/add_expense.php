<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$categories = getCategories();
$errors = [];
$formData = [
    'title'        => '',
    'category'     => '',
    'amount'       => '',
    'expense_date' => date('Y-m-d'),
    'notes'        => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['title']        = trim($_POST['title'] ?? '');
    $formData['category']     = trim($_POST['category'] ?? '');
    $formData['amount']       = trim($_POST['amount'] ?? '');
    $formData['expense_date'] = trim($_POST['expense_date'] ?? '');
    $formData['notes']        = trim($_POST['notes'] ?? '');

    if ($formData['title'] === '') {
        $errors[] = 'Title is required.';
    }
    if (!in_array($formData['category'], $categories, true)) {
        $errors[] = 'Please select a valid category.';
    }
    if (!is_numeric($formData['amount']) || (float) $formData['amount'] <= 0) {
        $errors[] = 'Amount must be a positive number.';
    }
    if ($formData['expense_date'] === '' || strtotime($formData['expense_date']) === false) {
        $errors[] = 'Please provide a valid date.';
    }

    if (empty($errors)) {
        $saved = addExpense(
            $pdo,
            $formData['title'],
            $formData['category'],
            (float) $formData['amount'],
            $formData['expense_date'],
            $formData['notes']
        );

        if ($saved) {
            header('Location: index.php?success=' . urlencode('Expense added successfully!'));
            exit;
        }

        $errors[] = 'Something went wrong while saving the expense.';
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Add New Expense</h1>
    <p class="page-subtitle">Record a new expense entry</p>
</div>

<div class="card form-card">
    <div class="card-body">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="add_expense.php" class="expense-form" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" required maxlength="150"
                           value="<?php echo htmlspecialchars($formData['title']); ?>"
                           placeholder="e.g. Grocery Shopping">
                </div>
                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"
                                <?php echo $formData['category'] === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="amount">Amount (₹) <span class="required">*</span></label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                           value="<?php echo htmlspecialchars($formData['amount']); ?>" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label for="expense_date">Date <span class="required">*</span></label>
                    <input type="date" id="expense_date" name="expense_date" required
                           value="<?php echo htmlspecialchars($formData['expense_date']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Optional notes about this expense"><?php echo htmlspecialchars($formData['notes']); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Expense</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
