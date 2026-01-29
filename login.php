<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Pogrešno korisničko ime ili lozinka";
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava - To-Do Lista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper">
        <!-- Dekorativni elementi -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        
        <div class="login-container">
            <!-- Logo sekcija -->
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-tasks"></i>
                    <span>To-Do<span class="logo-highlight">Master</span></span>
                </div>
                <h1>Dobrodošli Nazad</h1>
                <p class="subtitle">Prijavite se da pristupite svojim zadacima</p>
            </div>
            
            <!-- Login forma -->
            <form method="POST" class="login-form">
                <!-- Obaveštenje o grešci -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Input polja -->
                <div class="input-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Korisničko ime ili Email
                    </label>
                    <input type="text" id="username" name="username" 
                           placeholder="Unesite korisničko ime ili email"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           required>
                    <div class="input-focus-line"></div>
                </div>
                
                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Lozinka
                    </label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" 
                               placeholder="Unesite lozinku" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-focus-line"></div>
                </div>
                
                <!-- Opcije ispod forme -->
                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Zapamti me
                    </label>
                    <a href="forgot_password.php" class="forgot-password">
                        Zaboravili ste lozinku?
                    </a>
                </div>
                
                <!-- Dugmad -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Prijavi se
                </button>
                
                <div class="divider">
                    <span>ili</span>
                </div>
                
                <!-- Social login (opciono) -->
                <div class="social-login">
                    <button type="button" class="btn-social btn-google">
                        <i class="fab fa-google"></i>
                        Prijavi se sa Google
                    </button>
                    <button type="button" class="btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i>
                        Prijavi se sa Facebook
                    </button>
                </div>
                
                <!-- Link za registraciju -->
                <div class="register-link">
                    Nemate nalog? 
                    <a href="register.php">Registrujte se ovde</a>
                </div>
            </form>
            
            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; 2024 To-Do Master. Sva prava zadržana.</p>
                <a href="#"><i class="fas fa-question-circle"></i> Pomoć</a>
            </div>
        </div>
    </div>
    
    <script>
    // Prikaz/sakrivanje lozinke
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Animacija input polja
    document.querySelectorAll('.input-group input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    </script>
</body>
</html>