<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PruebasController extends AbstractActionController
{
    public function indexAction()
    {
		$id = $this->params()->fromRoute("id","POR DEFECTO");
		$id2= $this->params()->fromRoute("id2","POR DEFECTO 2");
		
		$this->layout("layout/prueba");
		
		$this->layout()->parametro="Hola que tal?";
		
		$this->layout()->title="Plantillas en Zend Framework 2";
		
        return new ViewModel(array(
			"texto" => "Vista del nuevo metodo action del nuevo controlador 2",
			"id" => $id,
			"id2" => $id2
		));
    }
	
	public function verDatosAjaxAction(){
		$view=new ViewModel();
		
		$view->setTerminal(true);
		return $view;
	}

}
