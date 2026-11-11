
<?php
session_start();

require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $sql = "SELECT * FROM usuarios WHERE email = :email";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":email" => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {

        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nome"] = $usuario["nome"];

        header("Location: reservar.php");
        exit;

    } else {

        $mensagem = "E-mail ou senha incorretos.";

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do SportPlex</title>
</head>
<body>

<h1>Bem-vindo à SportPlex faça o seu login aqui!</h1>

<form method="POST">

    <input type="email" name="email"
           placeholder="Seu e-mail" required>

    <input type="password" name="senha"
           placeholder="Sua senha" required>

    <button type="submit">Entrar</button>

</form>

<p><?= htmlspecialchars($mensagem) ?></p>

<a href="cadastro.php">Criar uma conta</a>

</body>
</html>
