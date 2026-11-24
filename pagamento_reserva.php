
<?php
require_once "conexao.php";

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

$consulta = $pdo->prepare("SELECT * FROM reservas WHERE id = ?");
$consulta->execute([$id]);
$reserva = $consulta->fetch();

if (!$reserva) {
    die("Reserva não encontrada.");
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $forma = $_POST["forma_pagamento"] ?? "";

    $formasPermitidas = [
        "PIX",
        "Cartão de crédito",
        "Cartão de débito"
    ];

    if (!in_array($forma, $formasPermitidas)) {
        $mensagem = "Selecione uma forma de pagamento.";
    } elseif ($reserva["status"] != "Pendente") {
        $mensagem = "Esta reserva já foi processada.";
    } else {
        $atualizar = $pdo->prepare("
            UPDATE reservas
            SET forma_pagamento = ?, status = 'Confirmada'
            WHERE id = ? AND status = 'Pendente'
        ");

        $atualizar->execute([$forma, $id]);

        if ($atualizar->rowCount() > 0) {
            $mensagem = "Reserva confirmada com sucesso!";
            $reserva["status"] = "Confirmada";
            $reserva["forma_pagamento"] = $forma;
        } else {
            $mensagem = "Não foi possível confirmar a reserva.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento da Reserva - SportPlex</title>
    <link rel="stylesheet" href="pagamento_reserva.css">
</head>
<body>

<div class="container-pagamento">

    <h1>Finalizar reserva</h1>

    <div class="resumo">
        <h2>Resumo da reserva</h2>

        <p>Quadra: <?= htmlspecialchars($reserva["quadra"]) ?></p>

        <p>Data: <?= date("d/m/Y", strtotime($reserva["inicio"])) ?></p>

        <p>Horário: <?= date("H:i", strtotime($reserva["inicio"])) ?>
            até <?= date("H:i", strtotime($reserva["fim"])) ?></p>

        <p>Participantes: <?= (int)$reserva["participantes"] ?></p>

        <p>Equipamentos:
            <?= htmlspecialchars($reserva["equipamentos"] ?: "Nenhum") ?>
        </p>

        <p>Valor: R$ <?= number_format($reserva["valor"], 2, ",", ".") ?></p>
    </div>

    <?php if ($reserva["status"] == "Pendente"): ?>

        <form method="POST">

            <input type="hidden" name="id" value="<?= $id ?>">

            <h2>Forma de pagamento</h2>

            <label>
                <input type="radio" name="forma_pagamento" value="PIX">
                PIX
            </label>

            <label>
                <input type="radio" name="forma_pagamento" value="Cartão de crédito">
                Cartão de crédito
            </label>

            <label>
                <input type="radio" name="forma_pagamento" value="Cartão de débito">
                Cartão de débito
            </label>

            <p class="aviso">
                Pagamento demonstrativo. Nenhum valor será cobrado.
            </p>

            <button type="submit">Confirmar pagamento simulado</button>

        </form>

    <?php else: ?>

        <h2>Reserva confirmada!</h2>
        <p>Forma de pagamento: <?= htmlspecialchars($reserva["forma_pagamento"]) ?></p>

    <?php endif; ?>

    <?php if ($mensagem != ""): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <a href="pagina.php?pagina=eventos">Voltar para os eventos</a>

</div>

</body>
</html>
