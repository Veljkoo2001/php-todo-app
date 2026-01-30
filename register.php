<?php
// register.php
include 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validacija
    if (empty($username)) $errors[] = "Korisničko ime je obavezno";
    if (empty($email)) $errors[] = "Email je obavezan";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email nije validan";
    if (strlen($password) < 6) $errors[] = "Lozinka mora imati najmanje 6 karaktera";
    if ($password !== $confirm_password) $errors[] = "Lozinke se ne poklapaju";
    
    // Provera da li korisnik postoji
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Korisničko ime ili email već postoje";
            } else {
                // Hash lozinke i snimi korisnika
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash]);
                
                // Automatski prijavi korisnika
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                // Postavi poruku za uspeh
                $_SESSION['success'] = "Uspešno ste se registrovali!";
                
                header("Location: index.php");
                exit();
            }
        } catch(PDOException $e) {
            $errors[] = "Greška pri registraciji: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Registracija</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Korisničko ime" 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            
            <input type="email" name="email" placeholder="Email" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            
            <input type="password" name="password" placeholder="Lozinka" required>
            
            <input type="password" name="confirm_password" placeholder="Potvrdi lozinku" required>
            
            <button type="submit">Registruj se</button>
        </form>
        
        <div class="auth-links">
            <p>Već imaš nalog? <a href="login.php">Prijavi se</a></p>
            <p><a href="index.php">Nazad na početnu</a></p>
        </div>
    </div>
</body>
</html>