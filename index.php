<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Página de Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container">
    <div class="login-container">
      <h2 class="text-center mb-3">Login</h2>
      <form method="POST">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input type="text" class="form-control" name="nome" placeholder="Seu nome" required>
        </div>
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" placeholder="Digite sua senha" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Entrar</button>
        </div>
      </form>
      <a href="/cadastro.php">cadastro</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['nome']);
    $senha = htmlspecialchars($_POST['senha']);

    $db = new database();
    if ($db->login($name, $senha)) {
        header('Location: /home.php');
        die();
    }

    echo "invalid";
}
?>
