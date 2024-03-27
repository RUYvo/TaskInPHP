<?php
class DbService
{

    private $conexao;

    public function __construct(Conexao $conexao)
    {
        $this->conexao = $conexao->conectar();
    }

    public function pegarCategorias()
    {
        $query = "SELECT id, categoria FROM categoria";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function returnIfHaveArchived(){
        $query = "SELECT * FROM tb_tarefas where arquivada = 1";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ) ? true : false; 
    }
}