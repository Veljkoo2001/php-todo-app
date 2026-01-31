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
    <!-- Flatpickr za lepši datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h1><i class="fas fa-tasks"></i> Moja To-Do Lista</h1>
        
        <!-- Forma za dodavanje zadataka -->
        <form action="add_task.php" method="POST" class="add-form">
            <div class="form-row">
                <!-- Polje za zadatak -->
                <div class="form-group">
                    <label for="task"><i class="fas fa-pen"></i> Novi zadatak</label>
                    <input type="text" 
                           name="task" 
                           id="task" 
                           placeholder="Šta treba da uradim?" 
                           required>
                </div>
                
                <!-- Prioritet -->
                <div class="form-group">
                    <label for="priority"><i class="fas fa-flag"></i> Prioritet</label>
                    <select name="priority" id="priority" class="priority-select">
                        <option value="low">🐢 Nizak</option>
                        <option value="medium" selected>⚡ Srednji</option>
                        <option value="high">🔥 Visok</option>
                    </select>
                </div>
                
                <!-- Deadline -->
                <div class="form-group">
                    <label for="deadline"><i class="far fa-calendar-alt"></i> Rok</label>
                    <input type="date" 
                           name="deadline" 
                           id="deadline" 
                           min="<?php echo date('Y-m-d'); ?>"
                           class="deadline-input">
                    <span class="deadline-hint">Ostavite prazno ako nema roka</span>
                </div>
                
                <!-- Dugme za dodavanje -->
                <button type="submit" class="btn-add">
                    <i class="fas fa-plus-circle"></i> Dodaj zadatak
                </button>
            </div>
        </form>

        <!-- Lista zadataka -->
        <ul class="task-list">
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
                    <?php
                    // Priprema podataka za prikaz
                    $deadlineText = '';
                    $deadlineClass = '';
                    $priorityBadge = '';
                    
                    if (!empty($task['deadline'])) {
                        $deadlineDate = strtotime($task['deadline']);
                        $today = time();
                        $daysLeft = floor(($deadlineDate - $today) / (60 * 60 * 24));
                        
                        // Odredi klasu po rokovima
                        if ($task['is_completed']) {
                            $deadlineClass = 'deadline-completed';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (završeno)';
                        } elseif ($daysLeft < 0) {
                            $deadlineClass = 'deadline-overdue';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (kasni ' . abs($daysLeft) . ' d.)';
                        } elseif ($daysLeft == 0) {
                            $deadlineClass = 'deadline-urgent';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (danas!)';
                        } elseif ($daysLeft <= 2) {
                            $deadlineClass = 'deadline-urgent';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (za ' . $daysLeft . ' d.)';
                        } elseif ($daysLeft <= 7) {
                            $deadlineClass = 'deadline-warning';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (za ' . $daysLeft . ' d.)';
                        } else {
                            $deadlineClass = 'deadline-normal';
                            $deadlineText = date('d.m.Y', $deadlineDate) . ' (za ' . $daysLeft . ' d.)';
                        }
                    }
                    
                    // Tekst za priority badge
                    if ($task['priority'] == 'high') {
                        $priorityBadge = '🔥 Visok';
                    } elseif ($task['priority'] == 'medium') {
                        $priorityBadge = '⚡ Srednji';
                    } else {
                        $priorityBadge = '🐢 Nizak';
                    }
                    ?>
                    
                    <li class="task-item <?php echo $task['is_completed'] ? 'completed' : ''; ?> priority-<?php echo $task['priority']; ?>">
                        <div class="task-content">
                            <span class="task-text"><?php echo htmlspecialchars($task['task_text']); ?></span>
                            
                            <div class="task-meta">
                                <!-- Prikaz deadline-a -->
                                <?php if (!empty($task['deadline'])): ?>
                                    <span class="deadline-badge <?php echo $deadlineClass; ?>">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo $deadlineText; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <!-- Prikaz prioriteta -->
                                <span class="priority-badge priority-<?php echo $task['priority']; ?>">
                                    <?php echo $priorityBadge; ?>
                                </span>
                            </div>
                        </div>
                        
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
                <div class="empty-state">
                    <i class="fas fa-clipboard-list fa-3x"></i>
                    <p>Nema zadataka. Dodajte prvi!</p>
                </div>
            <?php endif; ?>
        </ul>
        
        <?php if (count($tasks) > 0): ?>
            <?php
            // Brojanje završenih zadataka
            $total = count($tasks);
            $completed = 0;
            foreach ($tasks as $task) {
                if ($task['is_completed'] == 1) {
                    $completed++;
                }
            }
            $pending = $total - $completed;
            $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            ?>
            
            <div class="stats-container">
                <div class="stats-header">
                    <h3><i class="fas fa-chart-bar"></i> Statistika</h3>
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%">
                        <span class="progress-text"><?php echo $progress; ?>%</span>
                    </div>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Ukupno</span>
                            <span class="stat-value"><?php echo $total; ?></span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon completed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">Završeni</span>
                            <span class="stat-value"><?php echo $completed; ?></span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-label">U toku</span>
                            <span class="stat-value"><?php echo $pending; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicijalizuj datepicker
        flatpickr("#deadline", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true
        });
        
        // Dodaj efekat hover na zadatke
        const taskItems = document.querySelectorAll('.task-item');
        taskItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
            });
        });
    });
    </script>
</body>
</html>