<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['arquivar_concluidas'])) {
    require_once 'db.service.php';
    $conexao = new Conexao();
    $query = "UPDATE tb_tarefas SET arquivado = 1 WHERE id_status = 1";
    $conexao = $conexao->conectar();
    $stmt = $conexao->prepare($query);
    $result = $stmt->execute();
    echo json_encode($result);
}