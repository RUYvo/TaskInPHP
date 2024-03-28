<?php
$acao = 'recuperar';
require_once 'tarefa_controller.php';
require_once 'conexao.php';
require_once 'db.service.php';
$conexao = new Conexao();
$dbService = new DbService($conexao);
$categorias = $dbService->pegarCategorias();
?>

<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>App Lista Tarefas</title>

	<link rel="stylesheet" href="css/estilo.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
		integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<script>
		function editar(id, txt_tarefa) {

			//criar um form de edição
			let form = document.createElement('form')
			form.action = 'tarefa_controller.php?acao=atualizar'
			form.method = 'post'
			form.className = 'row'

			//criar um input para entrada do texto
			let inputTarefa = document.createElement('input')
			inputTarefa.type = 'text'
			inputTarefa.name = 'tarefa'
			inputTarefa.className = 'col-9 form-control'
			inputTarefa.value = txt_tarefa

			//criar um input hidden para guardar o id da tarefa
			let inputId = document.createElement('input')
			inputId.type = 'hidden'
			inputId.name = 'id'
			inputId.value = id

			//criar um button para envio do form
			let button = document.createElement('button')
			button.type = 'submit'
			button.className = 'col-3 btn btn-info'
			button.innerHTML = 'Atualizar'

			//incluir inputTarefa no form
			form.appendChild(inputTarefa)

			//incluir inputId no form
			form.appendChild(inputId)

			//incluir button no form
			form.appendChild(button)

			//teste
			//console.log(form)

			//selecionar a div tarefa
			let tarefa = document.getElementById('tarefa_' + id)

			//limpar o texto da tarefa para inclusão do form
			tarefa.innerHTML = ''

			//incluir form na página
			tarefa.insertBefore(form, tarefa[0])

		}

		function remover(id) {
			location.href = 'todas_tarefas.php?acao=remover&id=' + id;
		}

		function marcarRealizada(id) {
			location.href = 'todas_tarefas.php?acao=marcarRealizada&id=' + id;
		}

		function orderBy(atribute) {
			location.href = 'todas_tarefas.php?acao=ordenarTarefas&atribute=' + atribute;
			// location.href = 'index.php'
		}
		function filterBy(status) {
			location.href = 'todas_tarefas.php?acao=filtrarTarefas&status=' + status;
			// location.href = 'index.php'
		}
		function filterByCategory(category) {
			location.href = 'todas_tarefas.php?acao=filtrarPorCategoria&categoria=' + category;
		}

		function arquivarConcluidas() {
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						var response = JSON.parse(xhr.responseText);

						if (response.success) {
							console.log('Tarefas atualizadas com sucesso');
							location.reload();
						} else {
							console.error('Falha ao atualizar tarefas:', response.error);
						}
					} else {
						console.error('Erro ao processar a solicitação:', xhr.status);
					}
				}
			};
			var isChecked = document.getElementById('arquivarConcluidas').checked;
			xhr.open('POST', 'arquivar_concluidas.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.send('arquivar_concluidas=' + isChecked); 
		}


	</script>
</head>

<body>
	<nav class="navbar navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="#">
				<img src="img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
				App Lista Tarefas
			</a>
		</div>
	</nav>

	<div class="container app">
		<div class="row">
			<div class="col-sm-3 menu">
				<ul class="list-group">
					<li class="list-group-item"><a href="index.php">Tarefas pendentes</a></li>
					<li class="list-group-item"><a href="nova_tarefa.php">Nova tarefa</a></li>
					<li class="list-group-item active"><a href="#">Todas tarefas</a></li>
				</ul>
			</div>

			<div>
				<div>
					<h1>Ordenar Por:</h1>
					<a onclick="orderBy('id_status')">Status</a>
					<a onclick="orderBy('data_cadastrado')">Data de criação</a>
					<a onclick="orderBy('tarefa')">Nome</a>
				</div>
				<div>
					<h1>Filtrar Por:</h1>
					<a onclick="filterBy('1')">Pendentes</a>
					<a onclick="filterBy('2')">Concluidas</a>
					<a onclick="location.href = 'todas_tarefas.php?acao=recuperar'">Todas as tarefas</a>
					<div>
						<label> Categoria</label>
						<select id="cars" class="form-control" name="categoria_selecionada">
							<option hidden selected>Select one...</option>
							<?php foreach ($categorias as $categoria) { ?>
								<option value="<?php echo $categoria->categoria; ?>">
									<?php echo $categoria->categoria; ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div>
					<label for="arquivarConcluidas">Arquivar concluídas</label>
					<input type="checkbox" id="arquivarConcluidas" <?php if ($dbService->returnIfHaveArchived()) { ?> checked <?php } ?>
						onchange="arquivarConcluidas()">
				</div>
			</div>
			<div class="col-sm-9">
				<div class="container pagina">
					<div class="row">
						<div class="col">
							<h4>Todas tarefas</h4>
							<hr />
							<div id="tarefas">
								<?php foreach ($tarefas as $indice => $tarefa) { ?>
									<div class="row mb-3 d-flex align-items-center tarefa">
										<div class="col-sm-9" id="tarefa_<?= $tarefa->id ?>">
											<?= $tarefa->tarefa ?> (
											<?= $tarefa->status ?>)
											<?= $tarefa->prazo ?>
										</div>
										<div class="col-sm-3 mt-2 d-flex justify-content-between">
											<i class="fas fa-trash-alt fa-lg text-danger"
												onclick="remover(<?= $tarefa->id ?>)"></i>
											<?php if ($tarefa->status == 'pendente') { ?>
												<i class="fas fa-edit fa-lg text-info"
													onclick="editar(<?= $tarefa->id ?>, '<?= $tarefa->tarefa ?>')"></i>
												<i class="fas fa-check-square fa-lg text-success"
													onclick="marcarRealizada(<?= $tarefa->id ?>)"></i>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</body>
<script>
	var select = document.querySelector('select');
	select.addEventListener('change', function () {
		var selecionada = this.options[this.selectedIndex];;
		filterByCategory(selecionada.value)
		console.log(selecionada.value)
	});


	(function verificarStatus() {
		const lista = document.querySelectorAll('#tarefas .tarefa');
		let encontrado = false; // Flag para indicar se uma tarefa com prazo para hoje foi encontrada

		let i = 0;
		while (i < lista.length && !encontrado) {
			const prazo = new Date(lista[i].outerText.slice(-19, lista[i].outerText.length));
			const hoje = new Date();

			if (prazo.getDate() === hoje.getDate() && prazo.getMonth() === hoje.getMonth() && prazo.getFullYear() === hoje.getFullYear()) {
				encontrado = true; // Marca como encontrado
				alert("Você tem tarefas que estão para vencer hoje");
			}

			i++;
		}
	})();
</script>

</html>