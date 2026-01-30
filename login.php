<?php
// login.php
include 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $errors[] = "Unesite korisničko ime i lozinku";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Uspješna prijava
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                $_SESSION['success'] = "Uspešno ste se prijavili!";
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Pogrešno korisničko ime ili lozinka";
            }
        } catch(PDOException $e) {
            $errors[] = "Greška pri prijavi: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Koristi isti CSS kao za registraciju */
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Prijava</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Korisničko ime ili email" 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            
            <input type="password" name="password" placeholder="Lozinka" required>
            
            <button type="submit">Prijavi se</button>
        </form>
        
        <div class="auth-links">
            <p>Nemaš nalog? <a href="register.php">Registruj se</a></p>
            <p><a href="index.php">Nazad na početnu</a></p>
        </div>
    </div>
</body>
</html>