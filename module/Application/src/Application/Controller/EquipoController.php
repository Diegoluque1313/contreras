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

class EquipoController extends AbstractActionController {

	protected $equiposTable;
	private $auth;

	public function __construct() {
		$this->auth = new AuthenticationService();
	}

	protected function getEquiposTable() {
		if (!$this->equiposTable) {
			$sm = $this->getServiceLocator();
			$this->equiposTable = $sm->get("Application\Model\EquiposTable");
		}

		return $this->equiposTable;
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

		$paginator = $this->getEquiposTable()->fetchAll(true);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery("page", 1));
		$paginator->setItemCountPerPage(5);

		return new ViewModel(array(
			"equipos" => $paginator
		));
	}

	public function addAction() {

		#comentario de prueba para git

		$publicPath = $_SERVER['DOCUMENT_ROOT'];

		if ($this->request->isPost()) {

			$equipo = new \Application\Model\Equipo();

				# definimos la carpeta destino
			    $carpetaDestino = $publicPath . "/public/img/teams/";
			 
			    # si hay algun archivo que subir
			        # recorremos todos los arhivos que se han subido
			        for($i=0;$i<count($_FILES["image"]["name"]);$i++)
			        {
			 
			            # si es un formato de imagen
			            if($_FILES["image"]["type"]=="image/jpeg" || $_FILES["image"]["type"]=="image/pjpeg" || $_FILES["image"]["type"]=="image/gif" || $_FILES["image"]["type"]=="image/png")
			            {
			 
			                # si exsite la carpeta o se ha creado
			                if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
			                {
			                    $origen=$_FILES["image"]["tmp_name"];
			                    $destino=$carpetaDestino.$_FILES["image"]["name"];
			 
			                    # movemos el archivo
			                    if(@move_uploaded_file($origen, $destino))
			                    {
			                        echo "<br>".$_FILES["image"]["name"]." movido correctamente";
			                    }else{
			                        echo "<br>No se ha podido mover el archivo: ".$_FILES["image"]["name"];
			                    }
			                }else{
			                    echo "<br>No se ha podido crear la carpeta: ".$carpetaDestino;
			                }
			            }else{
			                echo "<br>".$_FILES["image"]["name"]." - NO es imagen jpg, png o gif";
			            }

			    }

			$data = array(
						"equipo_name" => $this->request->getPost("name"),
						"equipo_logo" => $_FILES["image"]["name"],
						"equipo_status" => $this->request->getPost("status"),
					);

			$equipo->exchangeArray($data);

			$save = $this->getEquiposTable()->saveEquipo($equipo);

						if ($save) {
							$this->flashMessenger()->setNamespace("add")->addMessage("El equipo se ha creado correctamente !!");
						} else {
							$this->flashMessenger()->setNamespace("add_false")->addMessage("El equipo no se ha creado !!");
						}
			/*var_dump($_FILES["image"]["name"]);
			var_dump($data);*/

			$this->redirect()->toRoute("equipo", array("action" => "index"));
		}	

		return new ViewModel();
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
			$this->redirect()->toRoute("equipo");
		} else {
			$delete = $this->getEquiposTable()->deleteEquipo($id);
			if ($delete) {
				$this->flashMessenger()->setNamespace("add")->addMessage("El equipo se ha borrado correctamente !!!");
			} else {
				$this->flashMessenger()->setNamespace("add_false")->addMessage("El el equipo no se ha borrado !!!");
			}
		}

		return $this->redirect()->toRoute("equipo");
	}
}
