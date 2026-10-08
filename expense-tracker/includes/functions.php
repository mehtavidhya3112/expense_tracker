<?php
/**
 * Helper functions for the Expense Tracker application.
 * All database access uses prepared statements (PDO) to
 * prevent SQL injection.
 */

function getCategories()
{
    return [
        'Food & Dining',
        'Transportation',
        'Shopping',
        'Bills & Utilities',
        'Entertainment',
        'Health & Fitness',
        'Education',
        'Travel',
        'Other'
    ];
}

function addExpense($pdo, $title, $category, $amount, $expense_date, $notes)
{
    $sql = "INSERT INTO expenses (title, category, amount, expense_date, notes)
            VALUES (:title, :category, :amount, :expense_date, :notes)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':title'        => $title,
        ':category'     => $category,
        ':amount'       => $amount,
        ':expense_date' => $expense_date,
        ':notes'        => $notes
    ]);
}

function getAllExpenses($pdo, $category = '', $search = '', $orderBy = 'expense_date DESC, id DESC')
{
    $sql = "SELECT * FROM expenses WHERE 1=1";
    $params = [];

    if (!empty($category)) {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }

    if (!empty($search)) {
        $sql .= " AND (title LIKE :search OR notes LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $sql .= " ORDER BY " . $orderBy;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getExpenseById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

function updateExpense($pdo, $id, $title, $category, $amount, $expense_date, $notes)
{
    $sql = "UPDATE expenses
            SET title = :title, category = :category, amount = :amount,
                expense_date = :expense_date, notes = :notes
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':title'        => $title,
        ':category'     => $category,
        ':amount'       => $amount,
        ':expense_date' => $expense_date,
        ':notes'        => $notes,
        ':id'           => $id
    ]);
}

function deleteExpense($pdo, $id)
{
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

function getTotalExpense($pdo)
{
    $stmt = $pdo->query("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses");
    return (float) $stmt->fetch()['total'];
}

function getMonthlyTotal($pdo)
{
    $sql = "SELECT COALESCE(SUM(amount), 0) AS total FROM expenses
            WHERE MONTH(expense_date) = MONTH(CURDATE())
            AND YEAR(expense_date) = YEAR(CURDATE())";
    $stmt = $pdo->query($sql);
    return (float) $stmt->fetch()['total'];
}

function getTotalCount($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM expenses");
    return (int) $stmt->fetch()['cnt'];
}

function getCategoryTotals($pdo)
{
    $sql = "SELECT category, COALESCE(SUM(amount), 0) AS total
            FROM expenses GROUP BY category ORDER BY total DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getTopCategory($pdo)
{
    $totals = getCategoryTotals($pdo);
    return count($totals) > 0 ? $totals[0]['category'] : 'N/A';
}

/**
 * Returns all distinct years that have at least one expense,
 * newest first. Falls back to the current year if there is no
 * data yet, so report filters always have something to show.
 */
function getAvailableYears($pdo)
{
    $stmt = $pdo->query("SELECT DISTINCT YEAR(expense_date) AS yr FROM expenses ORDER BY yr DESC");
    $years = array_column($stmt->fetchAll(), 'yr');
    if (empty($years)) {
        $years = [ (int) date('Y') ];
    }
    return $years;
}

function getExpensesForPeriod($pdo, $month, $year)
{
    $sql = "SELECT * FROM expenses
            WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year
            ORDER BY expense_date DESC, id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':month' => $month, ':year' => $year]);
    return $stmt->fetchAll();
}

function getCategoryTotalsForPeriod($pdo, $month, $year)
{
    $sql = "SELECT category, COALESCE(SUM(amount), 0) AS total, COUNT(*) AS cnt
            FROM expenses
            WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year
            GROUP BY category ORDER BY total DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':month' => $month, ':year' => $year]);
    return $stmt->fetchAll();
}

function getDailyTotalsForPeriod($pdo, $month, $year)
{
    $sql = "SELECT expense_date, COALESCE(SUM(amount), 0) AS total
            FROM expenses
            WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year
            GROUP BY expense_date ORDER BY expense_date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':month' => $month, ':year' => $year]);
    return $stmt->fetchAll();
}

function getMonthName($month)
{
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    return $months[(int) $month] ?? 'Unknown';
}

function getHighestExpense($pdo, $month, $year)
{
    $sql = "SELECT * FROM expenses
            WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year
            ORDER BY amount DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':month' => $month, ':year' => $year]);
    $result = $stmt->fetch();
    return $result ?: null;
}
