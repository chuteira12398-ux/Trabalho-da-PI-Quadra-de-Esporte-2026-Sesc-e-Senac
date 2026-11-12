<?php
$pagina = $_GET['pagina'] ?? 'academia';

$menu = [
    'academia' => 'Academia',
    'aulas' => 'Aulas',
    'eventos' => 'Eventos',
    'loja' => 'Loja',
    'gourmet' => 'Espaço Gourmet'
];

$aulas = [
    ['nome' => 'Pilates', 'professor' => 'Ana Souza', 'horario' => 'Seg e Qui - 07:00', 'vagas' => 12],
    ['nome' => 'Funcional', 'professor' => 'Rafael Lima', 'horario' => 'Seg e Qua - 18:00', 'vagas' => 10],
    ['nome' => 'Spinning', 'professor' => 'Camila Santos', 'horario' => 'Ter e Sex - 19:00', 'vagas' => 8],
    ['nome' => 'Alongamento', 'professor' => 'Lucas Ferreira', 'horario' => 'Todos os dias - 06:30', 'vagas' => 15]
];

$eventos = [
    ['nome' => 'Torneio de Futsal', 'data' => '20 de Setembro', 'hora' => '14:00', 'icone' => '⚽'],
    ['nome' => 'Campeonato de Vôlei', 'data' => '27 de Setembro', 'hora' => '16:00', 'icone' => '🏐'],
    ['nome' => 'Jogo Amistoso de Basquete', 'data' => '28 de Setembro', 'hora' => '10:00', 'icone' => '🏀']
];

$produtos = [
    ['nome' => 'Camisa Dry Fit', 'preco' => '89,90', 'icone' => '👕'],
    ['nome' => 'Shorts de Treino', 'preco' => ',90', 'icone' => '👖'],
    ['nome' => 'Chuteira Campo Sintético', 'preco' => '120,00', 'icone' => '👟'],
    ['nome' => 'Chuteira de Futsal', 'preco' => '50,00', 'icone' => '👟'],
    ['nome' => 'Tênis de Esteira', 'preco' => '100,00', 'icone' => '👟'],
    ['nome' => 'Bola de Futebol', 'preco' => '50,00', 'icone' => '⚽'],
    ['nome' => 'Bola de Vôlei', 'preco' => '50,00', 'icone' => '🏐'],
    ['nome' => 'Bola de Basquete', 'preco' => '50,00', 'icone' => '🏀']
];

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pagina === 'gourmet') {
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    if ($data && $horario) {
        $mensagem = "Solicitação de reserva para $data às $horario recebida!";
    } else {
        $mensagem = 'Preencha a data e o horário.';
    }
}

function h($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportPlex</title>
    <link rel="stylesheet" href="paginainicial.css">
</head>

<body>

<header class="topo">
    <a href="" class="logo">SP <span>SPORTPLEX</span></a>

    <nav>
        <?php foreach ($menu as $link => $nome): ?>
            <a href="?pagina=<?= h($link) ?>"
               class="<?= $pagina === $link ? 'ativo' : '' ?>">
                <?= h($nome) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</header>

<main class="container">

<?php if ($pagina === 'academia'): ?>

    <h1>Academia</h1>
    <p class="subtitulo">Conheça nossa estrutura e equipamentos.</p>

    <section class="banner academia">
        <div>
            <h2>Treine na SportPlex</h2>
            <p>Uma estrutura completa para você alcançar seus objetivos.</p>
        </div>
    </section>

    <section class="indicadores">
        <div class="indicador">
            <h2>+50</h2>
            <p>Equipamentos</p>
        </div>

        <div class="indicador">
            <h2>35%</h2>
            <p>Ocupação</p>
        </div>

        <div class="indicador">
            <h2>Premium</h2>
            <p>Plano</p>
        </div>
    </section>

    <h2 class="titulo-secao">Equipamentos em destaque na SportPlex</h2>

    <section class="grade">
        <article class="card">
            <div class="foto musculacao"></div>
            <h3>Musculação</h3>
        </article>

        <article class="card">
            <div class="foto cardio"></div>
            <h3>Cardio</h3>
        </article>

        <article class="card">
            <div class="foto funcional"></div>
            <h3>Funcional</h3>
        </article>
    </section>

    <a href="?pagina=aulas" class="botao">Conheça as aulas</a>

<?php elseif ($pagina === 'aulas'): ?>

    <h1>Aulas</h1>
    <p class="subtitulo">Escolha sua atividade favorita para treinar.</p>

    <section class="lista">

        <?php foreach ($aulas as $aula): ?>
            <article class="card-aula">
                <div class="icone-aula">🏋️</div>

                <div class="info">
                    <h3><?= h($aula['nome']) ?></h3>
                    <p>Prof. <?= h($aula['professor']) ?></p>
                    <p><?= h($aula['horario']) ?></p>
                    <small><?= h($aula['vagas']) ?> Vagas disponíveis</small>
                </div>

                <a href="?pagina=aulas" class="botao pequeno">
                    Participar
                </a>
            </article>
        <?php endforeach; ?>

    </section>

<?php elseif ($pagina === 'eventos'): ?>

    <h1>Eventos e Campeonatos</h1>
    <p class="subtitulo">Confira os próximos eventos da SportPlex.</p>

    <section class="lista">

        <?php foreach ($eventos as $evento): ?>
            <article class="card-evento">
                <div class="icone-evento">
                    <?= h($evento['icone']) ?>
                </div>

                <div class="info">
                    <h3><?= h($evento['nome']) ?></h3>
                    <p><?= h($evento['data']) ?> às <?= h($evento['hora']) ?></p>
                    <small>Inscrições abertas todos os dias</small>
                </div>
            </article>
        <?php endforeach; ?>

    </section>

    <button class="botao" onclick="alert('Inscrição solicitada!')">
        Clique aqui para se inscrever no próximo evento
    </button>

<?php elseif ($pagina === 'loja'): ?>

    <h1>Loja SportPlex</h1>
    <p class="subtitulo">Encontre vários produtos para seus treinos diários.</p>

    <input type="search" id="busca" placeholder="Buscar produtos..." class="pesquisa">

    <section class="produtos">

        <?php foreach ($produtos as $produto): ?>
            <article class="card-produto">
                <div class="produto-icone">
                    <?= h($produto['icone']) ?>
                </div>

                <h3><?= h($produto['nome']) ?></h3>

                <p class="preco">R$ <?= h($produto['preco']) ?></p>

                <button class="botao"
                    onclick="alert('Produto adicionado com sucesso ao carrinho!')">
                    Comprar
                </button>
            </article>
        <?php endforeach; ?>

    </section>

<?php elseif ($pagina === 'gourmet'): ?>

    <h1>Espaço Gourmet</h1>
    <p class="subtitulo">Um ambiente especial para reunir os amigos enquanto vocês almoçam ou jantam.</p>

    <section class="banner gourmet">
        <div>
            <h2>Seu momento de lazer</h2>
            <p>Conforto e qualidade em um só lugar.</p>
        </div>
    </section>

    <h2 class="titulo-secao">Faça sua reserva</h2>

    <nav>

    <a href="login.php">Login</a>
    <a href="cadastro.php">Cadastro</a>
    <a href="reservar.php">Reservar Quadra</a>
    <a href="logout.php">Sair</a>

</nav>


    <form method="POST" class="formulario">

        <label>Selecione a dia e o mês</label>
        <input type="date" name="data" required>

        <label>Selecione o horário para o seu almoço ou jantar</label>
        <select name="horario" required>
            <option value="">Escolha um horário</option>
            <option value="12:00">12:00 - 15:00</option>
            <option value="15:00">15:00 - 18:00</option>
            <option value="19:00">19:00 - 22:00</option>
        </select>

        <h2>R$ 100,00</h2>

        <button type="submit" class="botao">
            Reservar Espaço Gourmet
        </button>

        <?php if ($mensagem): ?>
            <p class="mensagem"><?= h($mensagem) ?></p>
        <?php endif; ?>

    </form>

<?php else: ?>

    <h1>Página não encontrada</h1>
    <a href="?pagina=academia" class="botao">Voltar ao início</a>

<?php endif; ?>

</main>

<footer>
    <p> 2026 SportPlex | Site feito pelo: Igor, Ronny, Matheus Miranda, Gabriel Brey, Bernardo, Vitoria, Luis Henrique e Murilo Schimidt.
    </p>
</footer>

</body>
</html>
