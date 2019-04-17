<?php

class Ejemplo{
	private $attr;
	public function __construct() {
		$this->attr="HOLA";
	}
	
	public function hello(){
		return $this->attr."!! Soy una libreria propia."."<br/>";
	}
}

