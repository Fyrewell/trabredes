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
            ->get('/avaliar_tag/{tag}', [$this, 'avaliar_tag'])
            ->bind('avaliar_tag/{tag}');
            
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
      $dados = ['id_disciplina'=>'','nome'=>'','semestre'=>'','tag'=>''];
      if (!empty($id)){
        $dados = $app['db']->fetchAssoc('SELECT * FROM disciplina WHERE id_disciplina = ?', [$id]);
      }
        $builder = $app['form.factory']->createBuilder('form');

        $choices = array('choice a', 'choice b', 'choice c');

        $form = $builder
            ->add('id', 'text', array('disabled' => true, 'attr' => array('placeholder' => 'id_disciplina', 'value' => $dados['id_disciplina']))
            )
            ->add('nome', 'text', array(
                'constraints' => new Assert\NotBlank(),
                'attr' => array('placeholder' => 'nome', 'value' => $dados['nome']),
            ))
            ->add('semestre', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'semestre', 'value' => $dados['semestre'])))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                if (empty($id)){
                  $app['db']->insert('disciplina', ['nome' => $_POST['form']['nome'],'semestre' => $_POST['form']['semestre']]);
                }else{
                  $sql = "UPDATE disciplina SET nome = ?, semestre = ? WHERE id_disciplina = ?";
                  $app['db']->executeUpdate($sql, [$_POST['form']['nome'],$_POST['form']['semestre'], $id]);
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
        $dados = ['id_registro_presenca'=>'', 'id_aluno'=>'','id_disciplina'=>'','data_aula'=>'','faltas'=>''];
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
            ->add('data_aula', 'date', array(
                'constraints' => new Assert\NotBlank(),
                'data' => empty($dados['data_aula']) ?  new \DateTime() : new \DateTime($dados['data_aula']),
                'attr' => array('placeholder' => 'data_aula', 'format' => 'dd-MM-yyyy'),
            ))
            ->add('faltas', 'text', array('constraints' => new Assert\NotBlank(), 'attr' => array('placeholder' => 'faltas', 'value' => $dados['faltas'])))
            ->add('submit', 'submit')
            ->getForm()
        ;
        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $dt = date("d-m-Y", strtotime($_POST['form']['data_aula']['day'].'-'.$_POST['form']['data_aula']['month'].'-'.$_POST['form']['data_aula']['year']));
                if (empty($id_registro_presenca)){
                  try{
                    $app['db']->insert('registro_presenca', ['faltas' => $_POST['form']['faltas'],'data_aula' => $dt, 'id_aluno' => $_POST['form']['id_aluno'], 'id_disciplina' => $_POST['form']['id_disciplina']]);
                    $app['session']->getFlashBag()->add('success', 'Operação realizada com sucesso');
                  }catch(\Exception $e){
                    $form->addError(new FormError('Chamada para este dia e este aluno, ja cadastrado'));
                  }
                }else{
                  $sql = "UPDATE registro_presenca SET faltas = ?, data_aula = ? WHERE id_registro_presenca= ?";
                  $app['db']->executeUpdate($sql, [$_POST['form']['faltas'], $dt, $id_registro_presenca]);
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
    
    public function avaliar_tag(App $app, Request $request, $tag='')
    {
      $dados = $app['db']->fetchAssoc('SELECT * FROM aluno WHERE tag = ?', [$tag]);
      if (count($dados)>1)
        return new Response('ok - ' . $dados['matricula'] . ' - '. $dados['nome']);
      else
        return new Response('nok - Não encontrado.');
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
