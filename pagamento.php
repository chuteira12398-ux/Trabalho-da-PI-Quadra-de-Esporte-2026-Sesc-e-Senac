
<?php
require_once "pagamento.php";

$planos = [
    [
        "codigo" => "Basico",
        "nome" => "Plano Básico",
        "preco" => "29,99",
        "descricao" => "Acesso aos recursos básicos.",
        "beneficios" => [
            "Acesso à musculação",
            "Equipamentos básicos",
            "Suporte ao cliente"
        ]
    ],
    [
        "codigo" => "Padrao",
        "nome" => "Plano Padrão",
        "preco" => "49,99",
        "descricao" => "Acesso completo a recursos padrão.",
        "beneficios" => [
            "Acesso à musculação, natação e aulas em grupo",
            "Participação em aulas",
            "Suporte ao cliente"
        ]
    ],
    [
        "codigo" => "Premium",
        "nome" => "Plano Premium",
        "preco" => "79,99",
        "descricao" => "Acesso total aos recursos premium.",
        "beneficios" => [
            "Acesso completo",
            "Todas as atividades",
            "Benefícios exclusivos"
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planos - SportPlex</title>
    <link rel="stylesheet" href="planos.css">
</head>

<body>

<header class="cabecalho-planos">
    <h1>SP SPORTPLEX</h1>
    <a href="paginainicial.php">Voltar ao início</a>
</header>

<main class="container-planos">

    <h1 class="titulo-planos">Nossos Planos</h1>

    <p class="descricao-planos">
        Escolha o plano ideal para você!
    </p>

    <div class="grade-planos">

        <?php foreach ($planos as $plano): ?>

            <div class="card-plano">

                <h2><?= htmlspecialchars($plano["nome"]) ?></h2>

                <p class="preco-plano">
                    R$ <?= htmlspecialchars($plano["preco"]) ?>
                </p>

                <p><?= htmlspecialchars($plano["descricao"]) ?></p>

                <ul>
                    <?php foreach ($plano["beneficios"] as $beneficio): ?>
                        <li><?= htmlspecialchars($beneficio) ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="pagamento.php?plano=<?= urlencode($plano["codigo"]) ?>"
                   class="btn-plano">
                    Escolher plano
                </a>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<footer class="rodape-planos">
    <p>© 2026 SportPlex. Todos os direitos reservados.</p>
</footer>

</body>
</html>
