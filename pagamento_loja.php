
<?php
$nome = $_GET["nome"] ?? $_POST["nome"] ?? "";
$preco = $_GET["preco"] ?? $_POST["preco"] ?? "";

$mensagem = "";
$forma = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $forma = $_POST["forma_pagamento"] ?? "";

    if ($forma == "") {
        $mensagem = "Selecione uma forma de pagamento.";
    } else {
        $mensagem = "Compra simulada com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento da Loja - SportPlex</title>
    <link rel="stylesheet" href="pagamento_loja.css">
</head>
<body>

<div class="container-pagamento">

    <h1>Finalizar compra</h1>

    <div class="resumo">
        <h2>Resumo do pedido</h2>
        <p>Produto: <?= htmlspecialchars($nome) ?></p>
        <p>Preço: R$ <?= htmlspecialchars($preco) ?></p>
    </div>

    <form method="POST" action="pagamento_loja.php">

        <input type="hidden" name="nome" value="<?= htmlspecialchars($nome) ?>">
        <input type="hidden" name="preco" value="<?= htmlspecialchars($preco) ?>">

        <h2>Forma de pagamento</h2>

        <label>
            <input type="radio" name="forma_pagamento" value="PIX"
                <?= $forma == "PIX" ? "checked" : "" ?>
                onclick="mostrarPagamento('pix')">
            PIX
        </label>

        <label>
            <input type="radio" name="forma_pagamento" value="Cartão de crédito"
                <?= $forma == "Cartão de crédito" ? "checked" : "" ?>
                onclick="mostrarPagamento('credito')">
            Cartão de crédito
        </label>

        <label>
            <input type="radio" name="forma_pagamento" value="Cartão de débito"
                <?= $forma == "Cartão de débito" ? "checked" : "" ?>
                onclick="mostrarPagamento('debito')">
            Cartão de débito
        </label>

        <div id="pix" class="detalhes"
            style="display: <?= $forma == "PIX" ? "block" : "none" ?>;">
            <h3>Pagamento por PIX</h3>
            <p>QR Code ilustrativo para demonstração escolar.</p>
            <div class="qrcode">PIX<br>SPORTPLEX</div>
        </div>

        <div id="credito" class="detalhes"
            style="display: <?= $forma == "Cartão de crédito" ? "block" : "none" ?>;">
            <h3>Cartão de crédito</h3>
            <p>Campos fictícios para demonstração.</p>

            <label>Número fictício do cartão</label>
            <input type="text" placeholder="0000 0000 0000 0000"
                autocomplete="off">

            <label>Nome fictício do titular</label>
            <input type="text" placeholder="NOME DE EXEMPLO"
                autocomplete="off">

            <label>Validade fictícia</label>
            <input type="text" placeholder="MM/AA" autocomplete="off">

            <label>Código fictício</label>
            <input type="text" placeholder="000" autocomplete="off">
        </div>

        <div id="debito" class="detalhes"
            style="display: <?= $forma == "Cartão de débito" ? "block" : "none" ?>;">
            <h3>Cartão de débito</h3>
            <p>Campos fictícios para demonstração.</p>

            <label>Número fictício do cartão</label>
            <input type="text" placeholder="0000 0000 0000 0000"
                autocomplete="off">

            <label>Nome fictício do titular</label>
            <input type="text" placeholder="NOME DE EXEMPLO"
                autocomplete="off">

            <label>Validade fictícia</label>
            <input type="text" placeholder="MM/AA" autocomplete="off">

            <label>Código fictício</label>
            <input type="text" placeholder="000" autocomplete="off">
        </div>

        <button type="submit">Confirmar compra</button>

    </form>

    <?php if ($mensagem != ""): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <a href="pagina.php?pagina=loja">Voltar para a loja</a>

</div>

<script>
function mostrarPagamento(tipo) {
    document.getElementById("pix").style.display = "none";
    document.getElementById("credito").style.display = "none";
    document.getElementById("debito").style.display = "none";

    document.getElementById(tipo).style.display = "block";
}
</script>

</body>
</html>
