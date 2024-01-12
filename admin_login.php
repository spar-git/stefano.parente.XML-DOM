<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Credenziali desiderate
    $desiredUsername = 'stefano';
    $desiredPassword = 'pass123';

    if ($username === $desiredUsername && $password === $desiredPassword) {
        // Credenziali corrette, reindirizza alla pagina "reserved_area"
        header("Location: reserved_area.php");
        exit();
    } else {
        // Credenziali errate, mostra messaggio di errore
        $error_message = "Credenziali non valide. Riprova.";
    }
}
?>

<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Login area riservata</title>
    <link rel="stylesheet" href="stili_login.css">
</head>
<body>
    <div class="container">
        <h2>Accesso Amministratore</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p>Nome utente:</p>
            <input type="text" id="username" name="username" required>

            <p>Password:</p>
            <input type="password" id="password" name="password" required>

            <button type="submit">Accedi</button>
        </form>
    </div>
    <?php
        if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';        
        }
    ?>
</body>
</html>