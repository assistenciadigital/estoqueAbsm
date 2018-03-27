<?php
    $pdo = new PDO("mysql:host=localhost; dbname=estoque; charset=utf8;", "root", "root");
    $dados = $pdo->prepare("SELECT usuario_login as usuariologin, usuario_nome as usuarionome FROM usuario");
    $dados->execute();
    echo json_encode($dados->fetchAll(PDO::FETCH_ASSOC));
?>