<?php
    $pdo = new PDO("mysql:host=localhost; dbname=estoque; charset=utf8;", "root", "root");
    $dados = $pdo->prepare("SELECT * FROM status");
    $dados->execute();
    echo json_encode($dados->fetchAll(PDO::FETCH_ASSOC));
?>