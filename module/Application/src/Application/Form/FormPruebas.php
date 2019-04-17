<?php
namespace Application\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;

class FormPruebas extends Form{
	public function __construct($name = null){
		parent::__construct($name);
		
		$this->setInputFilter(new \Application\Form\FormPruebasValidator());
		
		$this->add(array(
			"name" => "nombre",
			"options" => array(
				"label" => "Nombre: "
			),
			"attributes" => array(
				"type" => "text",
				"class" => "form-control"
			)
		));
		
		$this->add(array(
			"name" => "email",
			"options" => array(
				"label" => "Email: "
			),
			"attributes" => array(
				"type" => "email",
				"class" => "form-control"
			)
		));
		
		$this->add(array(
			"name" => "submit",
			"attributes" => array(
				"type" => "submit",
				"value" => "Enviar",
				"title" => "Enviar"
			)
		));
		
		
	}
}
