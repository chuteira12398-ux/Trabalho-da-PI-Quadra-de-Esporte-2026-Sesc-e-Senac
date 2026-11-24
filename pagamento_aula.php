
<?php
session_start();

if (!isset($_SESSION["inscricao_aula"])) {
    header("Location: aulas.php");
    exit;
}

$inscricao = $_SESSION["inscricao_aula"];
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $forma = $_POST["forma_pagamento"] ?? "";

    $formasPermitidas = ["PIX", "Cartão de crédito", "Cartão de débito"];

    if (!in_array($forma, $formasPermitidas)) {
        $mensagem = "Selecione uma forma de pagamento.";

    } else {

        $_SESSION["pagamento_aula"] = $forma;

        header("Location: pagamento_aula.php?confirmado=1");
        exit;
    }
}

$confirmado = isset($_GET["confirmado"]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento da aula - SportPlex</title>
    <link rel="stylesheet" href="aulas.css">
</head>
<body>

<header class="topo">
    <h2>SP SPORTPLEX</h2>
    <a href="aulas.php">Voltar para aulas</a>
</header>

<main class="formulario">

    <?php if ($confirmado): ?>

        <h1>Inscrição confirmada!</h1>

        <div class="resumo">
            <h2><?= htmlspecialchars($inscricao["aula"]) ?></h2>
            <p>Professor: <?= htmlspecialchars($inscricao["professor"]) ?></p>
            <p>Data: <?= htmlspecialchars($inscricao["data"]) ?></p>
            <p>Horário: <?= htmlspecialchars($inscricao["horario"]) ?></p>
            <p>Participantes: <?= $inscricao["participantes"] ?></p>
            <p>Pagamento: <?= htmlspecialchars($_SESSION["pagamento_aula"] ?? "") ?></p>
            <p class="preco">
                Total: R$ <?= number_format($inscricao["valor"], 2, ",", ".") ?>
            </p>
        </div>

        <p class="sucesso">
            Sua inscrição foi realizada com sucesso!
            Este é um pagamento demonstrativo do projeto.
        </p>

        <a href="aulas.php" class="botao">Voltar para aulas</a>

    <?php else: ?>

        <h1>Finalizar inscrição</h1>

        <div class="resumo">

            <h2>Resumo do pedido</h2>

            <p>Aula: <?= htmlspecialchars($inscricao["aula"]) ?></p>
            <p>Professor: <?= htmlspecialchars($inscricao["professor"]) ?></p>
            <p>Data: <?= htmlspecialchars($inscricao["data"]) ?></p>
            <p>Horário: <?= htmlspecialchars($inscricao["horario"]) ?></p>
            <p>Participantes: <?= $inscricao["participantes"] ?></p>

            <p class="preco">
                Total: R$ <?= number_format($inscricao["valor"], 2, ",", ".") ?>
            </p>

        </div>

        <?php if ($mensagem != ""): ?>
            <p class="erro"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form method="POST">

            <h2>Forma de pagamento</h2>

            <label class="opcao">
                <input type="radio" name="forma_pagamento" value="PIX">
                PIX
            </label>

            <label class="opcao">
                <input type="radio" name="forma_pagamento" value="Cartão de crédito">
                Cartão de crédito
            </label>

            <label class="opcao">
                <input type="radio" name="forma_pagamento" value="Cartão de débito">
                Cartão de débito
            </label>

            <button type="submit">Confirmar inscrição</button>

        </form>

        <p class="aviso">
            Pagamento fictício para demonstração escolar.
            Nenhum dado bancário é solicitado ou armazenado.
        </p>

    <?php endif; ?>

</main>

</body>
</html>
