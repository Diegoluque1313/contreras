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

class JugadorController extends AbstractActionController {

	protected $jugadoresTable;
	private $auth;

	public function __construct() {
		$this->auth = new AuthenticationService();
	}

	protected function getJugadoresTable() {
		if (!$this->jugadoresTable) {
			$sm = $this->getServiceLocator();
			$this->jugadoresTable = $sm->get("Application\Model\JugadoresTable");
		}

		return $this->jugadoresTable;
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

		$paginator = $this->getJugadoresTable()->fetchAll(true);
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery("page", 1));
		$paginator->setItemCountPerPage(5);

		return new ViewModel(array(
			"jugadores" => $paginator,

		));
	}


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

		$publicPath = $_SERVER['DOCUMENT_ROOT'];

		$equipo_id = (int) $this->params()->fromRoute("equipo_id", 0);

		if ($this->request->isPost()) {

			$jugador = new \Application\Model\Jugador();


			    # definimos la carpeta destino
			    $carpetaDestino = $publicPath . "/public/img/players/";
			 
			    # si hay algun archivo que subir
			        # recorremos todos los arhivos que se han subido
			        for($i=0;$i<count($_FILES["jugador_picture"]["name"]);$i++)
			        {
			 
			            # si es un formato de imagen
			            if($_FILES["jugador_picture"]["type"]=="image/jpeg" || $_FILES["jugador_picture"]["type"]=="image/pjpeg" || $_FILES["jugador_picture"]["type"]=="image/gif" || $_FILES["jugador_picture"]["type"]=="image/png")
			            {
			 
			                # si exsite la carpeta o se ha creado
			                if(file_exists($carpetaDestino) || @mkdir($carpetaDestino))
			                {
			                    $origen=$_FILES["jugador_picture"]["tmp_name"];
			                    $destino=$carpetaDestino.$_FILES["jugador_picture"]["name"];
			 
			                    # movemos el archivo
			                    if(@move_uploaded_file($origen, $destino))
			                    {
			                        echo "<br>".$_FILES["jugador_picture"]["name"]." movido correctamente";
			                    }else{
			                        echo "<br>No se ha podido mover el archivo: ".$_FILES["jugador_picture"]["name"];
			                    }
			                }else{
			                    echo "<br>No se ha podido crear la carpeta: ".$carpetaDestino;
			                }
			            }else{
			                echo "<br>".$_FILES["jugador_picture"]["name"]." - NO es imagen jpg, png o gif";
			            }

			    }
    

			$data = array(
						"equipo_id" => (int) $this->request->getPost("equipo_id"),
						"jugador_name" => $this->request->getPost("jugador_name"),
						"jugador_lastname_pat" => $this->request->getPost("jugador_lastname_pat"),
						"jugador_lastname_mat" => $this->request->getPost("jugador_lastname_mat"),
						"jugador_number" => $this->request->getPost("jugador_number"),
						"jugador_picture" => $_FILES["jugador_picture"]["name"],
						"jugador_status" => $this->request->getPost("jugador_status"),
					);

			$jugador->exchangeArray($data);

			$save = $this->getJugadoresTable()->saveJugador($jugador);

						if ($save) {
							$this->flashMessenger()->setNamespace("add")->addMessage("El jugador se ha creado correctamente !!");
						} else {
							$this->flashMessenger()->setNamespace("add_false")->addMessage("El jugador no se ha creado !!");
						}
			/*var_dump($carpetaDestino);
			var_dump($publicPath);
			var_dump($_FILES["jugador_picture"]);
			var_dump($data);*/

			$this->redirect()->toRoute("equipo", array("action" => "index"));
		}	

		return new ViewModel(array(
			"equipo_id" => $equipo_id,
		));
	}


	public function deleteAction() {
		$id = (int) $this->params()->fromRoute("equipo_id", 0);

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

		return $this->redirect()->toRoute("equipo");
	}
	
}
