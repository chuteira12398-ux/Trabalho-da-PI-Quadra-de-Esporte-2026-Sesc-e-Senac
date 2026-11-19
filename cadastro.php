
<?php
require_once "config/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
        $erro = "Preencha os campos corretamente. A senha precisa ter pelo menos 8 caracteres.";
    } else {
        $verificar = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verificar->execute([$email]);

        if ($verificar->fetch()) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = $pdo->prepare(
                "INSERT INTO usuarios (nome, email, senha, tipo)
                 VALUES (?, ?, ?, 'cliente')"
            );

            $sql->execute([$nome, $email, $hash]);

            header("Location: login.php");
            exit;
        }
    }
}
?>

<h1>Cadastro SportPlex</h1>

<?php if ($erro): ?>
    <p><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha (mínimo 8 caracteres)" required>
    <button type="submit">Cadastrar</button>
</form>

<a href="login.php">Já tenho uma conta</a>
