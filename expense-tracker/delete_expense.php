<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $expense = getExpenseById($pdo, $id);
    if ($expense) {
        deleteExpense($pdo, $id);
        header('Location: all_expenses.php?success=' . urlencode('Expense deleted successfully!'));
        exit;
    }
}

header('Location: all_expenses.php?error=' . urlencode('Expense not found.'));
exit;
