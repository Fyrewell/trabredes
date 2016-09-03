<?php

/**
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
$schema = new \Doctrine\DBAL\Schema\Schema();

$post = $schema->createTable('post');
$post->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
$post->addColumn('title', 'string', array('length' => 32));
$post->setPrimaryKey(array('id'));

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
$reg_presenca->addColumn('data_aula', 'datetime');
$reg_presenca->addColumn('faltas', 'integer', array('unsigned' => true));
$reg_presenca->addColumn('id_aluno', 'integer', array('unsigned' => true));
$reg_presenca->addColumn('id_disciplina', 'integer', array('unsigned' => true));

return $schema;
