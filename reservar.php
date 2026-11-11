
<?php
require_once "includes/proteger.php";
require_once "config/conexao.php";

$mensagem = "";

$quadras = [
    "Basquete",
    "Futebol",
    "Vôlei",
    "Beach Tênis"
];

$horarios = [
    "07:00",
    "08:00",
    "09:00",
    "10:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00",
    "18:00",
    "19:00"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $quadra = $_POST["quadra"] ?? "";
    $data = $_POST["data"] ?? "";
    $horario = $_POST["horario"] ?? "";

    $dataValida = DateTime::createFromFormat("!Y-m-d", $data);

    if (
        !in_array($quadra, $quadras, true) ||
        !in_array($horario, $horarios, true) ||
        !$dataValida ||
        $dataValida->format("Y-m-d") !== $data ||
        $data <= date("Y-m-d")
    ) {

        $mensagem = "Verifique os dados da reserva.";

    } else {

        try {

            $sql = "INSERT INTO reservas
                    (usuario_id, quadra, data_reserva, horario)
                    VALUES (:usuario, :quadra, :data, :horario)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":usuario" => $_SESSION["usuario_id"],
                ":quadra" => $quadra,
                ":data" => $data,
                ":horario" => $horario
            ]);

            $mensagem = "Reserva realizada com sucesso!";

        } catch (PDOException $erro) {

            if ($erro->getCode() === "23000") {
                $mensagem = "Esse horário já está reservado!";
            } else {
                $mensagem = "Erro ao realizar reserva.";
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Reserva de Quadras SportPlex</title>
</head>
<body>

<h1>Locação de Quadras</h1>

<p>
    Olá, <?= htmlspecialchars($_SESSION["usuario_nome"]) ?>!
</p>

<form method="POST">

    <label>Escolha a quadra:</label>

    <select name="quadra" required>
        <option value="">Selecione</option>

        <?php foreach ($quadras as $quadra): ?>
            <option value="<?= htmlspecialchars($quadra) ?>">
                <?= htmlspecialchars($quadra) ?>
            </option>
        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Data da reserva:</label>

    <input type="date" name="data"
           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
           required>

    <br><br>

    <label>Horário:</label>

    <select name="horario" required>
        <option value="">Selecione</option>

        <?php foreach ($horarios as $horario): ?>
            <option value="<?= $horario ?>">
                <?= $horario ?>
            </option>
        <?php endforeach; ?>

    </select>

    <br><br>

    <button type="submit">Reservar Agora</button>

</form>

<p><?= htmlspecialchars($mensagem) ?></p>

<a href="logout.php">Sair da conta</a>

</body>
</html>
