
<?php
session_start();

$mensagem = "";
$erro = "";

if (!isset($_SESSION["reserva_pendente"])) {
    die("Nenhuma reserva encontrada. Volte e faça uma nova reserva.");
}

$reserva = $_SESSION["reserva_pendente"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $forma = $_POST["forma_pagamento"] ?? "";

    $formasPermitidas = [
        "PIX",
        "Cartão de crédito",
        "Cartão de débito"
    ];

    if (!in_array($forma, $formasPermitidas, true)) {
        $erro = "Selecione uma forma de pagamento.";

    } else {

        $_SESSION["reserva_confirmada"] = [
            "quadra" => $reserva["quadra"],
            "data" => $reserva["data"],
            "inicio" => $reserva["inicio"],
            "fim" => $reserva["fim"],
            "participantes" => $reserva["participantes"],
            "equipamentos" => $reserva["equipamentos"],
            "valor" => $reserva["valor"],
            "pagamento" => $forma
        ];

        unset($_SESSION["reserva_pendente"]);

        $mensagem = "Reserva confirmada com sucesso!";
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

    <style>
        body {
            background: #f3f6fc;
            font-family: Arial, sans-serif;
            color: #172b4d;
        }

        .container-pagamento {
            max-width: 650px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h1, h2 {
            color: #303f98;
        }

        .resumo {
            background: #f3f6fc;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            line-height: 1.8;
        }

        .opcao {
            display: block;
            padding: 15px;
            margin: 12px 0;
            border: 1px solid #d5dbea;
            border-radius: 8px;
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #303f98;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background: #202e78;
        }

        .mensagem {
            background: #dcfce7;
            color: #166534;
            padding: 18px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .erro {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
        }

        .voltar {
            display: inline-block;
            margin-top: 20px;
            color: #303f98;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container-pagamento">

    <h1>Finalizar reserva</h1>

    <div class="resumo">

        <h2>Resumo da reserva</h2>

        <p>
            <strong>Quadra:</strong>
            <?= htmlspecialchars($reserva["quadra"]) ?>
        </p>

        <p>
            <strong>Data:</strong>
            <?= htmlspecialchars($reserva["data"]) ?>
        </p>

        <p>
            <strong>Horário:</strong>
            <?= htmlspecialchars($reserva["inicio"]) ?>
            até
            <?= htmlspecialchars($reserva["fim"]) ?>
        </p>

        <p>
            <strong>Participantes:</strong>
            <?= (int)$reserva["participantes"] ?>
        </p>

        <p>
            <strong>Equipamentos:</strong>
            <?= htmlspecialchars($reserva["equipamentos"] ?: "Nenhum") ?>
        </p>

        <h2>
            Valor: R$
            <?= number_format($reserva["valor"], 2, ",", ".") ?>
        </h2>

    </div>

    <?php if ($mensagem): ?>

        <div class="mensagem">
            <h2><?= htmlspecialchars($mensagem) ?></h2>

            <p>
                Forma de pagamento:
                <?= htmlspecialchars($_SESSION["reserva_confirmada"]["pagamento"]) ?>
            </p>

            <p>Nenhum pagamento real foi realizado.</p>
        </div>

        <a class="voltar" href="paginainicial.php?pagina=eventos">
            Voltar para os eventos
        </a>

    <?php else: ?>

        <?php if ($erro): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
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

            <p>
                Pagamento demonstrativo. Nenhum valor será cobrado.
            </p>

            <button type="submit">
                Confirmar pagamento
            </button>

        </form>

        <a class="voltar" href="reservas.php">
            Voltar para a reserva
        </a>

    <?php endif; ?>

</div>

</body>
</html>
