<?php

namespace MPruebas\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
	
	public function helloWorldAction(){
		echo "Hola Mundo!! Bienvenido al curso de Zend Framework 2 de Victor fdsa";
		die();
	}
}
