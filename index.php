<?php
include 'config.php';

// Ako korisnik nije prijavljen, preusmeri ga
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Dohvatanje svih zadataka
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do Lista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigacioni meni -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <i class="fas fa-tasks"></i> To-Do Lista
            </div>
            <div class="nav-user">
                <span>Zdravo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Odjavi se
                </a>
            </div>
        </div>
    </nav>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php 
        echo htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']);
        ?>
    </div>
<?php endif; ?>
    <div class="container">
        <h1><i class="fas fa-tasks"></i> Moja To-Do Lista</h1>
        
        <!-- Forma za dodavanje zadataka -->
        <form action="add_task.php" method="POST" class="add-form">
            <input type="text" name="task" placeholder="Unesi novi zadatak..." required>
            <button type="submit"><i class="fas fa-plus"></i> Dodaj</button>
        </form>

        <!-- Lista zadataka -->
        <ul class="task-list">
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="<?php echo $task['is_completed'] ? 'completed' : ''; ?>">
                        <span class="task-text"><?php echo htmlspecialchars($task['task_text']); ?></span>
                        
                        <div class="task-actions">
                            <!-- Označavanje kao završen/nezavršen -->
                            <a href="update_task.php?id=<?php echo $task['id']; ?>&status=<?php echo $task['is_completed'] ? '0' : '1'; ?>"
                               class="btn-complete">
                                <?php if ($task['is_completed']): ?>
                                    <i class="fas fa-undo"></i> Vrati
                                <?php else: ?>
                                    <i class="fas fa-check"></i> Završi
                                <?php endif; ?>
                            </a>
                            
                            <!-- Brisanje zadatka -->
                            <a href="delete_task.php?id=<?php echo $task['id']; ?>" 
                               class="btn-delete"
                               onclick="return confirm('Da li ste sigurni?')">
                                <i class="fas fa-trash"></i> Obriši
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-tasks">Nema zadataka. Dodajte prvi!</p>
            <?php endif; ?>
        </ul>
        
        <?php if (count($tasks) > 0): ?>
            <?php
            // Brojanje završenih zadataka TRENUTNOG KORISNIKA
            $total = count($tasks);
            
            // Ručno brojanje završenih iz niza
            $completed = 0;
            foreach ($tasks as $task) {
                if ($task['is_completed'] == 1) {
                    $completed++;
                }
            }
            
            $pending = $total - $completed;
            $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            ?>
                <span>Ukupno: <?php echo $total; ?></span>
                <span>Završeni: <?php echo $completed; ?></span>
                <span>U toku: <?php echo $pending; ?></span>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>