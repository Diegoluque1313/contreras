<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\FormAddUsuarios;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\ResultSet\ResultSet;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Application\Form\FormLogin;

class UsuarioController extends AbstractActionController {

	protected $usuariosTable;
	private $auth;

	public function __construct() {
		$this->auth = new AuthenticationService();
	}

	protected function getUsuariosTable() {
		if (!$this->usuariosTable) {
			$sm = $this->getServiceLocator();
			$this->usuariosTable = $sm->get("Application\Model\UsuariosTable");
		}

		return $this->usuariosTable;
	}

	public function indexAction() {
		$identity = $this->auth->getIdentity();
		if($identity!=false && $identity!=null){
			
		}else{
			$this->redirect()->toRoute("usuario",array("action"=>"login"));
		}
		
		/* $usuarios = $this->getUsuariosTable()->fetchAll();

		  return new ViewModel(array(
		  "usuarios" => $usuarios
		  )); */

		$paginator = $this->getUsuariosTable()->fetchAll(true);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery("page", 1));
		$paginator->setItemCountPerPage(5);

		return new ViewModel(array(
			"usuarios" => $paginator
		));
	}

	public function addAction() {
		$form = new FormAddUsuarios("AddUsuarios");

		$view = array(
			"form" => $form
		);

		if ($this->request->isPost()) {
			$form->setData($this->request->getPost());

			if (!$form->isValid()) {
				$errors = $form->getMessages();
				$view["errors"] = $errors;
			} else {
				$usuario = new \Application\Model\Usuario();

				$bcrypt = new Bcrypt(array(
					"salt" => "curso_zend_framework_2_victor_robles",
					"cost" => "6"
				));

				$password = $bcrypt->create($this->request->getPost("password"));

				$data = array(
					"name" => $this->request->getPost("name"),
					"surname" => $this->request->getPost("surname"),
					"description" => $this->request->getPost("description"),
					"email" => $this->request->getPost("email"),
					"password" => $password,
					"image" => "null",
					"alternative" => "null"
				);

				$usuario->exchangeArray($data);

				$usuario_by_email = $this->getUsuariosTable()->getUsuarioByEmail($data["email"]);

				if ($usuario_by_email) {
					$this->flashMessenger()->setNamespace("add_false")->addMessage("El usuario NO se ha creado !!");
				} else {
					$save = $this->getUsuariosTable()->saveUsuario($usuario);

					if ($save) {
						$this->flashMessenger()->setNamespace("add")->addMessage("El usuario se ha creado correctamente !!");
					} else {
						$this->flashMessenger()->setNamespace("add_false")->addMessage("El usuario NO se ha creado !!");
					}
				}
				$this->redirect()->toRoute("usuario", array("action" => "index"));
			}
		}

		return new ViewModel($view);
	}

	public function editAction() {
		$id = (int) $this->params()->fromRoute("id", 0);

		if (!$id) {
			$this->redirect()->toRoute("usuario");
		}

		$usuario = $this->getUsuariosTable()->getUsuario($id);

		if ($usuario != true) {
			$this->redirect()->toRoute("usuario");
		}

		$form = new FormAddUsuarios("AddUsuarios");

		$form->bind($usuario);

		$form->get("submit")->setAttribute("value", "Editar");

		var_dump($this->request->getPost());

		if ($this->request->isPost()) {
			$post = $this->request->getPost();

			if (empty($this->request->getPost("password"))) {
				$post["password"] = $usuario->password;
			} else {
				$bcrypt = new Bcrypt(array(
					"salt" => "curso_zend_framework_2_victor_robles",
					"cost" => "6"
				));

				$post["password"] = $bcrypt->create($this->request->getPost("password"));
			}

			$form->setData($post);

			if ($form->isValid()) {

				$usuario_by_email = $this->getUsuariosTable()->getUsuarioByEmail($this->request->getPost("email"));

				if ($usuario_by_email && $usuario_by_email->id != $usuario->id) {
					$this->flashMessenger()->setNamespace("add_false")->addMessage("El usuario NO se ha editado, utiliza otro email !!!");
				} else {
					$save = $this->getUsuariosTable()->saveUsuario($usuario);
					if ($save) {
						$this->flashMessenger()->setNamespace("add")->addMessage("El usuario se ha editado correctamente !!!");
					}
				}
			}

			$this->redirect()->toRoute("usuario");
		}

		return new ViewModel(array(
			"id" => $id,
			"form" => $form
		));
	}

	public function deleteAction() {
		$id = (int) $this->params()->fromRoute("id", 0);

		if (!$id) {
			$this->redirect()->toRoute("usuario");
		} else {
			$delete = $this->getUsuariosTable()->deleteUsuario($id);
			if ($delete) {
				$this->flashMessenger()->setNamespace("add")->addMessage("El usuario se ha borrado correctamente !!!");
			} else {
				$this->flashMessenger()->setNamespace("add_false")->addMessage("El usuario NO se ha borrado !!!");
			}
		}

		return $this->redirect()->toRoute("usuario");
	}

	public function loginAction() {
		$identity = $this->auth->getStorage()->read();

		if ($identity != false && $identity != null) {
			return $this->redirect()->toRoute("usuario");
		}

		$dbAdapter = $this->getServiceLocator()->get("Zend\Db\Adapter\Adapter");

		$form = new FormLogin("login");

		if($this->request->isPost()){
			$authAdapter = new AuthAdapter($dbAdapter, "usuarios", "email", "password");
			
			$bcrypt = new Bcrypt(array(
				"salt" => "curso_zend_framework_2_victor_robles",
				"cost" => "6"
			));

			$password = $bcrypt->create($this->request->getPost("password"));
			
			$authAdapter->setIdentity($this->request->getPost("email"))
						->setCredential($password);
			
			$this->auth->setAdapter($authAdapter);
			
			$result = $this->auth->authenticate();
			
			if($authAdapter->getResultRowObject() == false){
				$this->flashMessenger()->addMessage("Credenciales incorrectas !!!");
				$this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/usuario/login");
			}else{
				$this->auth->getStorage()->write($authAdapter->getResultRowObject());
				$this->redirect()->toRoute("home");
			}

		}
		
		return array(
			"form" => $form
		);
	}

	public function logoutAction(){
		$this->auth->clearIdentity();
		return $this->redirect()->toRoute("usuario",array("action" => "login"));
	}
	
}
