
<?php
require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {

        $mensagem = "Confira os dados. A senha precisa ter pelo menos 8 caracteres.";

    } else {

        $senhaProtegida = password_hash($senha, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO usuarios (nome, email, senha)
                    VALUES (:nome, :email, :senha)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":nome" => $nome,
                ":email" => $email,
                ":senha" => $senhaProtegida
            ]);

            header("Location: login.php");
            exit;

        } catch (PDOException $erro) {

            $mensagem = "Não foi possível cadastrar. Verifique se o e-mail já existe.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro SportPlex</title>
</head>
<body>

<h1>Criar conta SportPlex</h1>

<form method="POST">

    <input type="text" name="nome"
           placeholder="Nome completo" required>

    <input type="email" name="email"
           placeholder="E-mail" required>

    <input type="password" name="senha"
           placeholder="Senha (mínimo 8 caracteres)" required>

    <button type="submit">Cadastrar</button>

</form>

<p><?= htmlspecialchars($mensagem) ?></p>

<a href="login.php">Já tenho uma conta</a>

</body>
</html>
