
<?php
session_start();

// Fazer uma nova reserva
if (isset($_GET["nova"])) {
    unset($_SESSION["gourmet_confirmado"]);
    unset($_SESSION["gourmet_reserva"]);
    header("Location: reserva_gourmet.php");
    exit;
}

$erro = "";
$etapa = "reserva";

$comidas = [
    "Churrasco",
    "Comida brasileira",
    "Massas",
    "Pizza",
    "Hambúrguer",
    "Comida japonesa",
    "Saladas",
    "Sobremesas"
];

// Preço de cada comida
$precosComidas = [
    "Churrasco" => 50.00,
    "Comida brasileira" => 40.00,
    "Massas" => 35.00,
    "Pizza" => 30.00,
    "Hambúrguer" => 25.00,
    "Comida japonesa" => 45.00,
    "Saladas" => 20.00,
    "Sobremesas" => 15.00
];

// Valores para manter a seleção ao voltar
$dataSelecionada = "";
$horarioSelecionado = "";
$comidasSelecionadas = [];

if (isset($_SESSION["gourmet_confirmado"])) {
    $etapa = "confirmado";
} elseif (isset($_SESSION["gourmet_reserva"])) {
    $etapa = "pagamento";
}

// Processamento dos formulários
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    // Voltar para alterar as comidas
    if ($acao === "editar") {

        if (isset($_SESSION["gourmet_reserva"])) {

            $dataSelecionada = $_SESSION["gourmet_reserva"]["data"];
            $horarioSelecionado = $_SESSION["gourmet_reserva"]["horario"];
            $comidasSelecionadas = $_SESSION["gourmet_reserva"]["comidas"];

            unset($_SESSION["gourmet_reserva"]);
        }

        $etapa = "reserva";
    }

    // Realizar reserva
    elseif ($acao === "reservar") {

        $data = $_POST["data"] ?? "";
        $horario = $_POST["horario"] ?? "";
        $escolhidas = $_POST["comidas"] ?? [];

        $dataSelecionada = $data;
        $horarioSelecionado = $horario;

        $comidasSelecionadas = is_array($escolhidas)
            ? array_values(array_intersect($comidas, $escolhidas))
            : [];

        $horarios = ["12:00", "15:00", "19:00"];

        if (!$data || $data < date("Y-m-d")) {

            $erro = "Escolha uma data válida.";

        } elseif (!in_array($horario, $horarios, true)) {

            $erro = "Escolha um horário válido.";

        } elseif (empty($comidasSelecionadas)) {

            $erro = "Escolha pelo menos um tipo de comida.";

        } else {

            // Somar somente as comidas escolhidas
            $valor = 0;

            foreach ($comidasSelecionadas as $comida) {
                $valor += $precosComidas[$comida];
            }

            $_SESSION["gourmet_reserva"] = [
                "data" => $data,
                "horario" => $horario,
                "comidas" => $comidasSelecionadas,
                "valor" => $valor
            ];

            unset($_SESSION["gourmet_confirmado"]);

            $etapa = "pagamento";
        }
    }

    // Confirmar pagamento demonstrativo
    elseif ($acao === "pagar") {

        $forma = $_POST["pagamento"] ?? "";

        $permitidos = ["PIX", "Crédito", "Débito"];

        if (!isset($_SESSION["gourmet_reserva"])) {

            $erro = "Faça uma reserva primeiro.";
            $etapa = "reserva";

        } elseif (!in_array($forma, $permitidos, true)) {

            $erro = "Escolha uma forma de pagamento.";
            $etapa = "pagamento";

        } else {

            $_SESSION["gourmet_confirmado"] =
                $_SESSION["gourmet_reserva"];

            $_SESSION["gourmet_confirmado"]["pagamento"] = $forma;

            unset($_SESSION["gourmet_reserva"]);

            $etapa = "confirmado";
        }
    }
}

function h($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Espaço Gourmet | SportPlex</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f3f6fc;
    color: #172b4d;
}

.topo {
    background: #303f98;
    color: white;
    padding: 22px 7%;
}

.logo {
    font-size: 25px;
    font-weight: bold;
}

.container {
    width: 90%;
    max-width: 850px;
    margin: 40px auto;
}

h1, h2 {
    color: #303f98;
}

.subtitulo {
    color: #64748b;
    line-height: 1.6;
}

.painel {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 5px 22px rgba(0,0,0,.07);
    margin-top: 25px;
}

.campo {
    margin-bottom: 22px;
}

.campo label {
    display: block;
    font-weight: bold;
    margin-bottom: 9px;
}

input[type="date"], select {
    width: 100%;
    padding: 15px;
    border: 1px solid #d5dbea;
    border-radius: 9px;
    font-size: 16px;
    background: white;
}

.grade-comidas {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 15px;
}

.comida {
    border: 1px solid #d5dbea;
    padding: 17px;
    border-radius: 10px;
    cursor: pointer;
    background: #fff;
}

.comida:hover {
    border-color: #303f98;
    background: #f5f6ff;
}

.comida input {
    margin-right: 8px;
}

.comida strong {
    display: inline-block;
    color: #303f98;
    margin-top: 8px;
}

.preco {
    color: #303f98;
    font-size: 28px;
    font-weight: bold;
    margin: 25px 0;
}

.botao {
    display: block;
    width: 100%;
    padding: 16px;
    background: #303f98;
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    margin-top: 15px;
}

.botao:hover {
    background: #202e78;
}

.resumo {
    background: #f3f6fc;
    padding: 20px;
    border-radius: 12px;
    line-height: 1.8;
    margin: 20px 0;
}

.opcao {
    display: block;
    padding: 17px;
    border: 1px solid #d5dbea;
    border-radius: 10px;
    margin: 12px 0;
    cursor: pointer;
}

.mensagem {
    background: #dcfce7;
    color: #166534;
    padding: 18px;
    border-radius: 10px;
    margin: 20px 0;
}

.erro {
    background: #fee2e2;
    color: #991b1b;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.voltar {
    display: inline-block;
    margin-top: 20px;
    color: #303f98;
    font-weight: bold;
    text-decoration: none;
}

.botao-inicio {
    display: block;
    width: 100%;
    max-width: 850px;
    margin: 25px auto 35px;
    padding: 16px;
    background: #303f98;
    color: white;
    border-radius: 30px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
}

.botao-inicio:hover {
    background: #202e78;
}

@media(max-width: 600px) {

    .grade-comidas {
        grid-template-columns: 1fr;
    }

    .painel {
        padding: 20px;
    }

}

</style>

</head>

<body>

<header class="topo">
    <div class="logo">SPORTPLEX</div>
</header>

<main class="container">

<?php if ($etapa === "reserva"): ?>

    <h1>Espaço Gourmet</h1>

    <p class="subtitulo">
        Escolha suas comidas favoritas e reserve seu momento especial.
    </p>

    <section class="painel">

        <h2>Faça sua reserva</h2>

        <?php if ($erro): ?>
            <p class="erro"><?= h($erro) ?></p>
        <?php endif; ?>

        <form method="POST">

            <input type="hidden" name="acao" value="reservar">

            <div class="campo">

                <label>Escolha a data</label>

                <input
                    type="date"
                    name="data"
                    min="<?= date("Y-m-d") ?>"
                    value="<?= h($dataSelecionada) ?>"
                    required
                >

            </div>

            <div class="campo">

                <label>Escolha o horário</label>

                <select name="horario" required>

                    <option value="">Escolha um horário</option>

                    <option value="12:00"
                        <?= $horarioSelecionado === "12:00" ? "selected" : "" ?>>
                        12:00 - 15:00 | Almoço
                    </option>

                    <option value="15:00"
                        <?= $horarioSelecionado === "15:00" ? "selected" : "" ?>>
                        15:00 - 18:00 | Tarde
                    </option>

                    <option value="19:00"
                        <?= $horarioSelecionado === "19:00" ? "selected" : "" ?>>
                        19:00 - 22:00 | Jantar
                    </option>

                </select>

            </div>

            <h2>Tipos de comida disponíveis</h2>

            <p class="subtitulo">
                Selecione as comidas desejadas e veja o preço aumentar.
            </p>

            <div class="grade-comidas">

                <?php foreach ($comidas as $comida): ?>

                    <label class="comida">

                        <input
                            type="checkbox"
                            name="comidas[]"
                            value="<?= h($comida) ?>"
                            data-preco="<?= $precosComidas[$comida] ?>"
                            <?= in_array($comida, $comidasSelecionadas, true) ? "checked" : "" ?>
                        >

                        <?= h($comida) ?>

                        <br>

                        <strong>
                            + R$ <?= number_format(
                                $precosComidas[$comida],
                                2,
                                ",",
                                "."
                            ) ?>
                        </strong>

                    </label>

                <?php endforeach; ?>

            </div>

            <div class="preco" id="precoTotal">
                R$ 0,00
            </div>

            <button type="submit" class="botao">
                Continuar para pagamento
            </button>

        </form>

    </section>

<?php elseif ($etapa === "pagamento"): ?>

    <h1>Pagamento da reserva</h1>

    <p class="subtitulo">
        Confira seus dados e escolha como deseja pagar.
    </p>

    <section class="painel">

        <div class="resumo">

            <h2>Resumo do Espaço Gourmet</h2>

            <p>
                <strong>Data:</strong>
                <?= h(date(
                    "d/m/Y",
                    strtotime($_SESSION["gourmet_reserva"]["data"])
                )) ?>
            </p>

            <p>
                <strong>Horário:</strong>
                <?= h($_SESSION["gourmet_reserva"]["horario"]) ?>
            </p>

            <p><strong>Comidas escolhidas:</strong></p>

            <ul>

                <?php foreach ($_SESSION["gourmet_reserva"]["comidas"] as $comida): ?>

                    <li><?= h($comida) ?></li>

                <?php endforeach; ?>

            </ul>

            <div class="preco">

                R$ <?= number_format(
                    $_SESSION["gourmet_reserva"]["valor"],
                    2,
                    ",",
                    "."
                ) ?>

            </div>

        </div>

        <?php if ($erro): ?>
            <p class="erro"><?= h($erro) ?></p>
        <?php endif; ?>

        <form method="POST">

            <input type="hidden" name="acao" value="pagar">

            <h2>Forma de pagamento</h2>

            <label class="opcao">
                <input type="radio" name="pagamento" value="PIX">
                PIX
            </label>

            <label class="opcao">
                <input type="radio" name="pagamento" value="Crédito">
                Cartão de crédito
            </label>

            <label class="opcao">
                <input type="radio" name="pagamento" value="Débito">
                Cartão de débito
            </label>

            <p class="subtitulo">
                Pagamento demonstrativo. Nenhuma cobrança será realizada.
            </p>

            <button type="submit" class="botao">
                Confirmar pagamento simulado
            </button>

        </form>

        <form method="POST">

            <input type="hidden" name="acao" value="editar">

            <button type="submit" class="botao">
                Voltar e alterar comidas
            </button>

        </form>

    </section>

<?php else: ?>

    <h1>Reserva confirmada!</h1>

    <section class="painel">

        <div class="mensagem">

            <h2>Seu agendamento foi realizado!</h2>

            <p>O pagamento foi apenas simulado.</p>

        </div>

        <p>
            <strong>Data:</strong>
            <?= h(date(
                "d/m/Y",
                strtotime($_SESSION["gourmet_confirmado"]["data"])
            )) ?>
        </p>

        <p>
            <strong>Horário:</strong>
            <?= h($_SESSION["gourmet_confirmado"]["horario"]) ?>
        </p>

        <p>
            <strong>Comidas escolhidas:</strong>
        </p>

        <ul>
            <?php foreach ($_SESSION["gourmet_confirmado"]["comidas"] as $comida): ?>
                <li><?= h($comida) ?></li>
            <?php endforeach; ?>
        </ul>

        <p>
            <strong>Pagamento:</strong>
            <?= h($_SESSION["gourmet_confirmado"]["pagamento"]) ?>
        </p>

        <p>
            <strong>Valor:</strong>

            R$ <?= number_format(
                $_SESSION["gourmet_confirmado"]["valor"],
                2,
                ",",
                "."
            ) ?>
        </p>

        <a href="reserva_gourmet.php?nova=1" class="botao">
            Fazer outra reserva
        </a>

    </section>

<?php endif; ?>

<!-- Botão para voltar à página inicial -->

<a href="paginainicial.php" class="botao-inicio">
    Voltar para a página inicial
</a>

</main>

<script>

const comidas = document.querySelectorAll('input[name="comidas[]"]');
const precoTotal = document.getElementById("precoTotal");

function atualizarPreco() {

    let total = 0;

    comidas.forEach(function(comida) {

        if (comida.checked) {
            total += Number(comida.dataset.preco);
        }

    });

    precoTotal.textContent =
        "R$ " + total.toFixed(2).replace(".", ",");

}

comidas.forEach(function(comida) {
    comida.addEventListener("change", atualizarPreco);
});

// Atualizar o preço ao abrir a seleção
atualizarPreco();

</script>

</body>
</html>
