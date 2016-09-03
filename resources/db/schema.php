<?php

$schema = new \Doctrine\DBAL\Schema\Schema();

$aluno = $schema->createTable('aluno');
$aluno->addColumn('id_aluno', 'integer', array('unsigned' => true, 'autoincrement' => true));
$aluno->addColumn('matricula', 'integer', array('unsigned' => true));
$aluno->addColumn('nome', 'string', array('length' => 60));
$aluno->addColumn('tag', 'string', array('length' => 30));
$aluno->setPrimaryKey(array('id_aluno'));

$disciplina = $schema->createTable('disciplina');
$disciplina->addColumn('id_disciplina', 'integer', array('unsigned' => true, 'autoincrement' => true));
$disciplina->addColumn('nome', 'string', array('length' => 60));
$disciplina->addColumn('semestre', 'integer', array('unsigned' => true));
$disciplina->setPrimaryKey(array('id_disciplina'));

$reg_presenca = $schema->createTable('registro_presenca');
$reg_presenca->addColumn('id_registro_presenca', 'integer', array('unsigned' => true, 'autoincrement' => true));
$reg_presenca->addColumn('data_aula', 'datetime');
$reg_presenca->addColumn('faltas', 'integer', array('unsigned' => true));
$reg_presenca->addColumn('id_aluno', 'integer', array('unsigned' => true));
$reg_presenca->addColumn('id_disciplina', 'integer', array('unsigned' => true));
$reg_presenca->addUniqueIndex(array('id_disciplina','id_aluno','data_aula'));
$reg_presenca->setPrimaryKey(array('id_registro_presenca'));

return $schema;
