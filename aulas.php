
<?php
$aulas = [
    [
        "nome" => "Pilates",
        "professor" => "Ana Souza",
        "descricao" => "Exercícios de postura, equilíbrio, mobilidade e fortalecimento.",
        "horarios" => "Segunda e quinta",
        "preco" => 35,
        "foto" => "foto-pilates"
    ],
    [
        "nome" => "Funcional",
        "professor" => "Rafael Lima",
        "descricao" => "Treinamento para desenvolver força, coordenação e resistência.",
        "horarios" => "Segunda e quarta",
        "preco" => 30,
        "foto" => "foto-funcional"
    ],
    [
        "nome" => "Spinning",
        "professor" => "Camila Santos",
        "descricao" => "Exercícios em bicicletas com música e intensidade variada.",
        "horarios" => "Terça e sexta",
        "preco" => 30,
        "foto" => "foto-spinning"
    ],
    [
        "nome" => "Alongamento",
        "professor" => "Lucas Ferreira",
        "descricao" => "Exercícios de alongamento, mobilidade e relaxamento.",
        "horarios" => "Todos os dias",
        "preco" => 25,
        "foto" => "foto-alongamento"
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulas - SportPlex</title>
    <link rel="stylesheet" href="aulas.css">
</head>
<body>

<header class="topo">
    <h2>SP SPORTPLEX</h2>

    <nav>
        <a href="paginainicial.php">Início</a>
        <a href="paginainicial.php?pagina=loja">Loja</a>
        <a href="paginainicial.php?pagina=eventos">Eventos</a>
    </nav>
</header>

<main class="container">

    <h1>Nossas aulas</h1>
    <p class="subtitulo">
        Conheça nossos professores e escolha sua próxima atividade.
    </p>

    <section class="grade-aulas">

        <?php foreach ($aulas as $aula): ?>

            <article class="card-aula">

                <div class="foto-professor <?= htmlspecialchars($aula["foto"]) ?>"></div>

                <div class="conteudo-aula">

                    <h2><?= htmlspecialchars($aula["nome"]) ?></h2>

                    <h3>Prof. <?= htmlspecialchars($aula["professor"]) ?></h3>

                    <p><?= htmlspecialchars($aula["descricao"]) ?></p>

                    <p class="horarios">
                        <?= htmlspecialchars($aula["horarios"]) ?>
                    </p>

                    <p class="preco">
                        R$ <?= number_format($aula["preco"], 2, ",", ".") ?> por aula
                    </p>

                    <a
                        href="inscricao_aula.php?aula=<?= urlencode($aula["nome"]) ?>"
                        class="botao">
                        Participar
                    </a>

                </div>
            </article>

        <?php endforeach; ?>

    </section>

</main>

<footer>
    2026 SportPlex | Trabalho escolar
</footer>

</body>
</html>
