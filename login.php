<?php
require __DIR__ . "/config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  if ($username === "" || $password === "") {
    $error = "Debes introducir usuario y contraseña.";
  } else {
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user["password_hash"])) {
      $error = "Usuario o contraseña incorrectos.";
    } else {
      // Login correcto: guardar sesión
      $_SESSION["user_id"] = (int)$user["id"];
      $_SESSION["username"] = $user["username"];
      header("Location: index.php");
      exit;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <?php if ($error !== ""): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <label>Usuario</label><br>
    <input name="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>"><br><br>

    <label>Password</label><br>
    <input name="password" type="password"><br><br>

    <button type="submit">Entrar</button>
  </form>

  <p><a href="register.php">Crear cuenta (Registro)</a></p>
</body>
</html>

