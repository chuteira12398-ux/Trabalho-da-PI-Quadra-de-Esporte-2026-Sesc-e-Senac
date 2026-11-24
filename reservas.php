
<?php
session_start();

$erro = "";

$precos = [
    "Futsal" => 80,
    "Vôlei" => 60,
    "Basquete" => 70,
    "Society" => 100
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $quadra = $_POST["quadra"] ?? "";
    $data = $_POST["data"] ?? "";
    $horario = $_POST["horario"] ?? "";
    $duracao = (int)($_POST["duracao"] ?? 1);
    $participantes = (int)($_POST["participantes"] ?? 0);
    $itens = $_POST["equipamentos"] ?? [];

    if (!isset($precos[$quadra])) {
        $erro = "Escolha uma quadra válida.";

    } elseif (!in_array($duracao, [1, 2, 3])) {
        $erro = "Escolha uma duração válida.";

    } elseif ($participantes < 2 || $participantes > 30) {
        $erro = "Informe entre 2 e 30 participantes.";

    } elseif (!$data || !$horario) {
        $erro = "Informe a data e o horário.";

    } else {

        $inicio = DateTime::createFromFormat(
            "Y-m-d H:i",
            $data . " " . $horario
        );

        if (!$inicio || $inicio <= new DateTime()) {
            $erro = "Escolha uma data e horário futuros.";

        } else {

            $fim = clone $inicio;
            $fim->modify("+$duracao hours");

            $permitidos = ["Bolas", "Chuteiras", "Coletes"];
            $selecionados = [];

            foreach ($itens as $item) {
                if (in_array($item, $permitidos)) {
                    $selecionados[] = $item;
                }
            }

            $valor = $precos[$quadra] * $duracao;

            $_SESSION["reserva_pendente"] = [
                "quadra" => $quadra,
                "data" => $inicio->format("d/m/Y"),
                "inicio" => $inicio->format("H:i"),
                "fim" => $fim->format("H:i"),
                "participantes" => $participantes,
                "equipamentos" => implode(", ", $selecionados),
                "valor" => $valor
            ];

            header("Location: pagamento_reserva.php");
            exit;
        }
    }
}

$hoje = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservar Quadra - SportPlex</title>

    <link rel="stylesheet" href="reservas.css">
</head>

<body>

<div class="container">

    <h1>Reserve sua quadra</h1>

    <p class="subtitulo">
        Escolha o esporte, convide seus amigos e marque sua partida!
    </p>

    <?php if ($erro != ""): ?>
        <p class="erro">
            <?= htmlspecialchars($erro) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <h2>Escolha sua quadra</h2>

        <div class="quadras">

            <label class="card-quadra">
                <input type="radio" name="quadra" value="Futsal" required>
                <span class="icone">⚽</span>
                <strong>Quadra de Futsal</strong>
                <span>R$ 80,00 por hora</span>
                <small>Bolas, chuteiras e coletes disponíveis</small>
            </label>

            <label class="card-quadra">
                <input type="radio" name="quadra" value="Vôlei" required>
                <span class="icone">🏐</span>
                <strong>Quadra de Vôlei</strong>
                <span>R$ 60,00 por hora</span>
                <small>Bolas e rede disponíveis</small>
            </label>

            <label class="card-quadra">
                <input type="radio" name="quadra" value="Basquete" required>
                <span class="icone">🏀</span>
                <strong>Quadra de Basquete</strong>
                <span>R$ 70,00 por hora</span>
                <small>Bolas disponíveis</small>
            </label>

            <label class="card-quadra">
                <input type="radio" name="quadra" value="Society" required>
                <span class="icone">🥅</span>
                <strong>Campo Society</strong>
                <span>R$ 100,00 por hora</span>
                <small>Bolas, chuteiras e coletes disponíveis</small>
            </label>

        </div>

        <h2>Data e horário</h2>

        <div class="campos">

            <div>
                <label>Data da reserva</label>
                <input type="date" name="data"
                    min="<?= $hoje ?>" required>
            </div>

            <div>
                <label>Horário de início</label>
                <select name="horario" required>
                    <option value="">Escolha um horário</option>

                    <?php for ($h = 7; $h <= 21; $h++): ?>
                        <option value="<?= sprintf("%02d:00", $h) ?>">
                            <?= sprintf("%02d:00", $h) ?>
                        </option>
                    <?php endfor; ?>

                </select>
            </div>

            <div>
                <label>Duração</label>
                <select name="duracao" required>
                    <option value="1">1 hora</option>
                    <option value="2">2 horas</option>
                    <option value="3">3 horas</option>
                </select>
            </div>

            <div>
                <label>Quantidade de jogadores</label>
                <input type="number" name="participantes"
                    min="2" max="30" value="10" required>
            </div>

        </div>

        <h2>Equipamentos</h2>

        <div class="opcoes">

            <label>
                <input type="checkbox" name="equipamentos[]" value="Bolas">
                Bolas
            </label>

            <label>
                <input type="checkbox" name="equipamentos[]" value="Chuteiras">
                Chuteiras
            </label>

            <label>
                <input type="checkbox" name="equipamentos[]" value="Coletes">
                Coletes
            </label>

        </div>

        <p class="aviso">
            Valores demonstrativos para o projeto SportPlex.
        </p>

        <button type="submit">
            Continuar para pagamento
        </button>

    </form>

    <a href="pagina.php?pagina=eventos">
        Voltar para os eventos
    </a>

</div>

</body>
</html>
