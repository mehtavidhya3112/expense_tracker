<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$database = new Database();
$pdo = $database->getConnection();

$month = isset($_GET['month']) ? (int) $_GET['month'] : null;
$year  = isset($_GET['year'])  ? (int) $_GET['year']  : null;

if ($month && $year) {
    $expenses = getExpensesForPeriod($pdo, $month, $year);
    $filename = 'expenses_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.csv';
} else {
    $expenses = getAllExpenses($pdo);
    $filename = 'expenses_all.csv';
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Title', 'Category', 'Amount', 'Date', 'Notes']);

foreach ($expenses as $exp) {
    fputcsv($output, [
        $exp['id'],
        $exp['title'],
        $exp['category'],
        $exp['amount'],
        $exp['expense_date'],
        $exp['notes']
    ]);
}

fclose($output);
exit;
