<?php


//CRUD
class TarefaService
{

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao, Tarefa $tarefa)
	{
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}

	public function inserir()
	{ //create
		$query = "INSERT INTO tb_tarefas (tarefa, prazo, id_categoria) VALUES (:tarefa, :prazo, (SELECT id FROM categoria WHERE categoria = :categoria))";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
		$stmt->bindValue(':prazo', $this->tarefa->__get('prazo'));
		$stmt->bindValue(':categoria', $this->tarefa->__get('categoria'));
		$stmt->execute();
	}

	public function recuperar()
	{ //read
		$query = '
			select 
				t.id, s.status, t.tarefa, t.prazo
			from 
				tb_tarefas as t
				left join tb_status as s on (t.id_status = s.id) WHERE t.arquivada != 1
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function atualizar()
	{ //update

		$query = "update tb_tarefas set tarefa = ? where id = ?";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(1, $this->tarefa->__get('tarefa'));
		$stmt->bindValue(2, $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	public function remover()
	{ //delete

		$query = 'delete from tb_tarefas where id = :id';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id', $this->tarefa->__get('id'));
		$stmt->execute();
	}

	public function marcarRealizada()
	{ //update

		$query = "update tb_tarefas set id_status = ? where id = ?";
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(1, $this->tarefa->__get('id_status'));
		$stmt->bindValue(2, $this->tarefa->__get('id'));
		return $stmt->execute();
	}

	public function recuperarTarefasPendentes()
	{
		$query = '
			select 
				t.id, s.status, t.tarefa, t.prazo
			from 
				tb_tarefas as t
				left join tb_status as s on (t.id_status = s.id)
			where
				t.id_status = :id_status
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':id_status', $this->tarefa->__get('id_status'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function ordenarTarefas()
	{
		$orderBy = $_GET["atribute"];
		$query = "SELECT t.id, s.status, t.tarefa, t.prazo FROM tb_tarefas as t LEFT JOIN tb_status as s ON (t.id_status = s.id) ORDER BY $orderBy";
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function filtrarTarefas(){
		$idStatus = $_GET["status"];
		$query = "SELECT t.id, s.status, t.tarefa, t.prazo FROM tb_tarefas as t LEFT JOIN tb_status as s ON (t.id_status = s.id) WHERE t.id_status = $idStatus";
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function filtrarPorCategoria($categoria)
    {
		$query = "SELECT t.id, s.status, t.tarefa, t.prazo FROM tb_tarefas as t LEFT JOIN tb_status as s ON (t.id_status = s.id) WHERE id_categoria = (SELECT id FROM categoria WHERE categoria = ?)";
		$stmt = $this->conexao->prepare($query);
		$stmt->execute([$categoria]);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}