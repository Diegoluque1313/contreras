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

class PartidoController extends AbstractActionController {

	protected $partidosTable;
	private $auth;

	public function __construct() {
		$this->auth = new AuthenticationService();
	}

	protected function getPartidosTable() {
		if (!$this->partidosTable) {
			$sm = $this->getServiceLocator();
			$this->partidosTable = $sm->get("Application\Model\PartidosTable");
		}

		return $this->partidosTable;
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
		$id = (int) $this->params()->fromRoute("id", 0); 

		$paginator = $this->getPartidosTable()->fetchAll(true);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery("page", 1));
		$paginator->setItemCountPerPage(5);

		return new ViewModel(array(
			"partidos" => $paginator,

		));
	}

	/*public function indexAction() {
		return new ViewModel();
	}*/


	public function detailAction() {
		$identity = $this->auth->getIdentity();
		if($identity!=false && $identity!=null){
			
		}else{
			$this->redirect()->toRoute("usuario",array("action"=>"login"));
		}
		
		/* $usuarios = $this->getUsuariosTable()->fetchAll();

		  return new ViewModel(array(
		  "usuarios" => $usuarios
		  )); */
		$equipo_id = (int) $this->params()->fromRoute("equipo_id", 0); 

		//var_dump($equipo_id);

		$paginator = $this->getJugadoresTable()->fetchDetail(true , $equipo_id);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery("page", 1));
		$paginator->setItemCountPerPage(5);

		return new ViewModel(array(
			"jugadores" => $paginator,
			"equipo_id" => $equipo_id,
		));
	}

	public function addAction() {

		$equipo_id = (int) $this->params()->fromRoute("equipo_id", 0);

		if ($this->request->isPost()) {

			$jugador = new \Application\Model\Jugador();

			$data = array(
						"equipo_id" => (int) $this->request->getPost("equipo_id"),
						"jugador_name" => $this->request->getPost("jugador_name"),
						"jugador_lastname_pat" => $this->request->getPost("jugador_lastname_pat"),
						"jugador_lastname_mat" => $this->request->getPost("jugador_lastname_mat"),
						"jugador_number" => $this->request->getPost("jugador_number"),
						"jugador_picture" => $this->request->getPost("jugador_picture"),
						"jugador_status" => $this->request->getPost("jugador_status"),
					);

			$jugador->exchangeArray($data);

			$save = $this->getJugadoresTable()->saveJugador($jugador);

						if ($save) {
							$this->flashMessenger()->setNamespace("add")->addMessage("El jugador se ha creado correctamente !!");
						} else {
							$this->flashMessenger()->setNamespace("add_false")->addMessage("El jugador no se ha creado !!");
						}

			/*var_dump($data);*/

			$this->redirect()->toRoute("jugador", array("action" => "index"));
		}	

		return new ViewModel(array(
			"equipo_id" => $equipo_id,
		));
	}


	public function deleteAction() {
		$id = (int) $this->params()->fromRoute("id", 0);

		if (!$id) {
			$this->redirect()->toRoute("jugador");
		} else {
			$delete = $this->getJugadoresTable()->deleteJugador($id);
			if ($delete) {
				$this->flashMessenger()->setNamespace("add")->addMessage("El jugador se ha borrado correctamente !!!");
			} else {
				$this->flashMessenger()->setNamespace("add_false")->addMessage("El el jugador no se ha borrado !!!");
			}
		}

		return $this->redirect()->toRoute("jugador");
	}
	
}
