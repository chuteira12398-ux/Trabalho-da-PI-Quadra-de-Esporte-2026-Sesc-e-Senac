
<?php

session_start();

$host = "127.0.0.1";
$banco = "sportplex";
$usuario = "root";
$senha = "root";

try {

    $pdo = new PDO(
        "mysql:host=$host;port=3306;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

} catch (PDOException $e) {

    die("Não foi possível conectar ao MySQL: " . $e->getMessage());

}
