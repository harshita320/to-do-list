<?php
include 'db.php';

// Number of items per page
$items_per_page = 5;

// Get the current page or default to 1
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}

// Calculate the offset
$offset = ($current_page - 1) * $items_per_page;

// Fetch the total number of tasks
$total_sql = "SELECT COUNT(*) AS total FROM tasks";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch(PDO::FETCH_ASSOC);
$total_tasks = $total_row['total'];

// Calculate the total number of pages
$total_pages = ceil($total_tasks / $items_per_page);

// Fetch the tasks for the current page
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM tasks WHERE task LIKE :search ORDER BY created_at ASC LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':search', '%' . $search_query . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">To-Do List</h1>

    <!-- Search Form -->
    <form action="index.php" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit" class="btn btn-secondary">Search</button>
        </div>
    </form>

    <!-- Add Task Form -->
    <form action="add_task.php" method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="task" class="form-control" placeholder="Enter a new task" required>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

    <!-- Task List -->
    <ul class="list-group">
        <?php if (empty($results)): ?>
            <li class="list-group-item text-center">No tasks found.</li>
        <?php else: ?>
            <?php foreach ($results as $row): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="<?= $row['status'] === 'completed' ? 'text-decoration-line-through' : '' ?>">
                        <?= htmlspecialchars($row['task']) ?>
                    </span>
                    <div>
                        <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Mark as Done</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $current_page - 1 ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $current_page + 1 ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
</body>
</html>





<!-- <?php
// include 'db.php';

// // Fetch tasks from the database
// $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
// $result = $conn->query($sql);
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">To-Do List</h1>
    <form action="add_task.php" method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="task" class="form-control" placeholder="Enter a new task" required>
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
    <ul class="list-group">
        <?php //while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="<?//= //$row['status'] === 'completed' ? 'text-decoration-line-through' : '' ?>">
                    <?//= //htmlspecialchars($row['task']) ?>
                </span>
                <div>
                    <a href="update.php?id=<?//= //$row['id'] ?>" class="btn btn-sm btn-success">Mark as Done</a>
                    <a href="delete.php?id=<?//= //$row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </div>
            </li>
        <?php //endwhile; ?>
    </ul>
</div>
</body>
</html> -->
