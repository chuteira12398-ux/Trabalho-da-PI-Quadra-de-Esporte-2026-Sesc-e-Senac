
<?php
require_once "config/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $sql->execute([$email]);

    $usuario = $sql->fetch();

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["tipo"] = $usuario["tipo"];

        if ($usuario["tipo"] === "admin") {
            header("Location: admin/painel.php");
        } else {
            header("Location: cliente/painel.php");
        }
        exit;
    }

    $erro = "E-mail ou senha incorretos.";
}
?>

<h1>Login SportPlex</h1>

<?php if ($erro): ?>
    <p><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>

<a href="cadastro.php">Criar conta</a>
