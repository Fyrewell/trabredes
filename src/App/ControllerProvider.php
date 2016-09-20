<?php

namespace App;

use Silex\Application as App;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ControllerProvider implements ControllerProviderInterface
{
    private $app;

    public function connect(App $app)
    {
        date_default_timezone_set('America/Sao_Paulo');
        
        $this->app = $app;

        $app->error([$this, 'error']);

        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/', [$this, 'homepage'])
            ->bind('homepage');

        $controllers
            ->get('/login', [$this, 'login'])
            ->bind('login');

        $controllers
            ->get('/alunos', [$this, 'alunos'])
            ->bind('alunos');
        $controllers
            ->get('/alunos/add/{id}', [$this, 'alunos_add'])
            ->bind('alunos_add/{id}');
        $controllers
            ->post('/alunos/add', [$this, 'alunos_add']);
        $controllers
            ->post('/alunos/add/{id}', [$this, 'alunos_add']);
        $controllers
            ->get('/alunos/add', [$this, 'alunos_add'])
            ->bind('alunos_add');
        $controllers
            ->get('/alunos/remove', [$this, 'alunos_remove'])
            ->bind('alunos_remove');
        $controllers
            ->get('/alunos/remove/{id}', [$this, 'alunos_remove'])
            ->bind('alunos_remove/{id}');
        
        $controllers
            ->get('/disciplinas/add/{id}', [$this, 'disciplinas_add'])
            ->bind('disciplinas_add/{id}');
        $controllers
            ->get('/disciplinas/add', [$this, 'disciplinas_add'])
            ->bind('disciplinas_add');
        $controllers
            ->post('/disciplinas/add', [$this, 'disciplinas_add']);
        $controllers
            ->post('/disciplinas/add/{id}', [$this, 'disciplinas_add']);
        $controllers
            ->get('/disciplinas', [$this, 'disciplinas'])
            ->bind('disciplinas');
        $controllers
            ->get('/disciplinas/remove', [$this, 'disciplinas_remove'])
            ->bind('disciplinas_remove');
        $controllers
            ->get('/disciplinas/remove/{id}', [$this, 'disciplinas_remove'])
            ->bind('disciplinas_remove/{id}');
  
        $controllers
            ->get('/registro_presenca', [$this, 'registro_presenca'])
            ->bind('registro_presenca');
        $controllers
            ->get('/registro_presenca/add/{id_registro_presenca}', [$this, 'registro_presenca_add'])
            ->bind('registro_presenca_add/{id_registro_presenca}');
        $controllers
            ->post('/registro_presenca/add', [$this, 'registro_presenca_add']);
        $controllers
            ->post('/registro_presenca/add/{id_registro_presenca}', [$this, 'registro_presenca_add']);
        $controllers
            ->get('/registro_presenca/add', [$this, 'registro_presenca_add'])
            ->bind('registro_presenca_add');
        $controllers
            ->get('/registro_presenca/remove', [$this, 'registro_presenca_remove'])
            ->bind('registro_presenca_remove');
        $controllers
            ->get('/registro_presenca/remove/{id_registro_presenca}', [$this, 'registro_presenca_remove'])
            ->bind('registro_presenca_remove/{id_registro_presenca}');
        $controllers
            ->get('/registro_presenca_relatorio', [$this, 'registro_presenca_relatorio'])
            ->bind('registro_presenca_relatorio');
        $controllers
            ->get('/registro_presenca_relatorio/{id_registro_presenca}', [$this, 'registro_presenca_relatorio'])
            ->bind('registro_presenca_relatorio/{id_registro_presenca}');
        
        $controllers
            ->get('/matricula_aluno', [$this, 'matricula_aluno'])
            ->bind('matricula_aluno');
        $controllers
            ->get('/matricula_aluno/add/{id_disc_aluno}', [$this, 'matricula_aluno_add'])
            ->bind('matricula_aluno/{id_disc_aluno}');
        $controllers
            ->post('/matricula_aluno/add', [$this, 'matricula_aluno_add']);
        $controllers
            ->post('/matricula_aluno/add/{id_disc_aluno}', [$this, 'matricula_aluno_add']);
        $controllers
            ->get('/matricula_aluno/add', [$this, 'matricula_aluno_add'])
            ->bind('matricula_aluno_add');
        $controllers
            ->get('/matricula_aluno/remove', [$this, 'matricula_aluno_remove'])
            ->bind('matricula_aluno_remove');
        $controllers
            ->get('/matricula_aluno/remove/{id_disc_aluno}', [$this, 'matricula_aluno_remove'])
            ->bind('matricula_aluno_remove/{id_disc_aluno}');
            
        $controllers
            ->get('/avaliar_tag/{tag}', [$this, 'avaliar_tag'])
            ->bind('avaliar_tag/{tag}');
        
        $controllers
            ->get('/marcar_presenca/{tag}', [$this, 'marcar_presenca'])
            ->bind('marcar_presenca/{tag}');
        
        $controllers
            ->get('/gera_aulas', [$this, 'gera_aulas'])
            ->bind('gera_aulas');
        $controllers
            ->get('/gera_aulas/{id_disciplina}', [$this, 'gera_aulas'])
            ->bind('gera_aulas/{id_disciplina}');
        $controllers
            ->get('/gera_aulas/{id_disciplina}/{gera}', [$this, 'gera_aulas'])
            ->bind('gera_aulas/{id_disciplina}/{gera}');
        $controllers
            ->get('/aula/remove', [$this, 'aula_remove'])
            ->bind('aula_remove');
        $controllers
            ->get('/aula/remove/{id}/{id_disciplina}', [$this, 'aula_remove'])
            ->bind('aula_remove/{id}/{id_disciplina}');
          
        $controllers
            ->get('/doctrine', [$this, 'doctrine'])
            ->bind('doctrine');
        return $controllers;
    }

    public function homepage(App $app)
    {
      /*
        $app['session']->getFlashBag()->add('warning', 'Warning flash message');
        $app['session']->getFlashBag()->add('info', 'Info flash message');
        $app['session']->getFlashBag()->add('success', 'Success flash message');
        $app['session']->getFlashBag()->add('danger', 'Danger flash message');
      */
        return $app['twig']->render('index.html.twig');
    }

    public function login(App $app)
    {
        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.utils']->getLastAuthenticationError(),
            'username' => $app['security.utils']->getLastUsername(),
        ));
    }

    public function doctrine(App $app)
    {
        return $app['twig']->render('doctrine.html.twig', array(
            'posts' => $app['db']->fetchAll('SELECT * FROM post'),
        ));
    }

    public function alunos(App $app, Request $request)
    {
        return $app['twig']->render('alunos.html.twig', array(
            'dados' => $app['db']->fetchAll('SELECT * FROM aluno')
        ));
    }
    
    public function alunos_add(App $app, Request $request, $id=0)
    {
      $dados = ['id_aluno'=>'','nome'=>'','matricula'=>'','tag'=>''];
      if (!empty($id)){
        $dados = $app['db']->fetchAssoc('SELECT * FROM aluno WHERE id_aluno = ?', [$id]);
      }
        $builder = $app['form.factory']->createBuilder('form');

        $choices = array('choice a', 'choice b', 'choice c');

        $form = $builder
            ->add('id', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'id_aluno', 'value' => $dados['id_aluno']))
            )
            ->add('nome', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('placeholder' => 'nome', 'value' => $dados['nome']),
            ))
            ->add('matricula', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'matricula', 'value' => $dados['matricula'])))
            ->add('tag', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'tag', 'value' => $dados['tag'])))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                if (empty($id)){
                  $app['db']->insert('aluno', ['nome' => $_POST['form']['nome'],'matricula' => $_POST['form']['matricula'],'tag' => $_POST['form']['tag']]);
                }else{
                  $sql = "UPDATE aluno SET nome = ?, matricula = ?, tag = ? WHERE id_aluno = ?";
                  $app['db']->executeUpdate($sql, [$_POST['form']['nome'],$_POST['form']['matricula'],$_POST['form']['tag'], $id]);
                }
                $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
            } else {
                $form->addError(new FormError('Erro interno'));
                $app['session']->getFlashBag()->add('info', 'O formulario foi recebido porem é invalido');
            }
        }

        return $app['twig']->render('alunos_add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function alunos_remove(App $app, Request $request, $id=0)
    {
      $app['db']->delete("aluno", ['id_aluno'=>$id]);
      return $app->redirect('../../alunos');
    }
    
    public function disciplinas(App $app, Request $request)
    {
      return $app['twig']->render('disciplinas.html.twig', array(
            'dados' => $app['db']->fetchAll('SELECT * FROM disciplina')
        ));
    }

    public function disciplinas_add(App $app, Request $request, $id=0)
    {
      $dados = ['id_disciplina'=>'','nome'=>'','semestre'=>'','diasemana'=>'','data_ini'=>'','data_fim'=>''];
      if (!empty($id)){
        $dados = $app['db']->fetchAssoc('SELECT * FROM disciplina WHERE id_disciplina = ?', [$id]);
      }
        $builder = $app['form.factory']->createBuilder('form');

        $choices_diasemana[0] = 'Dom.';
        $choices_diasemana[1] = 'Seg.';
        $choices_diasemana[2] = 'Ter.';
        $choices_diasemana[3] = 'Qua.';
        $choices_diasemana[4] = 'Qui.';
        $choices_diasemana[5] = 'Sex.';
        $choices_diasemana[6] = 'Sáb.';
        
        $form = $builder
            ->add('id', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'id_disciplina', 'value' => $dados['id_disciplina']))
            )
            ->add('nome', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('placeholder' => 'nome', 'value' => $dados['nome']),
            ))
            ->add('semestre', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'semestre', 'value' => $dados['semestre'])))
            ->add('diasemana', 'choice', array(
                'choices' => $choices_diasemana,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $dados['diasemana'] => ['selected' => 'selected'],
                ],
            ))
            ->add('data_ini', 'date', array(
                'constraints' => new Assert\NotBlank(),
                'data' => empty($dados['data_ini']) ?  new \DateTime() : new \DateTime($dados['data_ini']),
                'attr' => array('placeholder' => 'data_ini', 'format' => 'dd/MM/yyyy'),
            ))
            ->add('data_fim', 'date', array(
                'constraints' => new Assert\NotBlank(),
                'data' => empty($dados['data_fim']) ?  new \DateTime() : new \DateTime($dados['data_fim']),
                'attr' => array('placeholder' => 'data_fim', 'format' => 'dd/MM/yyyy'),
            ))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $dt_ini = date("Y-m-d H:i", strtotime($_POST['form']['data_ini']['day'].'-'.$_POST['form']['data_ini']['month'].'-'.$_POST['form']['data_ini']['year']));
                $dt_fim = date("Y-m-d H:i", strtotime($_POST['form']['data_fim']['day'].'-'.$_POST['form']['data_fim']['month'].'-'.$_POST['form']['data_fim']['year']));
                if (empty($id)){
                  $app['db']->insert('disciplina', ['nome' => $_POST['form']['nome'],'semestre' => $_POST['form']['semestre'],'diasemana' => $_POST['form']['diasemana']
                                                    ,'data_ini' => $dt_ini,'data_fim' => $dt_fim]);
                }else{
                  $sql = "UPDATE disciplina SET nome = ?, semestre = ?, diasemana = ?, data_ini = ?, data_fim = ? WHERE id_disciplina = ?";
                  $app['db']->executeUpdate($sql, [$_POST['form']['nome'],$_POST['form']['semestre'],$_POST['form']['diasemana'],$dt_ini,$dt_fim, $id]);
                }
                $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
            } else {
                $form->addError(new FormError('Erro interno'));
                $app['session']->getFlashBag()->add('info', 'O formulario foi recebido porem é invalido');
            }
        }

        return $app['twig']->render('disciplinas_add.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    public function gera_aulas(App $app, Request $request, $id_disciplina=0, $gera=0){
      if ($gera){
        $dados = $app['db']->fetchAssoc('SELECT * FROM disciplina WHERE id_disciplina = ? ', [$id_disciplina]);
        print_r($dados);
        $dt = date('d-m-Y', strtotime($dados['data_ini']));
        while (strtotime($dt) < strtotime($dados['data_fim'])){
          $app['db']->insert('aula', ['id_disciplina' => $id_disciplina, 'data_aula' => $dt]);
          $dt = strtotime("+7 day", strtotime($dt));
          $dt = date('d-m-Y', $dt);
        }
        return $app->redirect('../../gera_aulas/'.$id_disciplina);
      }
      return $app['twig']->render('gera_aulas.html.twig', array(
        'dados' => $app['db']->fetchAll('SELECT * FROM aula WHERE id_disciplina = ? ORDER BY id_aula', [$id_disciplina]),
        'disciplina_id' => $id_disciplina
      ));
    }
    
    public function aula_remove(App $app, Request $request, $id=0, $id_disciplina)
    {
      $app['db']->delete("aula", ['id_aula'=>$id]);
      return $app->redirect('../../../gera_aulas/'.$id_disciplina);
    }
    
    public function disciplinas_remove(App $app, Request $request, $id=0)
    {
      $app['db']->delete("disciplina", ['id_disciplina'=>$id]);
      return $app->redirect('../../disciplinas');
    }
    
    public function registro_presenca(App $app, Request $request)
    {
      return $app['twig']->render('registro_presenca.html.twig', array(
            'dados' => $app['db']->fetchAll(
            'SELECT d.nome as disciplina_nome, a.nome as aluno_nome, rp.*
               FROM registro_presenca rp 
         INNER JOIN aluno a ON a.id_aluno = rp.id_aluno 
         INNER JOIN disciplina d ON d.id_disciplina = rp.id_disciplina ')
        ));
    }

    public function registro_presenca_add(App $app, Request $request, $id_registro_presenca=0)
    {
        $dados = ['id_registro_presenca'=>'', 'id_aluno'=>'','id_disciplina'=>'','data_entra'=>'','data_sai'=>''];
        if (!empty($id_registro_presenca)){
          $dados = $app['db']->fetchAssoc('SELECT * FROM registro_presenca WHERE id_registro_presenca = ? ', [$id_registro_presenca]);
        }
        $choices_disc = [''=>'']; $disc_selected = 0;
        $r_disc = $app['db']->fetchAll('SELECT id_disciplina,nome FROM disciplina');
        foreach ($r_disc as $c){
          $choices_disc[$c['id_disciplina']] = $c['nome'];
          if ($dados['id_disciplina'] == $c['id_disciplina'] && !$disc_selected)
            $disc_selected = count($choices_disc)-1;
        }
        $choices_alunos = [''=>'']; $aluno_selected = 0;
        $r_alunos = $app['db']->fetchAll('SELECT id_aluno,nome FROM aluno');
        foreach ($r_alunos as $r){
          $choices_alunos[$r['id_aluno']] = $r['nome'];
          if ($dados['id_aluno'] == $r['id_aluno'] && !$aluno_selected)
            $aluno_selected = count($choices_alunos)-1;
        }
        
        $builder = $app['form.factory']->createBuilder('form');

        $form = $builder
            ->add('id_registro_presenca', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'id_registro_presenca', 'value' => $dados['id_registro_presenca'])))
            ->add('id_disciplina', 'choice', array(
                'choices' => $choices_disc,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $disc_selected => ['selected' => 'selected'],
                ],
            ))
            ->add('id_aluno', 'choice', array(
                'choices' => $choices_alunos,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $aluno_selected => ['selected' => 'selected'],
                ],
            ))
            ->add('data_entra', 'datetime', array(
                'constraints' => new Assert\NotBlank(),
                'data' => empty($dados['data_entra']) ?  new \DateTime() : new \DateTime($dados['data_entra']),
                'attr' => array('placeholder' => 'data_entra', 'format' => 'dd/MM/yyyy H:i:s'),
            ))
            ->add('data_sai', 'datetime', array(
                'constraints' => new Assert\NotBlank(),
                'data' => empty($dados['data_sai']) ?  new \DateTime() : new \DateTime($dados['data_sai']),
                'attr' => array('placeholder' => 'data_sai', 'format' => 'dd/MM/yyyy H:i:s'),
            ))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $dt_entra = date("Y-m-d H:i", strtotime($_POST['form']['data_entra']['date']['day'].'-'.$_POST['form']['data_entra']['date']['month'].'-'.$_POST['form']['data_entra']['date']['year'].' '.
                                                        $_POST['form']['data_entra']['time']['hour'].':'.$_POST['form']['data_entra']['time']['minute']));
                $dt_sai = date("Y-m-d H:i", strtotime($_POST['form']['data_sai']['date']['day'].'-'.$_POST['form']['data_sai']['date']['month'].'-'.$_POST['form']['data_sai']['date']['year'].' '.
                                                        $_POST['form']['data_sai']['time']['hour'].':'.$_POST['form']['data_sai']['time']['minute']));
                if (empty($id_registro_presenca)){
                  try{
                    $app['db']->insert('registro_presenca', ['data_entra' => $dt_entra,'data_sai' => $dt_sai, 
                    'id_aluno' => $_POST['form']['id_aluno'], 'id_disciplina' => $_POST['form']['id_disciplina']]);
                    $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
                  }catch(\Exception $e){
                    $form->addError(new FormError('Chamada para este dia e este aluno, ja cadastrado'));
                  }
                }else{
                  $sql = "UPDATE registro_presenca SET data_entra = ?, data_sai = ? WHERE id_registro_presenca= ?";
                  $app['db']->executeUpdate($sql, [$dt_entra, $dt_sai, $id_registro_presenca]);
                  $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
                }
            } else {
                $form->addError(new FormError('Erro interno'));
                $app['session']->getFlashBag()->add('info', 'O formulario foi recebido porem é invalido');
            }
        }

        return $app['twig']->render('registro_presenca_add.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    public function registro_presenca_remove(App $app, Request $request, $id_registro_presenca=0)
    {
      $app['db']->delete("registro_presenca", ['id_registro_presenca'=>$id_registro_presenca]);
      return $app->redirect('../../registro_presenca');
    }
    
    public function registro_presenca_relatorio(App $app, Request $request, $id_registro_presenca=0)
    {
      header("Content-type: text/csv;");
      header("Content-Disposition: attachment; filename=relatorio_presenca.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
      
      $dados = $app['db']->fetchAll("
      SELECT da.semestre,a.nome as aluno_nome,a.matricula,d.nome as disciplina_nome, d.id_disciplina, da.id_aluno, dx.data_aula
      FROM disciplina d
      INNER JOIN disc_aluno da ON da.id_disciplina = d.id_disciplina 
      INNER JOIN aluno a ON a.id_aluno = da.id_aluno 
      INNER JOIN aula dx ON dx.id_disciplina = da.id_disciplina
     -- LEFT JOIN registro_presenca rp ON rp.id_aluno = da.id_aluno AND rp.id_disciplina = da.id_disciplina AND (julianday('rp.data_entra) + julianday(dx.data_aula) > 0)
      ORDER BY dx.id_aula
      ");
      $regs_p = $app['db']->fetchAll("SELECT rp.* FROM registro_presenca rp");
      foreach($regs_p as $r){
        $arrRegs[$r['id_aluno']][$r['id_disciplina']][date('d-m-Y',strtotime($r['data_entra']))]['data_entra'] = $r['data_entra'];
        $arrRegs[$r['id_aluno']][$r['id_disciplina']][date('d-m-Y',strtotime($r['data_entra']))]['data_sai'] = $r['data_sai'];
      }
      echo "Disciplina;Matricula;Nome Aluno;Data Aula;Semestre;Data/Hora Entrou;Data/Hora Saiu;Qtd Faltas\n";
      $t = '';
      error_reporting(!E_NOTICE);
      foreach($dados as $d){
        $d['data_entra'] = (!empty($arrRegs[$d['id_aluno']][$d['id_disciplina']][date('d-m-Y',strtotime($d['data_aula']))]['data_entra'])) ? $arrRegs[$d['id_aluno']][$d['id_disciplina']][date('d-m-Y',strtotime($d['data_aula']))]['data_entra']: '';
        $d['data_sai'] = (!empty($arrRegs[$d['id_aluno']][$d['id_disciplina']][date('d-m-Y',strtotime($d['data_aula']))]['data_sai'])) ? $arrRegs[$d['id_aluno']][$d['id_disciplina']][date('d-m-Y',strtotime($d['data_aula']))]['data_sai']: '';
        $t_calc_f = 200-((date('H',strtotime($d['data_sai']))*60 + date('i',strtotime($d['data_sai']))) - (date('H',strtotime($d['data_entra']))*60 + date('i',strtotime($d['data_entra']))));
        $t_calc_f = ($t_calc_f*4)/200;
        $qtd_faltas = ($t_calc_f > 0 ? (round($t_calc_f) > 4 ? 4 : round($t_calc_f)) : 0);
        if (new \DateTime($d['data_aula'])<=new \DateTime()){
          $t .= $d['disciplina_nome'] . ';' . $d['matricula'] . ';' . $d['aluno_nome'] . ';' . $d['data_aula'] . ';' . $d['semestre'] . ';' . $d['data_entra'] . ';' . $d['data_sai'] . ';' . $qtd_faltas . "\n";
        }
      }
      echo $t;
      
      exit();
    }
    
    public function avaliar_tag(App $app, Request $request, $tag='')
    {
      $dados = $app['db']->fetchAssoc('SELECT * FROM aluno WHERE tag = ?', [$tag]);
      if (count($dados)>1)
        return new Response('ok - ' . $dados['matricula'] . ' - '. $dados['nome'] . "\n</html>");
      else
        return new Response('nok - Nao encontrado.' . "\n</html>");
    }
    
    public function marcar_presenca(App $app, Request $request, $tag='', $num_faltas=4)
    {
      $dt = date("Y-m-d H:i");
      $dados = $app['db']->fetchAssoc("SELECT rp.*,al.*,da.*,d.nome as nome_disciplina FROM aluno al INNER JOIN disc_aluno da ON da.id_aluno = al.id_aluno
      INNER JOIN disciplina d ON d.id_disciplina = da.id_disciplina
      INNER JOIN aula aa ON da.id_disciplina = aa.id_disciplina AND aa.data_aula = ?
                LEFT JOIN registro_presenca rp ON al.id_aluno = rp.id_aluno AND rp.id_disciplina = da.id_disciplina AND strftime('%s', rp.data_entra) BETWEEN strftime('%s', '". date('Y-m-d',time())."') AND strftime('%s', '".date('Y-m-d H:i:s', strtotime('+1 day', time()))."')
                WHERE al.tag = ? AND da.diasemana = ?", [date('d-m-Y',time()),$tag, date("w", time())]);
      //print_r($dados); exit;
      if (count($dados)>1){
        try{
            if (empty($dados['data_entra'])){
              $app['db']->insert('registro_presenca', ['data_entra' => $dt, 'data_sai' => $dt, 'id_aluno' => $dados['id_aluno'], 'id_disciplina' => $dados['id_disciplina']]);
            }else{
              $sql = "UPDATE registro_presenca SET data_sai = ? WHERE id_registro_presenca= ?";
              $app['db']->executeUpdate($sql, [$dt, $dados['id_registro_presenca']]);
            }
            return new Response('ok - ' . $dados['matricula'] . ' - '. $dados['nome'] . "\nDisciplina: ". $dados['nome_disciplina'] ."\n</html>");
          }catch(\Exception $e){
            return new Response('error - ' . $dados['matricula'] . ' - '. $dados['nome'] . "\n</html>");
          }
        return new Response('ok - ' . $dados['matricula'] . ' - '. $dados['nome'] . "\nDisciplina: ".  "\n</html>");
      }else
        return new Response('nok - Nao encontrado.' . "\n</html>");
    }
    
    
    public function matricula_aluno(App $app, Request $request)
    {
      return $app['twig']->render('matricula_aluno.html.twig', array(
            'dados' => $app['db']->fetchAll(
            'SELECT d.nome as disciplina_nome, a.nome as aluno_nome, ma.*
               FROM disc_aluno ma 
         INNER JOIN aluno a ON a.id_aluno = ma.id_aluno 
         INNER JOIN disciplina d ON d.id_disciplina = ma.id_disciplina ')
        ));
    }

    public function matricula_aluno_add(App $app, Request $request, $id_disc_aluno=0)
    {
        $dados = ['id_disc_aluno'=>'', 'id_disciplina'=>'','id_aluno'=>'','ano'=>'','semestre'=>'','diasemana'=>''];
        if (!empty($id_disc_aluno)){
          $dados = $app['db']->fetchAssoc('SELECT * FROM disc_aluno WHERE id_disc_aluno = ? ', [$id_disc_aluno]);
        }
        $choices_disc = [''=>'']; $disc_selected = 0;
        $r_disc = $app['db']->fetchAll('SELECT id_disciplina,nome FROM disciplina');
        foreach ($r_disc as $c){
          $choices_disc[$c['id_disciplina']] = $c['nome'];
          if ($dados['id_disciplina'] == $c['id_disciplina'] && !$disc_selected)
            $disc_selected = count($choices_disc)-1;
        }
        $choices_alunos = [''=>'']; $aluno_selected = 0;
        $r_alunos = $app['db']->fetchAll('SELECT id_aluno,nome FROM aluno');
        foreach ($r_alunos as $r){
          $choices_alunos[$r['id_aluno']] = $r['nome'];
          if ($dados['id_aluno'] == $r['id_aluno'] && !$aluno_selected)
            $aluno_selected = count($choices_alunos)-1;
        }
        
        $choices_diasemana[0] = 'Dom.';
        $choices_diasemana[1] = 'Seg.';
        $choices_diasemana[2] = 'Ter.';
        $choices_diasemana[3] = 'Qua.';
        $choices_diasemana[4] = 'Qui.';
        $choices_diasemana[5] = 'Sex.';
        $choices_diasemana[6] = 'Sáb.';
        
        $builder = $app['form.factory']->createBuilder('form');

        $form = $builder
            ->add('id_disc_aluno', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'id_disc_aluno', 'value' => $dados['id_disc_aluno'])))
            ->add('id_disciplina', 'choice', array(
                'choices' => $choices_disc,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $disc_selected => ['selected' => 'selected'],
                ],
            ))
            ->add('id_aluno', 'choice', array(
                'choices' => $choices_alunos,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $aluno_selected => ['selected' => 'selected'],
                ],
            ))
            ->add('ano', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'ano', 'value' => $dados['ano'])))
            ->add('semestre', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'semestre', 'value' => $dados['semestre'])))
            ->add('diasemana', 'choice', array(
                'choices' => $choices_diasemana,
                'multiple' => false,
                'expanded' => false,
                'choice_attr' => [
                  $dados['diasemana'] => ['selected' => 'selected'],
                ],
            ))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                if (empty($id_disc_aluno)){
                  try{
                    $app['db']->insert('disc_aluno', ['diasemana' => $_POST['form']['diasemana'], 'ano' => $_POST['form']['ano'], 'semestre' => $_POST['form']['semestre'],
                    'id_aluno' => $_POST['form']['id_aluno'], 'id_disciplina' => $_POST['form']['id_disciplina']]);
                    $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
                  }catch(\Exception $e){
                    $form->addError(new FormError('Chamada para este dia e este aluno, ja cadastrado'));
                  }
                }else{
                  $sql = "UPDATE disc_aluno SET semestre = ?, ano = ?, diasemana = ? WHERE id_disc_aluno= ?";
                  $app['db']->executeUpdate($sql, [$_POST['form']['semestre'], $_POST['form']['ano'], $_POST['form']['diasemana'], $id_disc_aluno]);
                  $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
                }
            } else {
                $form->addError(new FormError('Erro interno'));
                $app['session']->getFlashBag()->add('info', 'O formulario foi recebido porem é invalido');
            }
        }

        return $app['twig']->render('matricula_aluno_add.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }
    
    public function matricula_aluno_remove(App $app, Request $request, $id_disc_aluno=0)
    {
      $app['db']->delete("disc_aluno", ['id_disc_aluno'=>$id_disc_aluno]);
      return $app->redirect('../../matricula_aluno');
    }
    
    public function form(App $app, Request $request)
    {
        $builder = $app['form.factory']->createBuilder('form');

        $choices = array('choice a', 'choice b', 'choice c');

        $form = $builder
            ->add(
                $builder->create('sub-form', 'form')
                    ->add('subformemail1', 'email', array(
                        'constraints' => array(new Assert\NotBlank(), new Assert\Email()),
                        'attr' => array('placeholder' => 'email constraints'),
                        'label' => 'A custom label : ',
                    ))
                    ->add('subformtext1', 'text')
            )
            ->add('text1', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('placeholder' => 'not blank constraints'),
            ))
            ->add('text2', 'text', array('attr' => array('class' => 'span1', 'placeholder' => '.span1')))
            ->add('text3', 'text', array('attr' => array('class' => 'span2', 'placeholder' => '.span2')))
            ->add('text4', 'text', array('attr' => array('class' => 'span3', 'placeholder' => '.span3')))
            ->add('text5', 'text', array('attr' => array('class' => 'span4', 'placeholder' => '.span4')))
            ->add('text6', 'text', array('attr' => array('class' => 'span5', 'placeholder' => '.span5')))
            ->add('text8', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'disabled field')))
            ->add('textarea', 'textarea')
            ->add('email', 'email')
            ->add('integer', 'integer')
            ->add('money', 'money')
            ->add('number', 'number')
            ->add('password', 'password')
            ->add('percent', 'percent')
            ->add('search', 'search')
            ->add('url', 'url')
            ->add('choice1', 'choice', array(
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('choice2', 'choice', array(
                'choices' => $choices,
                'multiple' => false,
                'expanded' => true,
            ))
            ->add('choice3', 'choice', array(
                'choices' => $choices,
                'multiple' => true,
                'expanded' => false,
            ))
            ->add('choice4', 'choice', array(
                'choices' => $choices,
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('country', 'country')
            ->add('language', 'language')
            ->add('locale', 'locale')
            ->add('timezone', 'timezone')
            ->add('date', 'date')
            ->add('datetime', 'datetime')
            ->add('time', 'time')
            ->add('birthday', 'birthday')
            ->add('checkbox', 'checkbox')
            ->add('file', 'file')
            ->add('radio', 'radio')
            ->add('password_repeated', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'options' => array('required' => true),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('submit', 'submit')
            ->getForm()
        ;

        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $app['session']->getFlashBag()->add('success', 'The form is valid');
            } else {
                $form->addError(new FormError('This is a global error'));
                $app['session']->getFlashBag()->add('info', 'The form is bound, but not valid');
            }
        }

        return $app['twig']->render('form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function cache(App $app)
    {
        $response = new Response($app['twig']->render('cache.html.twig', array('date' => date('Y-M-d h:i:s'))));
        $response->setTtl(10);

        return $response;
    }

    public function error(\Exception $e, $code)
    {
        if ($this->app['debug']) {
            return;
        }

        switch ($code) {
            case 404:
                $message = 'The requested page could not be found.';
                break;
            default:
                $message = 'We are sorry, but something went terribly wrong.';
        }

        return new Response($message, $code);
    }
}
