<?php

include 'sql.php';

$methodToCall = $_POST['methodToCall'];
$dataset = $_POST['dataset'];

if($methodToCall == 'loadSelects'){
	$aluno = DB::get_rows(DB::query('SELECT id_aluno, nome from aluno'));
	$selects = array('aluno' => $aluno);

	echo json_encode($selects);
}

if($methodToCall == 'pesquisa'){
	$id_aluno = $dataset['id_aluno'];
	$data_ini = $dataset['data_ini'];
	$data_fim = $dataset['data_fim'];

	$SQL = "SELECT a.nome as aluno , p.nome as plano FROM aluno as a 
			join plano as p on a.id_plano_fk = p.id_plano
			where a.id_aluno = $id_aluno";
	$aluno = DB::get_rows(DB::query($SQL));

    $exp_data = explode('/', $data_ini);
    $data_ini = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$exp_data = explode('/', $data_fim);
    $data_fim = $exp_data[2]."-".$exp_data[1]."-".$exp_data[0];

	$SQL = "SELECT count(*) as num_presencas FROM aluno as a
			join alunos_aula as aa on a.id_aluno = aa.id_aluno_fk
			join aula as au on aa.id_aula_fk = au.id_aula
			WHERE a.id_aluno = $id_aluno AND au.data BETWEEN '$data_ini' AND '$data_fim'";

	$presencas = DB::get_rows(DB::query($SQL));

	$result = ["aluno" => $aluno, "presencas" => $presencas];
	echo json_encode($result);
}
?>