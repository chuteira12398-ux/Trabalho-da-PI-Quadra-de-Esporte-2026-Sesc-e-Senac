
<?php
session_start();
date_default_timezone_set("America/Sao_Paulo");

$aulas = [
    "Pilates" => ["professor" => "Ana Souza", "preco" => 35],
    "Funcional" => ["professor" => "Rafael Lima", "preco" => 30],
    "Spinning" => ["professor" => "Camila Santos", "preco" => 30],
    "Alongamento" => ["professor" => "Lucas Ferreira", "preco" => 25]
];

$nomeAula = $_GET["aula"] ?? "";

if (!isset($aulas[$nomeAula])) {
    die("Aula não encontrada. Volte e escolha uma aula.");
}

$aula = $aulas[$nomeAula];
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = $_POST["data"] ?? "";
    $horario = $_POST["horario"] ?? "";
    $participantes = (int)($_POST["participantes"] ?? 0);

    $horarios = [
        "07:00", "08:00", "09:00",
        "10:00", "14:00", "15:00",
        "16:00", "18:00", "19:00"
    ];

    $dataHora = DateTime::createFromFormat(
        "Y-m-d H:i",
        $data . " " . $horario
    );

    if (!$dataHora || $dataHora <= new DateTime()) {
        $erro = "Escolha uma data e horário futuros.";

    } elseif (!in_array($horario, $horarios)) {
        $erro = "Escolha um horário válido.";

    } elseif ($participantes < 1 || $participantes > 5) {
        $erro = "Escolha entre 1 e 5 participantes.";

    } else {

        $_SESSION["inscricao_aula"] = [
            "aula" => $nomeAula,
            "professor" => $aula["professor"],
            "data" => $dataHora->format("d/m/Y"),
            "horario" => $horario,
            "participantes" => $participantes,
            "valor" => $aula["preco"] * $participantes
        ];

        header("Location: pagamento_aula.php");
        exit;
    }
}

$hoje = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar aula - SportPlex</title>
    <link rel="stylesheet" href="aulas.css">
</head>
<body>

<header class="topo">
    <h2>SP SPORTPLEX</h2>
    <a href="aulas.php">Voltar para aulas</a>
</header>

<main class="formulario">

    <h1>Agende sua aula</h1>

    <div class="resumo">
        <h2><?= htmlspecialchars($nomeAula) ?></h2>
        <p>Professor: <?= htmlspecialchars($aula["professor"]) ?></p>
        <p>Valor por participante: R$ <?= number_format($aula["preco"], 2, ",", ".") ?></p>
    </div>

    <?php if ($erro): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST" action="inscricao_aula.php?aula=<?= urlencode($nomeAula) ?>">

        <label>Escolha a data:</label>
        <input type="date" name="data" min="<?= $hoje ?>" required>

        <label>Escolha o horário:</label>
        <select name="horario" required>
            <option value="">Selecione</option>
            <option value="07:00">07:00</option>
            <option value="08:00">08:00</option>
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="18:00">18:00</option>
            <option value="19:00">19:00</option>
        </select>

        <label>Quantidade de participantes:</label>
        <input type="number" name="participantes"
            min="1" max="5" value="1" required>

        <button type="submit">Continuar para pagamento</button>

    </form>

</main>

</body>
</html>
