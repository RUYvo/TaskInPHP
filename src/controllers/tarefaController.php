<?php
require($_SERVER["DOCUMENT_ROOT"] . '/taskInPhp/src/models/tarefaModel.php');
require($_SERVER["DOCUMENT_ROOT"] . '/taskInPhp/src/models/conexao.php');
require($_SERVER["DOCUMENT_ROOT"] . '/taskInPhp/src/services/tarefaService.php');

$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

if ($acao == 'inserir') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();
    $tarefa->__set('tarefa', $_POST['tarefa']);

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->inserir();

    header('Location: ../views/todasTarefas.php');
} else if ($acao == 'recuperar') {
    $tarefa = new Tarefa();
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperar();
} else if ($acao == 'atualizar') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_POST['id'])
        ->__set('tarefa', $_POST['tarefa']);

    $conexao = new Conexao();
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->atualizar();
} else if ($acao == 'remover') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_POST['id']);

    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->remover();
} else if ($acao == 'recuperarTarefasPendentes') {
    $tarefa = new Tarefa();
    $tarefa->__set('id_status', 1);
    $conexao = new Conexao();

    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefas = $tarefaService->recuperarTarefasPendentes();

} else if ($acao == 'marcarRealizada') {
    $tarefa = new Tarefa();
    $tarefa->__set('id', $_GET["id"]->__set("id_status", 1));

    $conexao = new Conexao();
    
    $tarefaService = new TarefaService($conexao, $tarefa);
    $tarefaService->marcarRealizada();
}