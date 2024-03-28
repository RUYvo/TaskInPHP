<?php
require_once 'conexao.php'; // Inclui o arquivo conexao.php que contém a classe Conexao
require_once 'db.service.php'; // Inclui o arquivo db.service.php que contém a classe DbService

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['arquivar_concluidas'])) {
    $conexao = new Conexao();
    $dbService = new DbService($conexao);
    $isChecked = $_POST['arquivar_concluidas'] === 'true';
    if ($isChecked) {
        $query = "UPDATE tb_tarefas SET arquivada = 1 WHERE id_status = 2";
    } else {
        $query = "UPDATE tb_tarefas SET arquivada = 0 WHERE arquivada = 1";
    }
    $conexao = $conexao->conectar();
    $stmt = $conexao->prepare($query);
    $result = $stmt->execute();
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'error' => $stmt->errorInfo()));
    }
}
?>
