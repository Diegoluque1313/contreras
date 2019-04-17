<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\FormPruebas;
use Zend\Validator;
use Zend\I18n\Validator as I18Validator;
use Zend\Session\Container;

class IndexController extends AbstractActionController {

	protected $usuariosTable;

	protected function getUsuariosTable() {
		if (!$this->usuariosTable) {
			$sm = $this->getServiceLocator();
			$this->usuariosTable = $sm->get("Application\Model\UsuariosTable");
		}
		return $this->usuariosTable;
	}

	public function indexAction() {
		return new ViewModel();
	}

	public function helloWorldAction() {
		$ejemplo = new \Ejemplo();
		echo $ejemplo->hello();
		echo "Hola Mundo!! Bienvenido al curso de Zend Framework 2 de Victor";
		die();
	}

	public function formAction() {
		$form = new FormPruebas("form");

		$view = array(
			"title" => "Formularios con Zend Framework 2",
			"form" => $form
		);

		if ($this->request->isPost()) {
			$form->setData($this->request->getPost());

			if (!$form->isValid()) {
				$errors = $form->getMessages();
				$view["errors"] = $errors;
			}
		}

		return new ViewModel($view);
	}

	public function getFormDataAction() {
		if ($this->request->getPost("submit")) {
			$data = $this->request->getPost();

			$email = new Validator\EmailAddress();
			$email->setMessage("El email '%value%' no es correcto ");
			$validate_email = $email->isValid($this->request->getPost("email"));

			$alpha = new I18Validator\Alpha();
			$alpha->setMessage("EL nombre '%value%' no son solo letras");
			$validate_alpha = $alpha->isValid($this->request->getPost("nombre"));

			if ($validate_email == true && $validate_alpha == true) {
				$validate = "Validación de datos correcta";
			} else {
				$validate = array(
					$email->getMessages(),
					$alpha->getMessages()
				);
				var_dump($validate);
			}

			var_dump($data);
			die();
		} else {
			$this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/application/index/form");
		}
	}

	public function listarAction() {
		$plugins = $this->Plugins();
		echo $plugins->hoy();

		$var = "fdasf";
		var_dump($plugins->existe($var));

		//$usuarios = $this->getUsuariosTable()->fetchAll();

		$usuarios = $this->getUsuariosTable()->fetchAllSql();
		foreach ($usuarios as $usuario) {
			var_dump($usuario);
		}

		die();
	}

	public function addAction() {
		$usuario = new \Application\Model\Usuario();

		$data = array(
			"name" => "Bruce 2",
			"surname" => "Wayne",
			"description" => "Soy Batman",
			"email" => "bruce2@wayne.com",
			"password" => "batman",
			"image" => "null",
			"alternative" => "null"
		);

		$usuario->exchangeArray($data);

		$usuario_by_email = $this->getUsuariosTable()->getUsuarioByEmail($data["email"]);

		if ($usuario_by_email) {
			$this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/application/index/listar");
		} else {
			$save = $this->getUsuariosTable()->saveUsuario($usuario);
			$this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/application/index/listar");
		}
	}

	public function sesionesAction() {
		//$_SESION["NOMBRE"] = "HOLA";
		$sesion = new Container("sesion");

		if (!$sesion->id) {
			$sesion->id = 1;
			$sesion->nombre = "Curso ZF2 Victor Robles";
		}

		return array("sesion" => $sesion->id);
	}

	public function addSesionAction() {
		$sesion = new Container("sesion");
		$sesion->id++;
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/application/index/sesiones");
	}

	public function deleteSesionAction() {
		$sesion = new Container("sesion");
		$sesion->id--;
		return $this->redirect()->toUrl($this->getRequest()->getBaseUrl() . "/application/index/sesiones");
	}

	public function langAction(){
		$lang_post = $this->params()->fromPost("lang", "es_ES");
		$lang = new Container("lang");
		
		$lang->lang=$lang_post;
		
		$translator = $this->getServiceLocator()->get("translator");
		$translator->setLocale($lang->lang)->setFallbackLocale($lang->lang);
		
		$this->redirect()->toRoute("home");
	}
	
	public function ajaxAction(){
		
		return new ViewModel();
	}
	public function loadAction(){
		if($this->request->isXmlHttpRequest()){
			echo "TE ESCUPO LOS DATOS";
			die();
		}else{
			$this->redirect()->toRoute("home");
		}
		
	}
	
	public function plantillasAction(){
		
		$view = new ViewModel();
		
		$contentView = new ViewModel();
		$contentView->setTemplate("application/templates/content");
		
		$sidebarView = new ViewModel();
		$sidebarView->setTemplate("application/templates/sidebar");
		
		$view->addChild($contentView, "content")
			 ->addChild($sidebarView, "sidebar");
		
		return $view;
	}

}
