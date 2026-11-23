
<?php

$ocupacoes = [
    [
        "nome" => "Musculação",
        "ocupacao" => 75,
        "capacidade" => 40,
        "pessoas" => 30
    ],
    [
        "nome" => "Cardio",
        "ocupacao" => 50,
        "capacidade" => 30,
        "pessoas" => 15
    ],
    [
        "nome" => "Sala de Funcional",
        "ocupacao" => 35,
        "capacidade" => 20,
        "pessoas" => 7
    ],
    [
        "nome" => "Piscina",
        "ocupacao" => 60,
        "capacidade" => 25,
        "pessoas" => 15
    ]
];

$totalPessoas = 0;
$totalCapacidade = 0;

foreach ($ocupacoes as $ambiente) {
    $totalPessoas += $ambiente["pessoas"];
    $totalCapacidade += $ambiente["capacidade"];
}

$ocupacaoGeral = round(($totalPessoas / $totalCapacidade) * 100);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ocupações - SportPlex</title>

    <link rel="stylesheet" href="ocupacoes.css">
</head>

<body>

<header class="topo-ocupacoes">

    <h1>SP SPORTPLEX</h1>

    <a href="paginainicial.php">Voltar ao início</a>

</header>

<main class="container-ocupacoes">

    <section class="introducao">
        <h2>Ocupação da Academia</h2>

        <p>
            Confira a quantidade de pessoas e a ocupação
            dos ambientes da SportPlex.
        </p>
    </section>

    <section class="resumo">

        <div class="card-resumo">
            <h3>Ocupação geral</h3>
            <strong><?= $ocupacaoGeral ?>%</strong>
        </div>

        <div class="card-resumo">
            <h3>Pessoas presentes</h3>
            <strong><?= $totalPessoas ?></strong>
        </div>

        <div class="card-resumo">
            <h3>Capacidade total</h3>
            <strong><?= $totalCapacidade ?></strong>
        </div>

    </section>

    <section class="ambientes">

        <h2>Ocupação por ambiente</h2>

        <div class="grade-ambientes">

            <?php foreach ($ocupacoes as $ambiente): ?>

                <?php
                    if ($ambiente["ocupacao"] >= 80) {
                        $situacao = "Alta ocupação";
                        $classe = "alta";
                    } elseif ($ambiente["ocupacao"] >= 50) {
                        $situacao = "Ocupação moderada";
                        $classe = "media";
                    } else {
                        $situacao = "Pouco movimentado";
                        $classe = "baixa";
                    }
                ?>

                <div class="card-ambiente">

                    <h3><?= htmlspecialchars($ambiente["nome"]) ?></h3>

                    <div class="informacoes">
                        <span>Ocupação</span>

                        <strong>
                            <?= $ambiente["ocupacao"] ?>%
                        </strong>
                    </div>

                    <div class="barra">
                        <div
                            class="progresso <?= $classe ?>"
                            style="width: <?= $ambiente["ocupacao"] ?>%">
                        </div>
                    </div>

                    <p>
                        Pessoas presentes:
                        <strong><?= $ambiente["pessoas"] ?></strong>
                    </p>

                    <p>
                        Capacidade:
                        <strong><?= $ambiente["capacidade"] ?></strong>
                    </p>

                    <span class="situacao <?= $classe ?>">
                        <?= $situacao ?>
                    </span>

                </div>

            <?php endforeach; ?>

        </div>

    </section>

</main>

<footer class="rodape-ocupacoes">
    <p>© 2026 SportPlex. Todos os direitos reservados.</p>
</footer>

</body>
</html>
