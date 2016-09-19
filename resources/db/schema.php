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
$disciplina->addColumn('diasemana', 'integer');
$disciplina->addColumn('data_ini', 'date');
$disciplina->addColumn('data_fim', 'date');
$disciplina->setPrimaryKey(array('id_disciplina'));

$disc_aluno = $schema->createTable('disc_aluno');
$disc_aluno->addColumn('id_disc_aluno', 'integer', array('unsigned' => true, 'autoincrement' => true));
$disc_aluno->addColumn('id_disciplina', 'integer');
$disc_aluno->addColumn('id_aluno', 'integer');
$disc_aluno->addColumn('ano', 'integer');
$disc_aluno->addColumn('semestre', 'integer');
$disc_aluno->addColumn('diasemana', 'integer');
$disc_aluno->addUniqueIndex(array('id_disciplina','id_aluno','ano','semestre'));
$disc_aluno->setPrimaryKey(array('id_disc_aluno'));

$aula = $schema->createTable('aula');
$aula->addColumn('id_aula', 'integer', array('unsigned' => true, 'autoincrement' => true));
$aula->addColumn('id_disciplina', 'integer');
$aula->addColumn('data_aula', 'date');
$aula->addUniqueIndex(array('id_disciplina','data_aula'));
$aula->setPrimaryKey(array('id_aula'));

$reg_presenca = $schema->createTable('registro_presenca');
$reg_presenca->addColumn('id_registro_presenca', 'integer', array('unsigned' => true, 'autoincrement' => true));
$reg_presenca->addColumn('data_entra', 'datetime', array('notnull' => false));
$reg_presenca->addColumn('data_sai', 'datetime', array('notnull' => false));
$reg_presenca->addColumn('id_aluno', 'integer', array('unsigned' => true));
$reg_presenca->addColumn('id_disciplina', 'integer', array('unsigned' => true));
$reg_presenca->setPrimaryKey(array('id_registro_presenca'));

return $schema;
